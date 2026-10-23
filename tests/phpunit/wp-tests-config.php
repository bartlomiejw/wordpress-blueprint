<?php
/**
 * WordPress test suite configuration.
 *
 * Override the database settings with environment variables, e.g.
 * WP_TESTS_DB_HOST=127.0.0.1:3306 composer test:integration
 *
 * The test suite drops and recreates every table in this database.
 * Never point it at a database you care about.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

$site_root = dirname( __DIR__, 2 );

define( 'ABSPATH', $site_root . '/wp/' );
define( 'WP_CONTENT_DIR', $site_root . '/wp-content' );

define( 'DB_NAME', getenv( 'WP_TESTS_DB_NAME' ) ?: 'wordpress_test' );
define( 'DB_USER', getenv( 'WP_TESTS_DB_USER' ) ?: 'root' );
define( 'DB_PASSWORD', getenv( 'WP_TESTS_DB_PASSWORD' ) ?: 'root' );
define( 'DB_HOST', getenv( 'WP_TESTS_DB_HOST' ) ?: '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Site' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WPLANG', '' );
define( 'WP_DEBUG', true );

$table_prefix = 'wptests_'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
