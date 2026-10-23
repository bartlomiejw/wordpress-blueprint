# 2. Classic WordPress layout with Composer-managed core, not Bedrock

- Status: Accepted
- Date: 2026-06-12

## Context

A site repository needs reproducible WordPress core and plugin versions
without committing third-party code. The common options are Roots Bedrock,
a fully classic install with core committed, or a classic layout with core
installed by Composer (the approach of 10up's wp-scaffold).

## Decision

Keep the WordPress-native directory layout. The root `composer.json` installs
core into `wp/` (`roots/wordpress`) and third-party plugins from WPackagist.
A version-controlled `wp-content/` next to it holds the Custom Theme, plugins
and mu-plugins. `wp-config.php` lives in the root and reads configuration from
environment variables.

Structure follows 10up conventions, with WordPress VIP practices layered on top
(functionality in mu-plugins, presentation in the theme).

## Consequences

- Any WordPress developer recognises `wp-content/` immediately.
- Core and plugin updates are a one-line `composer.lock` change, reviewed and
  tested like any other change, and proposed automatically by Dependabot.
- The web root is the repository root, so hosts without custom document roots
  still work.

## Considered options

- **Roots Bedrock.** Popular, but third-party where WordPress-native
  conventions are preferred, its `web/app/` layout is unfamiliar to many
  WordPress developers, and it requires a custom web root.
- **Commit core.** Simple, but core updates pollute history and the exact
  installed version is no longer declared anywhere.
