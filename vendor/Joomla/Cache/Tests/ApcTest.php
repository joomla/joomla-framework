<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\Apc class.
 *
 * @since  1.0
 */
class ApcTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cache\Apc
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\Apc::doDelete method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::doDelete
	 * @since   1.0
	 */
	public function testDoDelete()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::doGet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::doGet
	 * @since   1.0
	 */
	public function testDoGet()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\Apc::doSet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Apc::doSet
	 * @since   1.0
	 */
	public function testDoSet()
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
			$this->instance = new Cache\Apc;
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
