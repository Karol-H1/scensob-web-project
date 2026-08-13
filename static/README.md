# Standalone site source — IT and Transport & Delivery

Full-fidelity HTML and CSS for both division sites. No build step, no server, no
dependencies: open `index.html` in a browser and it works.

```
it/                       IT Division
  index.html              Home
  services.html
  products.html
  gallery.html
  contact.html
  assets/css/style.css
  assets/img/

transport-delivery/       Transport & Delivery Division
  index.html              Home
  services.html
  catalog.html            Service catalog
  about.html
  contact.html
  assets/css/style.css
  assets/img/
```

## What this is

These pages are exported from the WordPress build in `../wordpress`, which is
where the design system actually lives. That means the two stay identical by
construction rather than by hand — the design is defined once, in
`wordpress/themes/scensob/theme.json`, and both outputs come from it.

Regenerate after changing the theme:

```bash
python export_static.py
```

## Design system

Shared across both sites, and with the Global hub:

| Token | Value |
| --- | --- |
| Page / surface | `#FFFFFF` |
| Body text | `#33302B` |
| Secondary text | `#7A7168` |
| Rules and borders | `#E7E2DA`, `#D3CCC0` |
| IT accent | `#2E8B78` |
| Transport & Delivery accent | `#2F5FAE` |
| Global accent | `#B8823F` |

Headings are set in a serif face, body copy in the system sans stack. No web
fonts are loaded, so nothing depends on an external host and there is no
licensing question.

Layout is a 1120px content column with a 1240px wide measure. Spacing follows a
fixed scale (10 / 20 / 28 / 40 / 64 / 88px) rather than ad-hoc values.

## Notes on the exported markup

- **Styles are inlined.** WordPress emits per-block CSS inline, so each page
  carries what it needs. `assets/css/style.css` holds the theme's own rules.
- **The responsive menu is driven by a small script at the end of each page.**
  WordPress normally powers it through its Interactivity API, which doesn't
  exist outside WordPress — and ES modules don't load over `file://` anyway.
  It's about twenty lines of plain JavaScript, no library.
- **Class names beginning `wp-block-` are WordPress's layout primitives.** They
  are carried through deliberately: they're what the inlined CSS targets.

## Content status

Every piece of copy in these pages is bracketed placeholder text, agreed with
the mentor. The structure, layout, type, colour, spacing and imagery are final;
the words are not. Photography is licensed stock standing in until the divisions
supply their own.

Outstanding content is itemised separately in the project's content inventory.
