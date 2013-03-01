<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Image
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Image\Filter\Brightness as FilterBrightness;

/**
 * Test class for JImage.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Image
 * @since       11.4
 */
class JImageFilterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   11.4
	 */
	protected function setUp()
	{
		parent::setUp();

		// Verify that GD support for PHP is available.
		if (!extension_loaded('gd'))
		{
			$this->markTestSkipped('No GD support so skipping JImage tests.');
		}
	}

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   11.4
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * Tests the JImage::__construct method - with an invalid argument.
	 *
	 * @return  void
	 *
	 * @since   11.4
	 *
	 * @expectedException  InvalidArgumentException
	 */
	public function testConstructorInvalidArgument()
	{
		$filter = new FilterBrightness('test');
	}

	/**
	 * Tests the JImage::__construct method.
	 *
	 * @return  void
	 *
	 * @since   11.4
	 */
	public function testConstructor()
	{
		// Create an image handle of the correct size.
		$imageHandle = imagecreatetruecolor(100, 100);

		$filter = new FilterBrightness($imageHandle);

		$this->assertEquals(TestReflection::getValue($filter, 'handle'), $imageHandle);
	}
}
