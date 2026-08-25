"""Build single-file copies of each page, with the CSS, JS and images inlined.

The multi-file source in this folder is the version to edit. These copies exist
purely so a page can be opened from anywhere and still render — including
straight out of a zip, where a browser only unpacks the one file you clicked and
relative asset paths break. Images are inlined as base64 data URIs for the same
reason.

Run after changing the CSS, JS or images:

    python build-standalone.py
"""

import base64
import io
import mimetypes
import os
import re

HERE = os.path.dirname(os.path.abspath(__file__))
OUT = os.path.join(HERE, "standalone")

PAGES = ["index.html", "services.html", "portfolio.html", "about.html", "quote.html"]

CSS_LINK = re.compile(r'[ \t]*<link rel="stylesheet" href="assets/css/site\.css">\n?')
JS_TAG = re.compile(r'[ \t]*<script src="assets/js/site\.js"></script>\n?')
IMG_SRC = re.compile(r'src="assets/img/([^"]+)"')

NOTE = (
    "<!--\n"
    "  Single-file build. The CSS, JS and images below are inlined copies of\n"
    "  assets/css/site.css, assets/js/site.js and assets/img/*.\n"
    "\n"
    "  Edit the multi-file source in the parent folder, not this file, then\n"
    "  re-run build-standalone.py.\n"
    "-->\n"
)


def read(*parts):
    with io.open(os.path.join(HERE, *parts), encoding="utf-8") as fh:
        return fh.read()


def image_data_uri(name):
    path = os.path.join(HERE, "assets", "img", name)
    mime = mimetypes.guess_type(path)[0] or "application/octet-stream"
    with open(path, "rb") as fh:
        encoded = base64.b64encode(fh.read()).decode("ascii")
    return "data:%s;base64,%s" % (mime, encoded)


def inline_images(html):
    return IMG_SRC.sub(lambda m: 'src="%s"' % image_data_uri(m.group(1)), html)


def main():
    css = read("assets", "css", "site.css")
    js = read("assets", "js", "site.js")

    if not os.path.isdir(OUT):
        os.makedirs(OUT)

    for page in PAGES:
        html = read(page)

        if not CSS_LINK.search(html):
            raise SystemExit("no stylesheet link found in %s" % page)
        if not JS_TAG.search(html):
            raise SystemExit("no script tag found in %s" % page)

        html = CSS_LINK.sub("<style>\n%s\n</style>\n" % css, html)
        html = JS_TAG.sub("<script>\n%s\n</script>\n" % js, html)
        html = inline_images(html)
        html = html.replace("<!doctype html>\n", "<!doctype html>\n" + NOTE, 1)

        # These pages sit one level down, so page-to-page links still work as-is.
        with io.open(os.path.join(OUT, page), "w", encoding="utf-8") as fh:
            fh.write(html)

        print("%-16s %8d bytes" % (page, len(html.encode("utf-8"))))

    print("\nwritten to %s" % OUT)


if __name__ == "__main__":
    main()
