<?php
/**
 * Integration tests for the example block's server-side render.
 *
 * Requires built blocks (`npm run build` in the theme), because the theme
 * registers blocks from build/blocks.
 *
 * @package CustomTheme
 */

declare( strict_types=1 );

namespace CustomTheme\Tests\Integration\Blocks;

use WP_Block_Type_Registry;
use WP_UnitTestCase;

/**
 * Covers blocks/example-block/render.php.
 */
class Example_Block_Render_Test extends WP_UnitTestCase {

	private const BLOCK_NAME = 'custom-theme/example-block';

	public function test_block_is_registered_by_the_theme(): void {
		$this->assertTrue(
			WP_Block_Type_Registry::get_instance()->is_registered( self::BLOCK_NAME ),
			'Block is not registered. Run `npm run build` in the theme before integration tests.'
		);
	}

	public function test_renders_heading_and_content(): void {
		$html = $this->render_block( [ 'heading' => 'Hello', 'content' => '<p>World</p>' ] );

		$this->assertStringContainsString( 'wp-block-custom-theme-example-block', $html );
		$this->assertStringContainsString( '<h2 class="example-block__heading">Hello</h2>', $html );
		$this->assertStringContainsString( '<div class="example-block__content"><p>World</p></div>', $html );
	}

	public function test_escapes_heading_and_strips_unsafe_content(): void {
		$html = $this->render_block(
			[
				'heading' => '<em>Hi</em>',
				'content' => '<p>Safe</p><script>alert(1)</script>',
			]
		);

		$this->assertStringContainsString( '&lt;em&gt;Hi&lt;/em&gt;', $html );
		$this->assertStringContainsString( '<p>Safe</p>', $html );
		$this->assertStringNotContainsString( '<script>', $html );
	}

	public function test_renders_nothing_without_attributes(): void {
		$this->assertSame( '', trim( $this->render_block( [] ) ) );
	}

	/**
	 * Render the block through the block parser, as on the front end.
	 *
	 * @param array<string, string> $attributes Block attributes.
	 */
	private function render_block( array $attributes ): string {
		$serialized = serialize_block(
			[
				'blockName'    => self::BLOCK_NAME,
				'attrs'        => $attributes,
				'innerBlocks'  => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		return do_blocks( $serialized );
	}
}
