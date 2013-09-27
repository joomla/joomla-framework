<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Image\Filter\Backgroundfill as FilterBackgroundfill;

/**
 * Test class for Image.
 *
 * @since       1.0
 */
class ImageFilterBackgroundfillTest extends PHPUnit_Framework_TestCase
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
	 * Tests the ImageFilterBackgroundfill::execute method.
	 *
	 * This tests to make sure we can brighten the image.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @note    Because GD2 uses 7bit alpha channel, results differ slightly 
	 *          compared to 8bit systems like Adobe Photoshop. 
	 *          Example: GD: 171, 45, 45, Photoshop: 172, 45, 45
	 *
	 * @note    To test alpha, use imagecolorsforindex($imageHandle, $color);
	 */
	public function testExecute()
	{
		// Create a image handle of the correct size.
		$imageHandle = imagecreatetruecolor(100, 100);
		imagealphablending($imageHandle, false);
		imagesavealpha($imageHandle, true);

		// Define semi-transparent gray areas.
		$dark = imagecolorallocatealpha($imageHandle, 90, 90, 90, 63);
		$light = imagecolorallocatealpha($imageHandle, 120, 120, 120, 63);

		imagefilledrectangle($imageHandle, 0, 0, 50, 99, $dark);
		imagefilledrectangle($imageHandle, 51, 0, 99, 99, $light);
		$filter = new FilterBackgroundfill($imageHandle);
		$filter->execute(array('color' => '#ff0000'));

		// Compare left part
		$color = imagecolorat($imageHandle, 25, 25);
		$this->assertEquals(
			array(171, 45, 45),
			array($color >> 16 & 0xFF, $color >> 8 & 0xFF, $color & 0xFF)
		);

		// Compare right part
		$color = imagecolorat($imageHandle, 51, 25);
		$this->assertEquals(
			array(186, 60, 60), // GD
			array($color >> 16 & 0xFF, $color >> 8 & 0xFF, $color & 0xFF)
		);
	}

	/**
	 * Tests the ImageFilterBackgroundFill::execute method - invalid argument.
	 *
	 * This tests to make sure an exception is properly thrown.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  InvalidArgumentException
	 */
	public function testExecuteInvalidArgument()
	{
		// Create a image handle of the correct size.
		$imageHandle = imagecreatetruecolor(100, 100);

		// Define red.
		$red = imagecolorallocate($imageHandle, 127, 0, 0);

		// Draw a red rectangle to fill the image.
		imagefilledrectangle($imageHandle, 0, 0, 100, 100, $red);

		$filter = new FilterBackgroundfill($imageHandle);

		$filter->execute(array());
	}	
}
