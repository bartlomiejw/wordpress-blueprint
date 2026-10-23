<?php

declare( strict_types=1 );

namespace SiteCore\Tests\Unit\Post_Types;

use SiteCore\Post_Types\Example_Post_Type;
use PHPUnit\Framework\TestCase;

class Example_Post_Type_Test extends TestCase {

	public function test_post_type_constant(): void {
		$this->assertSame( 'example', Example_Post_Type::POST_TYPE );
	}
}