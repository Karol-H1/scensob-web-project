# SCENSOB WordPress theme

A single block theme (`themes/scensob`) shared by every SCENSOB site. Each
division gets its own **style variation** rather than its own theme, so adding a
fourth division later is a JSON file, not a new codebase.

## Why one theme

| Division | Style variation | Accent |
| --- | --- | --- |
| Global (default) | built into `theme.json` | `#B8823F` bronze |
| IT | `styles/it.json` | `#2E8B78` teal-green |
| Transport & Delivery | `styles/transport-delivery.json` | `#2F5FAE` signal blue |

Everything else — type scale, spacing, cards, header, footer, logo — is shared.
Change it once, all sites inherit it.

## Structure

```
themes/scensob/
  style.css          Theme header + the few rules blocks can't express
  theme.json         Design tokens: colour, type, spacing, block styles
  functions.php      Theme supports, stylesheet, logo and photo helpers
  assets/logo.svg    Shared SCENSOB mark (inlined, not uploaded)
  parts/             header.html, footer.html
  templates/         front-page, page, page-wide, index, 404
  patterns/          logo, hero, divisions-grid, services-grid,
                     products-grid, gallery-grid, contact-panel, cta-band
  styles/            Per-division colour variations

setup/               Provisioning scripts run once per site
  common.php         Shared helpers: photos, style variation, pages
  global.php         Global hub site
  it.php             IT division site
```

Page layouts live in **page content**, not in templates — each page is built
from pattern references, so editors can rearrange a page in the Site Editor
without touching code.

## Running a site locally

Requires **Node 20+**. The Playground CLI depends on a native module that only
ships prebuilt binaries for Node 20 and up.

```bash
npx @wp-playground/cli@latest server --port=9400 --php=8.2 --mount-dir "./wordpress/themes/scensob" "/wordpress/wp-content/themes/scensob" --mount-dir "./wordpress/setup" "/setup" --mount-dir "./assets/photos" "/photos" --blueprint "./wordpress/playground-blueprint.json"
```

Swap `playground-blueprint.json` for `it-blueprint.json` (and a different
`--port`) to run the IT site. Both can run at once.

## Per-site setup on real hosting

1. Upload and activate the `scensob` theme.
2. **Settings → General → Tagline** — set to the division name (`Global`, `IT`,
   `Transport & Delivery`). This prints under the logo.
3. **Appearance → Editor → Styles → Browse styles** — pick the division's
   variation.
4. Create the pages and set the homepage under **Settings → Reading**.

No page builder plugin is required.

## Notes for whoever picks this up

Two things about provisioning WordPress programmatically that cost real time,
recorded so nobody rediscovers them:

- **Block markup is HTML comments, and KSES strips comments** when there's no
  authenticated user. Page content saves silently empty without
  `kses_remove_filters()`.
- **A global styles post is ignored unless** its JSON contains
  `isGlobalStylesUserThemeJSON: true` *and* the post carries a `wp_theme` term
  matching the stylesheet. Core sets the term via `tax_input`, which
  `wp_insert_post` drops when no user is logged in. Miss either and the
  variation saves correctly but has no effect.

## Status

Structure and design system are built and verified running. All copy is
deliberately bracketed placeholder text pending sign-off:

- IT service list — blocked on the IT HOD. Note the brief's IT section
  describes gardening and grounds upkeep, which contradicts "IT division";
  that needs resolving before any real copy is written.
- Global positioning and homepage copy — blocked on the CEO.
- Transport & Delivery scope — blocked on confirming whether the catalogue is
  browse-only or transactional. Transactional needs WooCommerce plus a paid
  bookings extension, which breaks the zero-budget constraint.
- Hosting — WordPress needs PHP and MySQL, so GitHub Pages and Netlify can't
  serve it. Worth finding out what the existing SCENSOB sites run on.
