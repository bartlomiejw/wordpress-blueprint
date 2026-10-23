# WordPress Blueprint

[![CI](https://github.com/bartlomiejw/wordpress-blueprint/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/bartlomiejw/wordpress-blueprint/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
![PHP 8.2 to 8.4](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.4-777bb4)
![WordPress 7.1](https://img.shields.io/badge/WordPress-7.1-21759b)

A template repository for building production WordPress sites the way
application code is built: dependencies declared and locked, code linted and
statically analysed, behaviour covered by unit, integration and end-to-end
tests, and every change on `main` verified by CI before it can ship.

Create a repository from it, rename a few things, and a new site starts with a
working block theme, a data-layer mu-plugin and the whole toolchain on day one.

## Why

Most WordPress projects start from a theme folder and a hosting control panel.
Quality tooling, if it arrives at all, arrives later and is bolted on. This
repository makes the opposite the default:

- **Reproducible.** WordPress core and plugins are Composer dependencies, so a
  fresh clone installs exactly what production runs. Nothing third-party is
  committed.
- **Separated concerns.** Content models live in an always-on mu-plugin; the
  theme only renders. A redesign can never make content disappear.
- **Verified.** WordPress Coding Standards, PHPStan level 8, Rector, ESLint,
  Stylelint, Jest, PHPUnit against a real WordPress, and Playwright, all run
  in CI on every pull request.
- **Boring to deploy.** Deployments are explicit (a version tag or a manual
  run), gated by GitHub Environments, and use pinned SSH host keys.

## What's inside

```
.
├── .github/
│   ├── workflows/ci.yml        # Quality, tests, build and E2E on every PR and push to main
│   ├── workflows/deploy.yml    # Tag or manual deploy over rsync/SSH, environment-gated
│   └── dependabot.yml          # Weekly Composer, npm and Actions updates
├── .ddev/                      # Local development environment (nginx, MySQL, Mailpit, Xdebug)
├── docs/
│   ├── architecture.md         # Vocabulary, boundaries, request flow
│   └── adr/                    # Architecture decision records
├── tests/
│   ├── e2e/                    # Playwright specs (run against wp-env)
│   └── phpunit/                # PHPUnit bootstraps and WordPress test config
├── wp/                         # WordPress core, installed by Composer (git-ignored)
├── wp-content/
│   ├── mu-plugins/
│   │   ├── plugin-loader.php   # Loads mu-plugins that live in subdirectories
│   │   └── site-core/          # Data layer: post types, taxonomies, meta, integrations
│   │       ├── src/            # PSR-4 classes (SiteCore\)
│   │       └── tests/          # Unit and integration tests
│   └── themes/custom-theme/    # Block theme: presentation only
│       ├── assets/             # SCSS (tokens, base, components) and JS entry points
│       ├── blocks/             # Custom blocks (block.json, edit.js, render.php, Jest tests)
│       ├── parts/, templates/  # Block templates and template parts
│       ├── tests/php/          # Theme integration tests
│       └── theme.json          # Layout widths and editor controls
├── .wp-env.json                # Disposable WordPress for E2E tests
├── composer.json               # Core, plugins, PHP tooling and scripts
├── phpcs.xml.dist, phpstan.neon, rector.php, phpunit.xml.dist
├── playwright.config.js
└── wp-config.php               # Reads all configuration from environment variables (.env)
```

How the parts fit together, and why the boundaries are where they are, is
described in [docs/architecture.md](docs/architecture.md).

## Getting started

### Requirements

- PHP 8.2+ and Composer 2
- Node.js 22+ and npm
- Docker, plus [DDEV](https://ddev.com/) for local development

### Run it locally

```bash
git clone https://github.com/bartlomiejw/wordpress-blueprint.git
cd wordpress-blueprint

composer install                                   # WordPress core + PHP tooling
npm install                                        # Git hooks, wp-env, Playwright
npm ci --prefix wp-content/themes/custom-theme     # Theme build tooling
npm run build --prefix wp-content/themes/custom-theme

ddev start                                         # https://site-project.ddev.site (admin / admin)
```

For a quick, disposable site without DDEV, `npm run env:start` starts wp-env
on <http://localhost:8888> with the theme active and Site Core loaded.

Theme development with file watching:

```bash
cd wp-content/themes/custom-theme
npm run start          # blocks
npm run start:assets   # global SCSS and JS
```

## Starting a new project from the template

1. Click **Use this template** on GitHub (or clone and re-initialise git).
2. Rename the project identifiers:
   - `name` in `composer.json` and `package.json`
   - `name` and URLs in `.ddev/config.yaml` and `.env.example`
3. Rename the theme if needed: the `custom-theme` directory, its `style.css`
   header and text domain, and every reference listed by
   `grep -rn "custom-theme" --exclude-dir={vendor,node_modules,wp} .`
4. Rename the `SiteCore` namespace and `site-core` text domain if the project
   needs a different prefix.
5. Replace the example post type (`Example_Post_Type`) and the example block
   with real ones; keep their tests as the pattern to follow.
6. Copy `.env.example` to `.env` and generate fresh salts.
7. In GitHub, protect `main` (see [Workflow](#workflow)) and, when a server
   exists, configure deployment (see [Deployment](#deployment)).

## Tooling

| Concern | Tool | Local command | CI job |
| --- | --- | --- | --- |
| PHP coding standards | PHP_CodeSniffer + WordPress Coding Standards | `composer phpcs` | `php-quality` |
| PHP static analysis | PHPStan 2 (level 8) + WordPress extension | `composer phpstan` | `php-quality` |
| PHP automated refactoring | Rector 2 (PHP 8.2, dead code, code quality, types) | `composer rector` | `php-quality` |
| PHP unit tests | PHPUnit 9.6 | `composer test:unit` | `phpunit` (PHP 8.2, 8.3, 8.4) |
| PHP integration tests | PHPUnit + WordPress core test library + MySQL | `composer test:integration` | `phpunit` (PHP 8.2, 8.3, 8.4) |
| JS and SCSS linting | ESLint and Stylelint via `@wordpress/scripts` | `npm run lint:js`, `npm run lint:css` (theme), `npm run lint:e2e` (root) | `js-quality` |
| JS unit tests | Jest + Testing Library | `npm run test:unit` (theme) | `js-quality` |
| Asset build | `@wordpress/scripts` (webpack, Sass) | `npm run build` (theme) | `build` |
| End-to-end tests | Playwright on wp-env | `npm run env:start && npm run test:e2e` | `e2e` |
| Pre-commit checks | Husky + lint-staged | runs on `git commit` | |
| Dependency updates | Dependabot (Composer, npm, GitHub Actions) | | weekly PRs |

`composer lint` runs all three PHP checks; `composer test` runs both PHP suites.

### Integration tests

Integration tests boot a real WordPress (core from `wp/`, the project's own
`wp-content/`) against a throwaway MySQL database. The test library comes from
Composer, so there is no install script ([ADR 0003](docs/adr/0003-wordpress-test-library-via-composer.md)).
Theme tests need built blocks.

```bash
docker run -d --name wp-tests-db -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=wordpress_test -p 3306:3306 mysql:8.0
npm run build --prefix wp-content/themes/custom-theme
composer test:integration
```

Connection settings default to `root:root@127.0.0.1/wordpress_test` and can be
overridden with `WP_TESTS_DB_HOST`, `WP_TESTS_DB_USER`, `WP_TESTS_DB_PASSWORD`
and `WP_TESTS_DB_NAME`. The suite drops all tables in that database.

## CI/CD

[`ci.yml`](.github/workflows/ci.yml) runs on every pull request and on every
push to `main`:

```
php-quality ─┐
js-quality  ─┤  (parallel)
build ───────┼──> phpunit (PHP 8.2 / 8.3 / 8.4, MySQL 8.0)
             └──> e2e (wp-env on the Composer-locked WordPress version, Playwright)
```

Superseded runs on a pull request are cancelled; runs on `main` always finish.
Third-party actions are pinned to commit SHAs and kept current by Dependabot.

### Deployment

[`deploy.yml`](.github/workflows/deploy.yml) builds a production artefact
(`composer install --no-dev`, compiled assets) and syncs it with rsync over SSH.
It never runs on a plain push:

- pushing a `v*` tag deploys to **production**;
- a manual run (`workflow_dispatch`) deploys to **staging** or **production**.

The job is skipped unless the repository variable `DEPLOY_ENABLED` is `true`.
Each GitHub Environment (`staging`, `production`) provides `SSH_PRIVATE_KEY`,
`SSH_KNOWN_HOSTS`, `SSH_HOST`, `SSH_USER` and `DEPLOY_PATH`. Host keys are
pinned through `SSH_KNOWN_HOSTS` and strict host key checking stays on.
`.deployignore` keeps development files and server-owned state (uploads,
`.env`) out of the sync.

## Workflow

The repository is developed trunk-based:

- **`main` is the trunk.** It is always releasable; nothing is committed to it
  directly.
- **Short-lived branches.** Work happens on branches that live hours or a few
  days (`feat/…`, `fix/…`, `chore/…`) and are merged through pull requests.
  Incomplete features ship dark behind flags rather than waiting on a branch.
- **Squash merge only.** Each pull request becomes one commit on `main`, with
  the PR title, written as a [Conventional Commit](https://www.conventionalcommits.org/),
  as its message.
- **Required checks.** Branch protection on `main` requires an up-to-date
  branch, one approving review and these checks: `PHP quality (PHPCS, PHPStan, Rector)`,
  `JS quality (ESLint, Stylelint, Jest)`, `Build theme assets`,
  `PHPUnit (PHP 8.2)`, `PHPUnit (PHP 8.3)`, `PHPUnit (PHP 8.4)` and
  `E2E (Playwright on wp-env)`.
- **Releases are tags.** A `vX.Y.Z` tag on a green `main` commit is what
  reaches production.

See [CONTRIBUTING.md](CONTRIBUTING.md) for the day-to-day version.

## Decisions

- [ADR 0001: DDEV for local development, wp-env only for disposable test sites](docs/adr/0001-ddev-over-wp-env.md)
- [ADR 0002: Classic WordPress layout with Composer-managed core, not Bedrock](docs/adr/0002-classic-composer-structure.md)
- [ADR 0003: Integration tests use the core test library from Composer, on PHPUnit 9.6](docs/adr/0003-wordpress-test-library-via-composer.md)

## License

[MIT](LICENSE) © Bartłomiej Warias
