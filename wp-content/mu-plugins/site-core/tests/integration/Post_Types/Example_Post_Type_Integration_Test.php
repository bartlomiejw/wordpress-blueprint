<?php
/**
 * Integration tests for the example custom post type.
 *
 * @package SiteCore
 */

declare( strict_types=1 );

namespace SiteCore\Tests\Integration\Post_Types;

use SiteCore\Post_Types\Example_Post_Type;
use WP_REST_Request;
use WP_UnitTestCase;

/**
 * Covers registration of the CPT by the Site Core mu-plugin.
 */
class Example_Post_Type_Integration_Test extends WP_UnitTestCase {

	public function test_post_type_is_registered_on_init_by_site_core(): void {
		// No manual register() call: the mu-plugin must have hooked it to init.
		$this->assertTrue( post_type_exists( Example_Post_Type::POST_TYPE ) );
	}

	public function test_post_type_supports_the_block_editor(): void {
		$post_type = get_post_type_object( Example_Post_Type::POST_TYPE );

		$this->assertNotNull( $post_type );
		$this->assertTrue( $post_type->show_in_rest );
		$this->assertTrue( use_block_editor_for_post_type( Example_Post_Type::POST_TYPE ) );

		foreach ( [ 'title', 'editor', 'thumbnail', 'excerpt' ] as $feature ) {
			$this->assertTrue( post_type_supports( Example_Post_Type::POST_TYPE, $feature ), "Missing support: {$feature}" );
		}
	}

	public function test_published_posts_are_exposed_in_the_rest_api(): void {
		$post_id = self::factory()->post->create(
			[
				'post_type'   => Example_Post_Type::POST_TYPE,
				'post_title'  => 'REST visible',
				'post_status' => 'publish',
			]
		);

		$response = rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/' . Example_Post_Type::POST_TYPE ) );

		$this->assertSame( 200, $response->get_status() );
		$this->assertContains( $post_id, wp_list_pluck( $response->get_data(), 'id' ) );
	}
}
