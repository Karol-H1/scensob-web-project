# Transport & Delivery — Figma design, built in HTML/CSS

A hand-built HTML and CSS implementation of the published Figma design at
<https://grow-mode-70103726.figma.site/>.

No build step, no framework, no dependencies. Open `index.html` in a browser.

```
index.html        Home
services.html     Services
about.html        About
catalog.html      Service Catalog
contact.html      Contact
assets/css/site.css
assets/js/site.js
```

## Design tokens

Taken from the Figma site's computed styles, so this is a like-for-like rebuild
rather than an interpretation.

| Role | Value |
| --- | --- |
| Page ground | `#EEF2FA` |
| Card surface | `#E4EAF6` |
| Deeper panel | `#D8E2F4` |
| Ink | `#0F172A` |
| Primary action | `#1A3A8F` |
| Hairline | `rgba(15, 23, 42, 0.12)` |
| Corner radius | `6px` |
| Content width | `1200px` |

**Type.** Playfair Display for display and section headings (weight 500), Mulish
for body and UI, DM Mono for the uppercase eyebrow labels, tags and figures.
Loaded from Google Fonts, with system fallbacks if offline.

Verified against the source: page ground, ink, primary action and the 68px
display size all match exactly.

## Interaction

`assets/js/site.js` is about sixty lines of plain JavaScript covering the three
things the design needs:

- the mobile navigation toggle
- the catalog's sector filter (All / Hire / Courier / Pharma)
- the enquiry-type selector on the contact page

## A note on the content

The Figma draft was generated, and it invented a large amount of specific
detail: a named founder and four named staff, three client testimonials
attributed to named people at named companies, four office addresses with UK
phone numbers, accreditation claims, headline statistics and salary bands.

None of that is verified, so **none of it is reproduced here.** Every one of
those is a bracketed placeholder sitting in the same slot, formatted to show
what belongs there — `[ £00,000–£00,000 ]`, `[ Name ]`, `[ Phone ]`. The design
reads exactly as designed; the fiction is gone.

Kept verbatim, because it is descriptive rather than a claim of fact: section
headings and body copy, the three sector names (which come from the project
brief), the compliance checks each service performs, the SLA tiers, and standard
industry role titles in the catalog.

This matters beyond tidiness. A fabricated testimonial or an invented phone
number is exactly the kind of thing that survives quietly into a live site.

## Relationship to the rest of the repo

This is a standalone implementation of the Figma visual design.

It is separate from `../../wordpress`, which is a WordPress block theme built on
the SCENSOB group design system shared with the Global and IT sites, and from
`../../static`, which is an export of that. The two look different because they
are different designs — this one follows Figma, that one follows the group
system.
