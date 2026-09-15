# SCENSOB Group — parent site

Source code for the design published at
<https://goat-short-45863997.figma.site>.

This is the **group-level site** that sits above the two division sites. The
same Figma project also holds the two divisions, at `/transport` and `/it` —
those are the sites in `../transport-delivery/` and `../it/`, already built.

Plain HTML, CSS and a little JavaScript. No build step, no framework, no
package install.

```
index.html            Home             <- edit these
about.html            About
portfolio.html        Portfolio
contact.html          Contact
assets/css/site.css
assets/js/site.js
assets/img/           Division, team and case-study photos

submit.php            Contact form endpoint (see Contact form, below)
config.sample.php     Database credentials template
database/schema.sql   Creates the group_enquiries table

standalone/           Same four pages, CSS, JS and images inlined
build-standalone.py   Regenerates standalone/ from the files above
```

## Which folder to open

**To view it:** open anything in `standalone/`. Those are single files with the
CSS, JS and images all built in (images as base64), so they render correctly
wherever they are, opened however they're opened.

**To edit it:** work in the files at the top level, where the CSS, JS and images
live in one place each. Run `python build-standalone.py` afterwards to refresh
the standalone copies.

> **Extract the zip before opening anything.** Windows lets you browse a zip as
> though it were a folder, but double-clicking a file inside one unpacks only
> that single file to a temporary directory. The stylesheet is left behind and
> the page renders as unstyled HTML. Right-click the zip, choose *Extract All*,
> then open from the extracted folder. The `standalone/` copies are immune to
> this, which is why they exist.

## How this was made

Figma Dev Mode wasn't available, so the design was measured directly from the
published site — every colour, font size, weight, line-height, grid track, gap
and padding read from its computed styles — and rebuilt as hand-written code.
It is a reconstruction, not an export, which means the markup is clean and
meant to be edited.

Verified against the source at 1440px. **The home page has since been replaced
on request and no longer follows the comp at all** — see "The home page" below.
The other three pages are unchanged and still match:

| | Figma | This build |
| --- | --- | --- |
| Header | 65px, `rgba(247,248,252,.96)` | identical |
| About hero | 374px tall, H1 60px / lh 75px | identical |
| About page height | 3893px | 3929px |
| Portfolio grid | 2 × 586px, gap 28px | identical |
| Portfolio page height | 3400px | 3433px |
| Contact form column | 784px, fields 384px | identical |
| Contact page height | 1685px | 1749px |

## The home page

The body is the globe animation with all eight divisions on a ring around it,
and the same eight in the sidebar card on the left. Both lists are in the same
order and point at the same places.

It follows the approved landing-page design: the artwork's own blue, and white
discs rimmed with the group's colour wheel. A navy version was tried to match
the other pages and set aside.

### The globe video

`assets/video/globe.mp4` — 1280x742, one 10-second loop, 880 KB.

Cut from the supplied master (`scensob LLC website.mp4`, 1920x1080, 29.8 MB) in
three steps:

1. **Cropped** 58px off the right edge. The master carries a white bar down that
   edge on every frame.
2. **Trimmed** to 10.033s. The master runs 15s, but frame 304 is identical to
   frame 0 — it is one cycle plus a partial repeat.
3. **Re-encoded** to H.264 at CRF 30, scaled to 1280 wide. The master's
   16.6 Mbps is far beyond what flat artwork needs.

97% smaller than the master, 77% smaller than the original GIF. WebM was tried
and came out *larger* here, so it was dropped rather than shipped for no gain.

To regenerate from a new master: `crop=1862:1080:0:0,scale=1280:-2` with
`-t 10.033`.

**The video covers the stage rather than fitting inside it.** Fitting it inside
letterboxes the artwork and leaves the globe small; covering fills the space and
the stage clips the overflow. It also removes the edge problem — with no stage
background showing beside the video, there is no join to hide.

**Below 560px it goes back to fitting inside.** Covering is what makes the globe
big on a wide screen, but on a narrow one the frame's width comes from the
stage's *height*, so it blows out sideways and the ring — which is sized off the
frame — ends up wider than the stage and loses its outer nodes.

Because fitting inside leaves stage background showing again, the video's edges
are feathered at that size. The artwork's own edge pixels are not one flat
colour — they run from `#0569C9` to `#1465B7` — so no CSS value matches them
exactly and the join otherwise reads as a rectangle.

### The division ring

Eight nodes, each a white disc with the group's colour wheel as its rim and the
division name inside it.

**The ring positions itself.** Each node carries an index (`--i`) and the overlay
carries the total (`--count`); the angle comes from those two via `sin()`/`cos()`.
Nothing is hand-placed.

It is centred on **the globe, not the video frame** — the globe sits at 48.3%
across and 51% down of the artwork, measured off the video itself. The radius is
38% of the frame height: the globe's centre sits at 51%, so the radius plus half
a disc has to stay under 100% or the bottom node is clipped.

Below 560px the names are swapped for each division's icon — they cannot be read
round a globe that small — the discs grow past the 44px touch target, and the
stage moves above the card so the globe is not pushed below the fold by an
eight-row list.

