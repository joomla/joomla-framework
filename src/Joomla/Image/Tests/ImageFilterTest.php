<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Image\Filter\Brightness as FilterBrightness;

use Joomla\Test\TestHelper;

/**
 * Test class for Image.
 *
 * @since  1.0
 */
class ImageFilterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		// Verify that GD support for PHP is available.
		if (!extension_loaded('gd'))
		{
			$this->markTestSkipped('No GD support so skipping Image tests.');
		}
	}

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   1.0
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * Tests the Image::__construct method - with an invalid argument.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  InvalidArgumentException
	 */
	public function testConstructorInvalidArgument()
	{
		$filter = new FilterBrightness('test');
	}

	/**
	 * Tests the Image::__construct method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testConstructor()
	{
		// Create an image handle of the correct size.
		$imageHandle = imagecreatetruecolor(100, 100);

		$filter = new FilterBrightness($imageHandle);

		$this->assertEquals(TestHelper::getValue($filter, 'handle'), $imageHandle);
	}
}
