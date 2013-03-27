<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Event\Tests;

use Joomla\Event\EventImmutable;

/**
 * Tests for the EventImmutable class.
 *
 * @since  1.0
 */
class EventImmutableTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Object under tests.
	 *
	 * @var    EventImmutable
	 *
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Test the offsetSet method.
	 *
	 * @expectedException  \BadMethodCallException
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testOffsetSet()
	{
		$this->instance['foo'] = 'bar';
	}

	/**
	 * Test the offsetUnset method.
	 *
	 * @expectedException  \BadMethodCallException
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testOffsetUnSet()
	{
		unset($this->instance['foo']);
	}

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$this->instance = new EventImmutable('test');
	}
}
