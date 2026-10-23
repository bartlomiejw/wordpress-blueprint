import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import './style.scss';

registerBlockType( metadata.name, {
	edit: Edit,
	// Dynamic block — save returns null; PHP render.php handles output.
	save: () => null,
} );
