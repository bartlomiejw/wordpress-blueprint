import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';

/**
 * Block editor component.
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { heading, content } = attributes;
	const blockProps = useBlockProps( { className: 'example-block' } );

	return (
		<div { ...blockProps }>
			<TextControl
				label={ __( 'Heading', 'custom-theme' ) }
				value={ heading }
				onChange={ ( value ) => setAttributes( { heading: value } ) }
			/>
			<TextareaControl
				label={ __( 'Content', 'custom-theme' ) }
				value={ content }
				onChange={ ( value ) => setAttributes( { content: value } ) }
			/>
		</div>
	);
}
