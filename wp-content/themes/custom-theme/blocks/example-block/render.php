<?php
/**
 * Example block server-side render.
 *
 * Variables provided by core (WP_Block::render()):
 *   $attributes (array)    Block attributes.
 *   $content    (string)   Inner blocks markup (unused by this block).
 *   $block      (WP_Block) Block instance.
 *
 * Attribute values are escaped once, at output time.
 *
 * @package CustomTheme
 */

declare( strict_types=1 );

$example_block_heading = trim( (string) ( $attributes['heading'] ?? '' ) );
$example_block_body    = trim( (string) ( $attributes['content'] ?? '' ) );

if ( '' === $example_block_heading && '' === $example_block_body ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( [ 'class' => 'example-block' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core escapes the attributes. ?>>
	<?php if ( '' !== $example_block_heading ) : ?>
		<h2 class="example-block__heading"><?php echo esc_html( $example_block_heading ); ?></h2>
	<?php endif; ?>

	<?php if ( '' !== $example_block_body ) : ?>
		<div class="example-block__content"><?php echo wp_kses_post( $example_block_body ); ?></div>
	<?php endif; ?>
</div>