> **The stage needs a real height, not `min-height`.** It is a size container,
> and `min-height` leaves its block size indefinite, which makes every `cqh`
> inside it resolve to zero — collapsing the frame, the video and the whole ring
> to nothing. On desktop it gets a definite height from the grid row; on mobile
> it is set explicitly.

### Adding a division

Two places:

1. **The ring** — one more `.division-node` in `index.html` with the next `--i`,
   and bump `--count` on `.globe-overlay`.
2. **The sidebar** — one more `.division-nav-item`, plus a
   `.division-nav-mark.mark-<name>` colour rule.
3. **The header** — one more link in `.divisions-menu` *and* in `#mobile-nav`,
   on all four pages, plus a `.swatch-<name>` colour rule.

Both re-space themselves. Note the colour rules are scoped to
`.division-nav-mark`: the ring nodes carry the same classes, and an unscoped
rule paints a coloured box behind each of their circles.

Keep division names short — they sit inside the discs. "IT" rather than
"Technologies", and "Home Health" as two words so it can wrap; "Homehealth" is a
single unbreakable word and overflows.

## Design tokens

All defined as custom properties at the top of `site.css`, so a rebrand is a
handful of edits in one place.

| Token | Value | Used for |
| --- | --- | --- |
| `--navy` | `#0A1628` | hero, footer, dark bands |
| `--navy-raised` | `#0F1F3D` | stat band |
| `--gold` | `#C9A84C` | group accent — eyebrows, CTAs, links |
| `--transport` | `#1A3A8F` | Transport division cues |
| `--it` | `#2563EB` | IT division cues |
| `--background` | `#F7F8FC` | page ground |
| `--tint` | `#F0F3FA` | alternating light band |
| `--foreground` | `#0F172A` | body text |
| `--muted` | `#6B7FA3` | secondary text |
| `--radius` | `6px` | everything |
| `--container` | `1280px` | max width, `40px` gutter |

**The gold is what separates the parent brand from its divisions.** Each
division keeps its own blue, and those blues are reused here wherever the page
refers to a specific division — the hero buttons, the division cards, and the
case-study tags on Portfolio. That is deliberate in the source design.

**Type.** Playfair Display for display and headings, Mulish for body and UI
(300 / 400 / 500 / 600), DM Mono for eyebrows and figures. Loaded from Google
Fonts with system fallbacks.

**Icons** are inline Lucide SVGs — the same set the Figma design uses (`truck`,
`server`, `shield`, `target`, `users`, `award`, `phone`, `mail`, `arrow-right`,
`chevron-down`, `check`). Inline rather than a font or sprite, so there is
nothing to load and each one inherits `currentColor`.

**Photos.** Every photo is a real image pulled from Unsplash (free to use, no
attribution required) at the same photo IDs the live site references — the two
division cards, the group offices shot, 5 leadership headshots, and one image
per case study. They're saved locally in `assets/img/` rather than hotlinked,
so the site works offline and doesn't depend on Unsplash staying up. Swap the
files for real photos whenever they're available — same filenames, same crop
ratios, and nothing else needs to change.

## JavaScript

`assets/js/site.js` covers:

- the mobile navigation toggle
- the Portfolio division filter (All Work / Transport & Delivery / IT Division),
  which updates the visible case studies and the running count, and shows an
  empty-state message if a division has none
- the Contact page's enquiry-type picker, which writes the chosen answer into a
  hidden field so it submits with the rest of the form
- the Contact form submit handler

The **Our Divisions** dropdown in the header is deliberately **not** scripted —
it is CSS-only, opening on `:hover` *and* `:focus-within` so it works for
keyboard users too. The published design opens it on hover alone.

No dependencies. Nothing else on the page needs scripting.

## Contact form

The form is connected to MySQL. `assets/js/site.js` posts to `submit.php`,
which validates the submission and inserts a row into the `group_enquiries`
table.

```
submit.php            Receives the form, validates, inserts
config.sample.php     Copy to config.php and fill in credentials
config.php            Real credentials — gitignored, never committed
database/schema.sql   Creates the group_enquiries table
SETUP.md              Step-by-step setup for whoever deploys the site
```

**This site uses its own database, separate from the two divisions.** The group
form asks different questions from the Transport and IT forms, so it gets its
own table rather than sharing theirs. The three table names don't clash, so
they can share one database if that's simpler to host.

Required: name, email, message — the three fields marked `*` on the page.
Optional: enquiry type, company, phone, stored as `NULL` when blank. The hidden
honeypot field silently discards bot submissions without touching the database.

Full deployment instructions, including what to do when it doesn't work, are in
**`SETUP.md`**.

## Responsive

Breakpoints at 1100px, 900px and 560px. The header collapses to a menu button,
the portfolio grid and contact layout drop to one column, and on the home page
the sidebar moves from beside the globe to above it. No page scrolls sideways at
any width.

## Content

The copy — and the photos, see above — is the demo content from the Figma
design, reproduced exactly as requested so the pages match the comp. It should
be replaced with real content before the site goes anywhere public — the names,
photos, case studies, office addresses, phone numbers and statistics were all
generated or sourced by Figma and none of them are real.
