<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\Wincache class.
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */
class WincacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Cache\Wincache
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the add method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Wincache::add
	 * @since   1.0
	 */
	public function testAdd()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the delete method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Wincache::delete
	 * @since   1.0
	 */
	public function testDelete()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the fetch method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Wincache::fetch
	 * @since   1.0
	 */
	public function testFetch()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Wincache::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->markTestIncomplete();
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

		try
		{
			$this->instance = new Cache\Wincache;
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
