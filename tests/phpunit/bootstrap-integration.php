<?php
/**
 * Integration test bootstrap: boots a real WordPress install against a test database.
 *
 * The WordPress test library comes from the wp-phpunit/wp-phpunit Composer
 * package, and core from roots/wordpress, so no svn checkout or download
 * step is needed. Database credentials are read from environment variables
 * in wp-tests-config.php.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

// Composer autoloader: Yoast PHPUnit Polyfills, and sets WP_PHPUNIT__DIR.
require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . __DIR__ . '/wp-tests-config.php' );

$site_tests_dir = getenv( 'WP_TESTS_DIR' ) ?: getenv( 'WP_PHPUNIT__DIR' );

if ( ! $site_tests_dir || ! file_exists( $site_tests_dir . '/includes/functions.php' ) ) {
	echo 'WordPress test library not found. Run `composer install` first.' . PHP_EOL;
	exit( 1 );
}

require_once $site_tests_dir . '/includes/functions.php';

// Mu-plugins (including Site Core) load automatically from WP_CONTENT_DIR.
// Activate the Custom Theme before WordPress sets up the theme.
tests_add_filter(
	'muplugins_loaded',
	static function (): void {
		switch_theme( 'custom-theme' );
	}
);

require $site_tests_dir . '/includes/bootstrap.php';
