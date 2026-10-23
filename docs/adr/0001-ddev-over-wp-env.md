# 1. DDEV for local development, wp-env only for disposable test sites

- Status: Accepted
- Date: 2026-06-12

## Context

The WordPress ecosystem has converged on `@wordpress/env` (wp-env) for local
work: it is what the official documentation, Automattic and 10up recommend,
with WordPress Playground as the fastest throwaway option. wp-env is built
around plugin and theme sandboxes, though. A full site needs a stack that
behaves like production: a real web server, mail capture, Xdebug, switchable
PHP and database versions, and the same directory layout as the deployed site.

## Decision

Use DDEV, configured as code in the committed `.ddev/` directory, as the local
development environment.

Use wp-env only where a disposable WordPress instance is the point: running
Playwright end-to-end tests, locally and in CI. It needs nothing beyond Docker
and Node, starts from a clean database every time and does not have to mirror
production.

Tooling above the runtime stays WordPress-official: `@wordpress/scripts`,
`@wordpress/create-block`, WP-CLI and the core PHPUnit test library.

## Consequences

- Developers get a production-like stack with one `ddev start`.
- Two tools exist, but with separate jobs: DDEV holds state you develop
  against, wp-env is recreated per test run. Nothing is shared between them,
  so they cannot drift in a way that matters.
- The wp-env core version is pinned to the Composer-locked version in CI, so
  end-to-end tests run on the exact core that ships.

## Considered options

- **wp-env only.** Official and simple, but not production-like enough for
  full-site work.
- **LocalWP.** GUI-driven, not config-as-code, not reproducible per project.
- **Hand-written Docker Compose.** Flexible, but every project would maintain
  its own copy of what DDEV already provides.
