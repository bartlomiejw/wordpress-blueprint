<?php
/**
 * MU-plugin loader.
 *
 * WordPress only auto-loads PHP files directly inside mu-plugins/, not
 * subdirectories. This file loads each plugin in its own subdirectory.
 * Pattern recommended by WP VIP.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

$site_mu_plugins = [
	'site-core/site-core.php',
];

foreach ( $site_mu_plugins as $site_mu_plugin ) {
	require_once __DIR__ . '/' . $site_mu_plugin;
}
