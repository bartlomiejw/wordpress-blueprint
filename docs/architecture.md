# Architecture and vocabulary

This page defines the terms used across the repository and the boundaries
between its parts. Decisions with real trade-offs are recorded separately as
[ADRs](adr/).

## Terms

| Term | Meaning |
| --- | --- |
| **Blueprint** | This repository: the template new site repositories are created from. It is never deployed as a site itself. |
| **Site Project** | A concrete WordPress site repository created from the Blueprint. It contains one Custom Theme, Site Core and any site-specific plugins. |
| **Custom Theme** | The block theme in `wp-content/themes/custom-theme/`: `theme.json`, HTML templates, template parts and custom blocks. It owns presentation only. |
| **Site Core** | The mu-plugin in `wp-content/mu-plugins/site-core/`. It owns the data layer: post types, taxonomies, meta, integrations. It contains no presentation logic. |
| **Custom Block** | A block built with `@wordpress/scripts`. Where it lives depends on the rule below. |

## Boundaries

**Data in Site Core, presentation in the theme.** Content models must survive a
redesign, so they are registered by an always-on mu-plugin rather than by the
theme. A theme switch can never make posts disappear from the admin.

**Where a block lives.** A block that is tied to the site's look (layout,
design components) belongs in the Custom Theme under `blocks/`. A block that
provides standalone functionality (forms, paywalls, data integrations) or must
keep working after a theme switch belongs in Site Core or a dedicated plugin.

**Styling.** Design tokens live in SCSS (`assets/css/abstracts/`), compiled by
`@wordpress/scripts`, with BEM-named component files. `theme.json` is used
deliberately narrowly: to set layout widths and to switch off editor controls
that would let editors drift from the design system. It is not the token source.

**Core is a dependency.** WordPress core is installed by Composer into `wp/`
and never committed. `wp-content/` sits next to it and is the only place
project code lives. See [ADR 0002](adr/0002-classic-composer-structure.md).

## Request flow

```
index.php ──> wp/wp-blog-header.php ──> wp-config.php (root, reads .env)
                                           │
                                           ├─ WP_CONTENT_DIR = ./wp-content
                                           └─ ABSPATH        = ./wp/
wp-content/mu-plugins/plugin-loader.php ──> site-core/site-core.php   (data layer)
wp-content/themes/custom-theme/functions.php                          (presentation)
```

## Environments

| Environment | Tool | Purpose |
| --- | --- | --- |
| Local development | DDEV (`.ddev/`) | Production-like stack: nginx, MySQL, Mailpit, Xdebug. See [ADR 0001](adr/0001-ddev-over-wp-env.md). |
| Disposable test site | wp-env (`.wp-env.json`) | Throwaway WordPress for Playwright, locally and in CI. |
| Staging, production | Any host with SSH + rsync | Deployed by `.github/workflows/deploy.yml`. |

Per-environment configuration comes from environment variables, loaded from
`.env` by `wp-config.php`. `.env.example` documents every variable.
