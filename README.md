# SCENSOB Web Project

Design and build of the SCENSOB Group site plus two divisional sites (IT,
Transport & Delivery), unified under one shared design system.

Internship project — team: Karol Harasim, Daniel Boateng. Mentor: Daniel Dankwa.

## The sites

Live work lives in **`figma-design/`**. Each of the three sites was rebuilt from
its published Figma design as hand-written HTML, CSS and JavaScript — no
framework, no build step, no package install.

| | Pages | Form |
| --- | --- | --- |
| **`figma-design/group/`** | Home, About, Portfolio, Contact | `group_enquiries` |
| **`figma-design/transport-delivery/`** | Home, Services, About, Catalog, Contact | `transport_enquiries` |
| **`figma-design/it/`** | Home, Services, About, Portfolio, Quote | `it_quote_requests` |

Each site folder is self-contained and has the same shape:

```
*.html                Pages            <- edit these
assets/css/site.css
assets/js/site.js
assets/img/

submit.php            Contact form endpoint
config.sample.php     Database credentials template
config.php            Real credentials — gitignored, never committed
database/schema.sql   Creates that site's table
SETUP.md              Deployment steps for whoever hosts it

standalone/           Same pages with CSS, JS and images inlined
build-standalone.py   Regenerates standalone/
```

**To view a site:** open anything in its `standalone/` folder — single files
that render correctly wherever they're opened, with no web server.

**To edit one:** work in the top-level files, then re-run
`python build-standalone.py`.

## Contact forms

All three forms post to their own `submit.php`, which validates the submission
and inserts a row into MySQL via a prepared statement. Each site writes to its
own table because the three forms ask different questions; the table names
don't clash, so one database can serve all three if the hosting only provides
one.

Every form carries a hidden honeypot field. Real visitors never see it, so
anything that fills it in is a bot — those submissions are silently discarded
and never reach the database.

`config.php` holds live credentials and is gitignored. Only `config.sample.php`
is committed, and only it should ever be shared. Per-site setup instructions,
including troubleshooting, are in each folder's `SETUP.md`.

## How the sites were built

Figma Dev Mode wasn't available, so each design was measured directly from the
published site — every colour, font size, weight, line-height, grid track, gap
and padding read from its computed styles — and rebuilt by hand. These are
reconstructions, not exports, which is why the markup is clean and meant to be
edited.

Residual differences against the Figma originals are font metrics: Figma ships
its own font files, and these builds load the same families from Google Fonts.
Closing that last gap would mean self-hosting the font files.

## Also in this repo

- **`design-system/`** — the shared SCENSOB logo (`scensob-logo.svg`) and a
  browser preview of it on light and dark backgrounds.
- **`wireframes/`** — the original HTML mockups, one folder per site. Superseded
  by `figma-design/`, kept as the record of the design phase.

An earlier version of this project was built as a WordPress block theme, with a
script that exported it to static HTML. That approach was dropped in favour of
the hand-written sites above. It isn't in the working tree any more, but it's
still in the git history if it's ever needed — see the commits up to `20d860d`.

## Content

The copy, photos, names, case studies, addresses and statistics across all three
sites are demo content from the Figma designs, reproduced so the pages match the
comps. **None of it is real**, and it needs replacing before any of these sites
goes public. Photos are Unsplash stand-ins saved locally, so swapping in real
photography means replacing the files in `assets/img/` — same filenames, same
crop ratios, nothing else changes.
