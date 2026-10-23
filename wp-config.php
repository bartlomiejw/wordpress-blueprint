<?php
/**
 * WordPress configuration.
 *
 * Every value comes from the environment: real environment variables (DDEV,
 * CI, hosting panel) or a local `.env` file. See `.env.example`.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

if ( file_exists( __DIR__ . '/.env' ) ) {
	Dotenv::createImmutable( __DIR__ )->safeLoad();
}

/**
 * Read a configuration value from $_ENV, $_SERVER or getenv(), in that order.
 *
 * @param string $key      Variable name.
 * @param string $fallback Value used when the variable is not set.
 */
function site_env( string $key, string $fallback = '' ): string {
	$value = $_ENV[ $key ] ?? $_SERVER[ $key ] ?? getenv( $key );

	return false === $value || null === $value ? $fallback : (string) $value;
}

/**
 * Read a boolean configuration value ("true", "1", "yes", "on").
 *
 * @param string $key Variable name.
 */
function site_env_bool( string $key ): bool {
	return filter_var( site_env( $key, 'false' ), FILTER_VALIDATE_BOOLEAN );
}

// -- Database -----------------------------------------------------------------
define( 'DB_NAME', site_env( 'DB_NAME', 'wordpress' ) );
define( 'DB_USER', site_env( 'DB_USER', 'wordpress' ) );
define( 'DB_PASSWORD', site_env( 'DB_PASSWORD' ) );
define( 'DB_HOST', site_env( 'DB_HOST', 'localhost' ) );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

$table_prefix = site_env( 'DB_PREFIX', 'wp_' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

// -- Authentication keys and salts --------------------------------------------
// Generate values at https://api.wordpress.org/secret-key/1.1/salt/
foreach ( [ 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ] as $site_salt ) {
	define( $site_salt, site_env( $site_salt, 'replace-me' ) );
}
unset( $site_salt );

// -- URLs: core lives in /wp, content in /wp-content --------------------------
define( 'WP_HOME', rtrim( site_env( 'WP_HOME', 'http://localhost' ), '/' ) );
define( 'WP_SITEURL', WP_HOME . '/wp' );
define( 'WP_CONTENT_DIR', __DIR__ . '/wp-content' );
define( 'WP_CONTENT_URL', WP_HOME . '/wp-content' );

// -- Environment and debugging ------------------------------------------------
define( 'WP_ENVIRONMENT_TYPE', site_env( 'WP_ENVIRONMENT_TYPE', 'production' ) );
define( 'WP_DEBUG', site_env_bool( 'WP_DEBUG' ) );
define( 'WP_DEBUG_LOG', site_env_bool( 'WP_DEBUG_LOG' ) );
define( 'WP_DEBUG_DISPLAY', site_env_bool( 'WP_DEBUG_DISPLAY' ) );
define( 'SCRIPT_DEBUG', site_env_bool( 'SCRIPT_DEBUG' ) );
define( 'SAVEQUERIES', site_env_bool( 'SAVEQUERIES' ) );

// -- Hardening: code changes ship through git and CI, not the admin -----------
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}

require_once ABSPATH . 'wp-settings.php';
