import { test, expect } from '@playwright/test';

test.describe( 'Homepage', () => {
	test( 'loads successfully', async ( { page } ) => {
		await page.goto( '/' );
		await expect( page ).toHaveTitle( /.+/ );
	} );

	test( 'has no console errors', async ( { page } ) => {
		const errors = [];
		page.on( 'console', ( msg ) => {
			if ( msg.type() === 'error' ) {
				errors.push( msg.text() );
			}
		} );

		await page.goto( '/' );
		expect( errors ).toHaveLength( 0 );
	} );

	test( 'passes basic accessibility check', async ( { page } ) => {
		await page.goto( '/' );
		// Confirm landmark regions exist (WCAG 2.1 — 1.3.6)
		await expect( page.locator( 'header' ) ).toBeVisible();
		await expect( page.locator( 'main' ) ).toBeVisible();
		await expect( page.locator( 'footer' ) ).toBeVisible();
	} );
} );
