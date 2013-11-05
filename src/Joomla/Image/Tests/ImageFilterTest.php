<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Image\Filter\Brightness as FilterBrightness;
use Joomla\Image\Filter\Inspector as FilterInspector;
use Joomla\Test\TestHelper;

/**
 * Test class for Image.
 *
 * @since  1.0
 */
class ImageFilterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var  FilterInspector  The object to test.
	 */
	protected $instance;

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

		$this->instance = new FilterInspector(imagecreate(10, 10));
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

	/**
	 * Tests the ImageFilter::getLogger for a NullLogger.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNullLogger()
	{
		$logger = $this->instance->getLogger();

		$this->assertInstanceOf(
			'Psr\\Log\\NullLogger',
			$logger,
			'When a logger has not been set, an instance of NullLogger should be returned.'
		);
	}

	/**
	 * Tests the ImageFilter::setLogger and Image::getLogger.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetLogger()
	{
		$mockLogger = $this->getMock('Psr\\Log\\AbstractLogger', array('log'), array(), '', false);

		$this->assertSame(
			$this->instance,
			$this->instance->setLogger($mockLogger),
			'The current instance of the application is returned when using setLogger. This is to support chaining.'
		);
		$this->assertSame(
			$mockLogger,
			$this->instance->getLogger(),
			'The getLogger method should return the same logger instance that was set via setLogger.'
		);
	}
}
