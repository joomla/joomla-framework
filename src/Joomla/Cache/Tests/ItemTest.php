<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\None class.
 *
 * @since  1.0
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cache\Item
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\Item class.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testItem()
	{
		$this->assertEquals('foo', $this->instance->getKey());
		$this->assertNull($this->instance->getValue());
		$this->assertFalse($this->instance->isHit());

		$this->instance->setValue('bar');
		$this->assertEquals('bar', $this->instance->getValue());
		$this->assertTrue($this->instance->isHit());
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new Cache\Item('foo');
	}
}
