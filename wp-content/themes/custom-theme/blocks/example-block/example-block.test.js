import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import Edit from './edit';

// @wordpress/* packages are provided by WordPress at runtime (externalized by
// wp-scripts), so they aren't installed. Stub what Edit uses with minimal
// accessible equivalents — this test covers Edit's wiring, not core UI.
jest.mock(
	'@wordpress/block-editor',
	() => ( { useBlockProps: ( props ) => props } ),
	{ virtual: true }
);

jest.mock(
	'@wordpress/components',
	() => {
		const field =
			( Tag ) =>
			( { label, value, onChange } ) => (
				// eslint-disable-next-line jsx-a11y/label-has-associated-control -- control is nested.
				<label>
					{ label }
					<Tag
						value={ value }
						onChange={ ( event ) => onChange( event.target.value ) }
					/>
				</label>
			);

		return {
			TextControl: field( 'input' ),
			TextareaControl: field( 'textarea' ),
		};
	},
	{ virtual: true }
);

const noop = () => {};

describe( 'Example Block — Edit', () => {
	it( 'renders heading and content fields', () => {
		render(
			<Edit
				attributes={ { heading: '', content: '' } }
				setAttributes={ noop }
			/>
		);

		expect( screen.getByLabelText( /heading/i ) ).toBeInTheDocument();
		expect( screen.getByLabelText( /content/i ) ).toBeInTheDocument();
	} );

	it( 'calls setAttributes when heading changes', async () => {
		const setAttributes = jest.fn();
		render(
			<Edit
				attributes={ { heading: '', content: '' } }
				setAttributes={ setAttributes }
			/>
		);

		await userEvent.type( screen.getByLabelText( /heading/i ), 'Hello' );
		expect( setAttributes ).toHaveBeenCalled();
	} );
} );
