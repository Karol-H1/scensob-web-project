# SCENSOB — IT site

Source code for the design published at
<https://goat-short-45863997.figma.site/it>.

Plain HTML, CSS and a little JavaScript. No build step, no framework, no
package install.

```
index.html            Home             <- edit these
services.html         Services
portfolio.html         Portfolio
about.html             About
quote.html             Get a Quote
assets/css/site.css
assets/js/site.js
assets/img/            Team photos, service and case-study images

submit.php             Quote wizard handler — writes to MySQL
config.sample.php      Copy to config.php and fill in DB credentials
database/schema.sql    Run once to create the it_quote_requests table

standalone/           Same five pages, CSS, JS and images inlined
build-standalone.py   Regenerates standalone/ from the files above
```

## Which folder to open

**To view it:** open anything in `standalone/`. Those are single files with the
CSS, JS and images all built in (images as base64), so they render correctly
wherever they are, opened however they're opened. The quote wizard on these
copies can't actually submit anywhere, though — see **Database** below.

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
published site — every colour, font size, weight, grid track, gap and padding
read from its computed styles — and rebuilt as hand-written code. It is a
reconstruction, not an export, which means the markup is clean and meant to be
edited.

This is a different design system to the Transport & Delivery site (also in
this repo, under `../transport-delivery/`): a dark navy hero and footer, a
Tailwind-blue accent, and Inter as the primary body font instead of Mulish.
Only the IT site was rebuilt here — the design also contains a Transport &
Delivery variant and a top-level "Group" site, neither of which are part of
this folder.

Verified against the source at 1440px:

| | Figma | This build |
| --- | --- | --- |
| Hero grid | `1fr 420px`, gap `64px` | identical |
| Hero padding | `80px` top, `96px` bottom | identical |
| H1 (home) | Playfair Display 56px / 500 | identical |
| H1 (inner pages) | Playfair Display 48px / 500, line-height 60px | identical |
| Header | 65px tall, `rgba(240,245,255,.96)`, blur(8px) | identical |
| Accent | `#2563EB` | identical |
| Quote card | `rgba(15,23,42,.8)`, border `rgba(37,99,235,.2)` | identical |
| Service card | white, border `rgba(15,23,42,.1)`, padding `28px 24px` | identical |
| Stat band | `#1E293B` | identical |
| Eyebrow badge | DM Mono 12px, `#93BBFB`, border `rgba(37,99,235,.4)` | identical |

## Design tokens

All defined as custom properties at the top of `site.css`, so a rebrand is a
handful of edits in one place.

| Token | Value | Used for |
| --- | --- | --- |
| `--background` | `#EEF2FA` | page ground |
| `--foreground` | `#0F172A` | hero, footer, testimonial band, body text |
| `--foreground-alt` | `#1E293B` | stat band |
| `--accent` | `#2563EB` | actions, links, icons, CTA panel |
| `--accent-tint` | `#EEF4FF` | icon tiles, selected states |
| `--card` | `#FFFFFF` | service cards, quote wizard, portfolio cards |
| `--muted-foreground` | `#6B7FA3` | secondary text |
| `--border` | `rgba(15,23,42,.1)` | hairlines on light backgrounds |
| `--radius` | `6px` | everything |
| `--container` | `1280px` | max width, `40px` gutter |

**Type.** Playfair Display for display and headings, Inter for body and UI
(300 / 400 / 500 / 600), DM Mono for eyebrows, badges and year tags. Loaded
from Google Fonts with system fallbacks. Mulish is kept as a fallback in the
font stack because the design's own CSS lists it that way, but Inter is what
actually renders.

**Icons** are inline Lucide SVGs — the same set the Figma design uses (`users`,
`server`, `cloud`, `shield`, `code-xml`, plus a check-circle for list items and
a chevron for expand/collapse controls). Inline rather than a font or sprite,
so there is nothing to load and each one inherits `currentColor`.

**Photos.** Every photo the design uses is a real image pulled from Unsplash
(free to use, no attribution required) at the same photo IDs the live site
references — 4 team headshots on About, one image per service on Services, and
one per case study on Portfolio (a few IDs repeat across pages, matching a
duplication in the source design itself). They're saved locally in
`assets/img/` rather than hotlinked, so the site works offline and doesn't
depend on Unsplash staying up. Swap the files for real staff/project photos
whenever they're available — same filenames, same crop ratios (square for
team, 4:3 for services and case studies) and nothing else needs to change.

## JavaScript

`assets/js/site.js` covers:

- the mobile navigation toggle
- the home hero's quick-enquiry form, which hands its selected service off to
  the quote wizard (`quote.html?service=...`) — the same query string that
  each service page's "Get a quote for &hellip;" button uses
- the portfolio's category filter and each project card's independent
  Read case study / Show less toggle (cards expand independently, not as an
  accordion — verified against the live site)
- the four-step quote wizard: multi-select services, single-select
  timeline/size/budget groups, a free-text brief, and a contact form that
  renders a running brief summary and a success panel on submit

No dependencies. Nothing else on the page needs scripting.

## Database

Step 4 of the quote wizard now submits for real: on "Submit quote request" it
POSTs the selected services plus timeline/size/budget/brief/contact fields to
`submit.php`, which validates them server-side and inserts a row into an
`it_quote_requests` MySQL table. The success panel only shows once that
insert has actually happened — a failed request shows an inline error instead.

**One-time setup**, on whatever server will actually run this (a local PHP
install for testing, or the real host once Daniel's set up):

1. Create a database and run `database/schema.sql` against it to create the
   `it_quote_requests` table:
   ```
   mysql -u root -p -e "CREATE DATABASE scensob_it CHARACTER SET utf8mb4;"
   mysql -u root -p scensob_it < database/schema.sql
   ```
2. Copy `config.sample.php` to `config.php` and fill in the real host/database/
   username/password. `config.php` is gitignored on purpose — it holds real
   credentials and should never be committed or handed off inside a zip.
3. Serve the folder through PHP (not just opened from disk) — e.g.
   `php -S localhost:8000` from this folder for local testing, or upload it to
   real PHP+MySQL hosting for production.

**Why `standalone/` can't submit anything:** those pages exist so someone can
open a single HTML file straight from a zip with no server at all. A working
form needs a real PHP process to receive the POST and talk to MySQL, so it's
a structural limit, not a bug — the single-file trick and a live backend are
mutually exclusive. Use the top-level files (served through PHP) for anything
that needs the wizard to actually work.

**A honeypot field** (`website`, positioned off-screen) sits in the step-4
form — real visitors never see or fill it in, but simple spam bots often do.
Any submission with it filled in is silently discarded server-side without
touching the database.

## Responsive

Breakpoints at 1100px, 900px and 560px. The hero stacks, the header collapses
to a menu button, grids reduce column count, and the quote wizard's step
caption hides on the smallest screens to make room for the step dots. No page
scrolls sideways at any width.

## Content

The copy — and the photos, see above — is the demo content from the Figma
design, reproduced exactly as requested so the pages match the comp. It should
be replaced with real content before the site goes anywhere public — the
client names, testimonials, case studies, team bios and photos, office
addresses, phone numbers and statistics were all generated or sourced by
Figma and none of them are real.
