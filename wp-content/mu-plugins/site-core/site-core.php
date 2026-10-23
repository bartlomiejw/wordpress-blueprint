<?php
/**
 * Plugin Name: Site Core
 * Description: Site-wide functionality — CPTs, taxonomies, meta, integrations. No presentation logic.
 * Version: 1.0.0
 *
 * @package SiteCore
 */

declare( strict_types=1 );

namespace SiteCore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SITE_CORE_DIR', __DIR__ );
define( 'SITE_CORE_VERSION', '1.0.0' );

// PSR-4 autoloader for SiteCore\ → src/. Kept local (not Composer) so the
// plugin also works where the root vendor/ isn't loaded, e.g. under wp-env.
spl_autoload_register(
	static function ( string $class_name ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( ! str_starts_with( $class_name, $prefix ) ) {
			return;
		}

		$file = __DIR__ . '/src/' . str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

add_action( 'init', __NAMESPACE__ . '\\register_post_types' );
add_action( 'init', __NAMESPACE__ . '\\register_taxonomies' );

/**
 * Register custom post types.
 */
function register_post_types(): void {
	( new Post_Types\Example_Post_Type() )->register();
}

/**
 * Register custom taxonomies.
 */
function register_taxonomies(): void {
	// Register taxonomies here.
}
