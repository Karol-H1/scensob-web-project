"""Export a running WordPress division site to standalone HTML/CSS.

The WordPress build is the design. This walks the rendered pages, pulls the
stylesheets and images down alongside them, rewrites every reference to a
relative path, and strips the WordPress plumbing that means nothing outside a
WordPress install. The result opens by double-clicking index.html.
"""

import os
import re
import shutil
import sys
import urllib.parse
import urllib.request

from bs4 import BeautifulSoup  # noqa: E402

# Output lands in static/ beside this script.
#
# Note for anyone running this on a OneDrive-synced checkout: sync can hold a
# lock on the output directory and make the clean rebuild fail with a
# PermissionError. Build to a local path and copy across if that happens.
REPO = os.path.dirname(os.path.abspath(__file__))

SITES = [
    {
        "name": "IT Division",
        "base": "http://127.0.0.1:9401",
        "out": os.path.join(REPO, "static", "it"),
        "pages": [
            ("/", "index.html"),
            ("/services/", "services.html"),
            ("/products/", "products.html"),
            ("/gallery/", "gallery.html"),
            ("/contact/", "contact.html"),
        ],
    },
    {
        "name": "Transport & Delivery",
        "base": "http://127.0.0.1:9402",
        "out": os.path.join(REPO, "static", "transport-delivery"),
        "pages": [
            ("/", "index.html"),
            ("/services/", "services.html"),
            ("/catalog/", "catalog.html"),
            ("/about/", "about.html"),
            ("/contact/", "contact.html"),
        ],
    },
]

# WordPress emits a pile of tags that only make sense inside WordPress.
DROP_LINK_RELS = {
    "https://api.w.org/", "EditURI", "wlwmanifest", "shortlink",
    "alternate", "pingback", "dns-prefetch", "prev", "next",
    "canonical", "modulepreload",
}
DROP_META_NAMES = {"generator"}

# WordPress drives the responsive menu through its Interactivity API, which
# doesn't exist once the pages are standalone -- and ES modules won't load over
# file:// anyway. The directives are stripped and replaced with this.
NAV_SHIM = """
(function () {
  var container = document.querySelector('.wp-block-navigation__responsive-container');
  if (!container) { return; }
  var openBtn = document.querySelector('.wp-block-navigation__responsive-container-open');
  var closeBtn = document.querySelector('.wp-block-navigation__responsive-container-close');

  function open() {
    container.classList.add('is-menu-open', 'has-modal-open');
    document.documentElement.style.overflow = 'hidden';
    if (closeBtn) { closeBtn.focus(); }
  }

  function close() {
    container.classList.remove('is-menu-open', 'has-modal-open');
    document.documentElement.style.overflow = '';
    if (openBtn) { openBtn.focus(); }
  }

  if (openBtn) { openBtn.addEventListener('click', open); }
  if (closeBtn) { closeBtn.addEventListener('click', close); }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { close(); }
  });
})();
"""


def fetch(url):
    req = urllib.request.Request(url, headers={"User-Agent": "scensob-export"})
    with urllib.request.urlopen(req, timeout=30) as resp:
        return resp.read()


def safe_name(url):
    """A stable, filesystem-safe filename for an asset URL."""
    path = urllib.parse.urlparse(url).path
    name = os.path.basename(path) or "asset"
    name = re.sub(r"[^A-Za-z0-9._-]", "_", name)
    return name


