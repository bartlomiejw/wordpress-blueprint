# 3. Integration tests use the core test library from Composer, on PHPUnit 9.6

- Status: Accepted
- Date: 2026-10-23

## Context

Integration tests need a real WordPress: the core PHPUnit test library
(`WP_UnitTestCase`, factories, a database reset per test). The traditional
`install-wp-tests.sh` script fetches that library with Subversion and downloads
core separately. That adds an `svn` dependency (absent on current GitHub
runners), network calls on every run, and a second copy of core whose version
can differ from the one Composer installs.

The core test library still relies on PHPUnit internals removed in PHPUnit 10
(test method annotation parsing), so it fails under PHPUnit 10 and later.

## Decision

- Take the test library from `wp-phpunit/wp-phpunit` and core from
  `roots/wordpress`, both via Composer and kept on the same minor version.
- Point the library at `tests/phpunit/wp-tests-config.php`, which reads
  database credentials from `WP_TESTS_DB_*` environment variables and uses
  the project's own `wp/` and `wp-content/`.
- Pin PHPUnit to 9.6 (with Yoast PHPUnit Polyfills) for both unit and
  integration suites, so one configuration serves both.

## Consequences

- `composer install` is the only setup step; CI needs a MySQL service and
  nothing else.
- Integration tests boot the real mu-plugins and theme exactly as production
  does.
- Moving to PHPUnit 10+ is blocked until the core test library supports it.
  Dependabot is configured to skip PHPUnit major updates until then.
