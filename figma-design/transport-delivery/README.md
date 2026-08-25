# SCENSOB — Transport & Delivery site

Source code for the design published at
<https://grow-mode-70103726.figma.site/>.

Plain HTML, CSS and a little JavaScript. No build step, no framework, no
package install.

```
index.html            Home             <- edit these
services.html         Services
about.html            About
catalog.html          Service Catalog
contact.html          Contact
assets/css/site.css
assets/js/site.js
assets/img/           Team photos and service images

standalone/           Same five pages, CSS, JS and images inlined
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
published site — every colour, font size, weight, letter-spacing, grid track,
gap and padding read from its computed styles — and rebuilt as hand-written
code. It is a reconstruction, not an export, which means the markup is clean and
meant to be edited.

Verified against the source at 1440px:

| | Figma | This build |
| --- | --- | --- |
| Hero grid | `732px 420px`, gap `48px` | identical |
| Hero padding | `56px 40px 80px` | identical |
| H1 | Playfair Display 68px / 500, line-height 71.4px | identical |
| Accent phrase | italic, weight 400, `#1A3A8F` | identical |
| Eyebrow | DM Mono 12px / 500, tracking `1.2px` | identical |
| Header | 65px tall, `rgba(238,242,250,.95)` | identical |
| Stat figure | Playfair Display 36px / 500 | identical |
| Service card | `#E4EAF6`, padding `32px 28px` | identical |
| Testimonial band | `#0F172A` on `#EEF2FA` | identical |
| CTA panel | `#1A3A8F`, padding `64px 40px`, H2 48px | identical |

## Design tokens

All defined as custom properties at the top of `site.css`, so a rebrand is a
handful of edits in one place.

| Token | Value | Used for |
| --- | --- | --- |
| `--background` | `#EEF2FA` | page ground |
| `--card` | `#E4EAF6` | cards, inputs, stats band |
| `--secondary` | `#D8E2F4` | icon tiles, segmented control |
| `--foreground` | `#0F172A` | body text, dark band |
| `--muted-foreground` | `#475880` | secondary text |
| `--accent` | `#1A3A8F` | actions, eyebrows, stars |
| `--border` | `rgba(15,23,42,.12)` | hairlines |
| `--radius` | `6px` | everything |
| `--container` | `1280px` | max width, `40px` gutter |

**Type.** Playfair Display for display and headings, Mulish for body and UI
(300 / 400 / 500), DM Mono for eyebrows, chips and figures. Loaded from Google
Fonts with system fallbacks.

**Icons** are inline Lucide SVGs — the same set the Figma design uses (`truck`,
`wrench`, `thermometer`, `zap`, `timer`, `rotate-ccw`, `phone-call`,
`badge-check`, `shield`, `package-2`, `star`, `arrow-right`, `check`). Inline
rather than a font or sprite, so there is nothing to load and each one inherits
`currentColor`.

**Photos.** Every photo the design uses is a real image pulled from Unsplash
(free to use, no attribution required) at the same photo IDs the live site
references — the About page's story image and 4 team headshots, and one image
per service on Services. They're saved locally in `assets/img/` rather than
hotlinked, so the site works offline and doesn't depend on Unsplash staying
up. Swap the files for real staff/fleet photos whenever they're available —
same filenames, same crop ratios (square for team, 4:5 for the story image,
16:9 for services) and nothing else needs to change.

## JavaScript

`assets/js/site.js` covers:

- the mobile navigation toggle
- single-select button groups (the hero's staff/candidate switch and the contact
  page's enquiry type)
- the catalog's sector filter, combined with a live text search across each
  role's title and description (matches the live site: type "GDP" and only the
  matching roles stay visible, sector counts update, and an empty result shows
  a "Clear filters" prompt)
- each of the 18 catalog roles expands independently on click to show its
  description, an "Enquire about this role" link, and its compliance-checks
  list — this and the search were both missing from an earlier pass and were
  added to match the source exactly

No dependencies. Nothing else on the page needs scripting.

## Responsive

Breakpoints at 1100px, 900px and 560px. The hero stacks, the header collapses to
a menu button, grids reduce to two columns and then one, and the catalog rows
reflow to stacked blocks. No page scrolls sideways at any width.

## Content

The copy — and the photos, see above — is the demo content from the Figma
design, reproduced exactly as requested so the pages match the comp. It should
be replaced with real content before the site goes anywhere public — the
names, photos, testimonials, office addresses, phone numbers, statistics and
salary bands were all generated or sourced by Figma and none of them are real.
