<?php
/**
 * Example custom post type.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

namespace SiteCore\Post_Types;

/**
 * Example custom post type.
 *
 * Copy, rename, and adapt for each CPT the site needs.
 * Delete this file once the real CPTs are in place.
 */
class Example_Post_Type {

	public const POST_TYPE = 'example';

	/**
	 * Register the post type with WordPress.
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'       => $this->labels(),
				'public'       => true,
				'show_in_rest' => true, // Required for block editor support.
				'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
				'has_archive'  => true,
				'rewrite'      => [ 'slug' => 'examples' ],
				'menu_icon'    => 'dashicons-admin-post',
			]
		);
	}

	/**
	 * Admin UI labels.
	 *
	 * @return array<string, string>
	 */
	private function labels(): array {
		return [
			'name'               => __( 'Examples', 'site-core' ),
			'singular_name'      => __( 'Example', 'site-core' ),
			'add_new_item'       => __( 'Add New Example', 'site-core' ),
			'edit_item'          => __( 'Edit Example', 'site-core' ),
			'new_item'           => __( 'New Example', 'site-core' ),
			'view_item'          => __( 'View Example', 'site-core' ),
			'search_items'       => __( 'Search Examples', 'site-core' ),
			'not_found'          => __( 'No examples found', 'site-core' ),
			'not_found_in_trash' => __( 'No examples found in trash', 'site-core' ),
		];
	}
}
