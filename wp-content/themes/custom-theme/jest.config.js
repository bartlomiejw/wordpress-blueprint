/**
 * Extends the @wordpress/scripts Jest config (preset, Babel transform, jsdom)
 * instead of replacing it, and adds Testing Library's DOM matchers.
 */
const defaultConfig = require( '@wordpress/scripts/config/jest-unit.config' );

module.exports = {
	...defaultConfig,
	setupFilesAfterEnv: [
		...( defaultConfig.setupFilesAfterEnv || [] ),
		'@testing-library/jest-dom',
	],
};
