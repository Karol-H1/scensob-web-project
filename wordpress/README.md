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

Switch variation per site in **Appearance → Editor → Styles → Browse styles**.

## Structure

```
themes/scensob/
  style.css          Theme header + the few rules blocks can't express
  theme.json         Design tokens: colour, type, spacing, block styles
  functions.php      Theme supports, stylesheet, logo helper
  assets/logo.svg    Shared SCENSOB mark (inlined, not uploaded)
  parts/             header.html, footer.html
  templates/         front-page, page, page-wide, index, 404
  patterns/          logo, hero, divisions-grid
  styles/            Per-division colour variations
```

## Per-site setup

1. Upload/activate the `scensob` theme.
2. **Settings → General → Tagline** — set to the division name (`Global`, `IT`,
   `Transport & Delivery`). This is what prints under the logo.
3. **Appearance → Editor → Styles** — pick the division's style variation.
4. Create the pages, then set the homepage under **Settings → Reading**.

No page builder plugin is required — editors change layout and text in the
built-in Site Editor.

## Previewing without installing anything

`themes/scensob` can be dragged into <https://playground.wordpress.net> as a zip
to see it running in the browser. Build the zip with:

```powershell
Compress-Archive -Path wordpress\themes\scensob -DestinationPath scensob-theme.zip -Force
```

## Local development

`playground-blueprint.json` boots WordPress with the theme active and the Global
site's pages created:

```bash
npx @wp-playground/cli@latest server --mount=./wordpress/themes/scensob:/wordpress/wp-content/themes/scensob --blueprint=./wordpress/playground-blueprint.json
```

**Requires Node 20 or newer.** The CLI depends on a native module that only ships
prebuilt binaries for Node 20+; on Node 18 the install fails trying to compile it.

## Status

Structure and design system are built. All copy is deliberately bracketed
placeholder text pending sign-off:

- IT service list — blocked on the IT HOD
- Global positioning and homepage copy — blocked on the CEO
- Transport & Delivery scope — blocked on confirming whether the catalogue is
  browse-only or transactional (the latter needs WooCommerce plus a paid
  bookings extension)
