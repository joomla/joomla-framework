<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Test\TestHelper;

/**
 * Class to mock Joomla\Mocker\Cache.
 *
 * @since  1.0
 */
class Mocker
{
	/**
	 * @var    \PHPUnit_Framework_TestCase
	 * @since  1.0
	 */
	private $test;

	/**
	 * Class contructor.
	 *
	 * @param   \PHPUnit_Framework_TestCase  $test  A test class.
	 *
	 * @since   1.0
	 */
	public function __construct(\PHPUnit_Framework_TestCase $test)
	{
		$this->test = $test;
	}

	/**
	 * Creates and instance of a mock Joomla\Mocker\Cache object.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createMockCache()
	{
		// Collect all the relevant methods in JDatabase.
		$methods = array(
			'clear',
			'exists',
			'get',
			'getMultiple',
			'remove',
			'removeMultiple',
			'set',
			'setMultiple',
		);

		// Create the mock.
		$mockObject = $this->test->getMock(
			'Joomla\Cache\Cache',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		TestHelper::assignMockCallbacks(
			$mockObject,
			$this->test,
			array(
				'get' => array((is_callable(array($this->test, 'mockCacheGet')) ? $this->test : $this), 'mockCacheGet'),
			)
		);

		return $mockObject;
	}

	/**
	 * Creates and instance of a mock Joomla\Cache\Item object.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createMockItem()
	{
		// Collect all the relevant methods in JDatabase.
		$methods = array(
			'getKey',
			'getValue',
			'isHit',
			'setValue',
		);

		// Create the mock.
		$mockObject = $this->test->getMock(
			'Joomla\Cache\Item',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		TestHelper::assignMockCallbacks(
			$mockObject,
			$this->test,
			array(
				'getValue' => array((is_callable(array($this->test, 'mockCacheItemGetValue')) ? $this->test : $this), 'mockCacheItemGetValue'),
				'isHit' => array((is_callable(array($this->test, 'mockCacheItemIsHit')) ? $this->test : $this), 'mockCacheItemIsHit'),
			)
		);

		return $mockObject;
	}

	/**
	 * Callback to mock the Joomla\Cache\Cache::get method.
	 *
	 * @param   string  $key  The input text.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function mockCacheGet($key)
	{
		return $this->createMockItem();
	}

	/**
	 * Callback to mock the Joomla\Cache\Item::getValue method.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function mockCacheItemGetValue()
	{
		return 'value';
	}

	/**
	 * Callback to mock the Joomla\Cache\Item::isHit method.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function mockCacheItemIsHit()
	{
		return false;
	}
}