class Exporter:
    def __init__(self, site):
        self.site = site
        self.base = site["base"].rstrip("/")
        self.out = site["out"]
        self.page_map = {}
        for path, filename in site["pages"]:
            self.page_map[path] = filename
            self.page_map[path.rstrip("/") or "/"] = filename
        self.assets = {}
        self.counts = {"pages": 0, "css": 0, "img": 0}

    def abs_url(self, url):
        return urllib.parse.urljoin(self.base + "/", url)

    def is_internal(self, url):
        return urllib.parse.urlparse(self.abs_url(url)).netloc == \
            urllib.parse.urlparse(self.base).netloc

    def save_asset(self, url, subdir):
        """Download an asset once; return its path relative to a page."""
        absolute = self.abs_url(url)
        if absolute in self.assets:
            return self.assets[absolute]

        name = safe_name(absolute)
        target_dir = os.path.join(self.out, "assets", subdir)
        os.makedirs(target_dir, exist_ok=True)

        # Avoid collisions between different URLs sharing a basename.
        candidate, n = name, 1
        while os.path.exists(os.path.join(target_dir, candidate)) and \
                candidate not in [os.path.basename(v) for v in self.assets.values()]:
            root, ext = os.path.splitext(name)
            candidate = "%s-%d%s" % (root, n, ext)
            n += 1

        data = fetch(absolute)
        with open(os.path.join(target_dir, candidate), "wb") as fh:
            fh.write(data)

        rel = "assets/%s/%s" % (subdir, candidate)
        self.assets[absolute] = rel
        self.counts["css" if subdir == "css" else "img"] += 1
        return rel

    def rewrite_css(self, rel_path):
        """Pull any url() targets referenced by a stylesheet down too."""
        full = os.path.join(self.out, rel_path)
        with open(full, "r", encoding="utf-8", errors="ignore") as fh:
            css = fh.read()

        def repl(match):
            raw = match.group(1).strip("'\"")
            if raw.startswith("data:") or not self.is_internal(raw):
                return match.group(0)
            saved = self.save_asset(raw, "img")
            # Stylesheets live in assets/css/, so step back up one level.
            return "url(../%s)" % saved[len("assets/"):]

        css = re.sub(r"url\(([^)]+)\)", repl, css)
        with open(full, "w", encoding="utf-8") as fh:
            fh.write(css)

    def map_link(self, href):
        """Turn an internal WordPress URL into a relative .html file."""
        parsed = urllib.parse.urlparse(self.abs_url(href))
        path = parsed.path
        if path in self.page_map:
            return self.page_map[path] + (("#" + parsed.fragment) if parsed.fragment else "")
        stripped = path.rstrip("/") or "/"
        if stripped in self.page_map:
            return self.page_map[stripped] + (("#" + parsed.fragment) if parsed.fragment else "")
        return None

    def clean_head(self, soup):
        for link in soup.find_all("link"):
            rels = link.get("rel") or []
            rel = " ".join(rels) if isinstance(rels, list) else str(rels)
            href = link.get("href", "")
            if rel in DROP_LINK_RELS or "wp-json" in href or "xmlrpc" in href:
                link.decompose()
        for meta in soup.find_all("meta"):
            if meta.get("name") in DROP_META_NAMES:
                meta.decompose()
        # Emoji detection, the import map and every ES module are WordPress
        # runtime plumbing with no meaning in a standalone page.
        for script in soup.find_all("script"):
            text = script.string or ""
            src = script.get("src") or ""
            if script.get("type") in ("importmap", "module") or \
                    "wp-emoji" in text or "_wpemojiSettings" in text or \
                    "wp-emoji" in src or "wp-includes" in src:
                script.decompose()

        for style in soup.find_all("style"):
            if "wp-emoji" in (style.get("id") or "") or \
                    "img.wp-smiley" in (style.string or ""):
                style.decompose()
                continue
            # sourceURL comments leak the local dev address into the output.
            if style.string and "sourceURL" in style.string:
                style.string = re.sub(
                    r"\s*/\*#\s*sourceURL=[^*]*\*/", "", style.string
                )

        # Interactivity directives point at an API that is no longer present.
        for el in soup.find_all(True):
            for attr in [a for a in el.attrs if a.startswith("data-wp-")]:
                del el[attr]

    def export_page(self, path, filename):
        html = fetch(self.base + path).decode("utf-8", "replace")
        soup = BeautifulSoup(html, "html.parser")

        self.clean_head(soup)

        for link in soup.find_all("link", rel=lambda r: r and "stylesheet" in r):
            href = link.get("href")
            if not href or not self.is_internal(href):
                continue
            link["href"] = self.save_asset(href, "css")

        for img in soup.find_all("img"):
            src = img.get("src")
            if src and self.is_internal(src):
                img["src"] = self.save_asset(src, "img")
            if img.has_attr("srcset"):
                parts = []
                for chunk in img["srcset"].split(","):
                    chunk = chunk.strip()
                    if not chunk:
                        continue
                    bits = chunk.split()
                    if bits and self.is_internal(bits[0]):
                        bits[0] = self.save_asset(bits[0], "img")
                    parts.append(" ".join(bits))
                img["srcset"] = ", ".join(parts)

        for a in soup.find_all("a", href=True):
            href = a["href"]
            if href.startswith("#") or not self.is_internal(href):
                continue
            mapped = self.map_link(href)
            a["href"] = mapped if mapped else "#"

        body = soup.find("body")
        if body is not None:
            shim = soup.new_tag("script")
            shim.string = NAV_SHIM
            body.append(shim)

        os.makedirs(self.out, exist_ok=True)
        with open(os.path.join(self.out, filename), "w", encoding="utf-8") as fh:
            fh.write(soup.prettify())
        self.counts["pages"] += 1

    def run(self):
        if os.path.isdir(self.out):
            shutil.rmtree(self.out)
        for path, filename in self.site["pages"]:
            self.export_page(path, filename)
        for url, rel in list(self.assets.items()):
            if rel.startswith("assets/css/"):
                self.rewrite_css(rel)
        return self.counts


for site in SITES:
    counts = Exporter(site).run()
    print("%-22s pages=%d css=%d img=%d" % (
        site["name"], counts["pages"], counts["css"], counts["img"]))
    print("   -> %s" % site["out"])
