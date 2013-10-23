<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests\Logger;

use Joomla\Log\LogEntry;
use Joomla\Log\Logger\Callback;
use Joomla\Test\TestHelper;

require_once __DIR__ . '/CallbackMethods.php';

/**
 * Test class for Joomla\Log\Logger\Callback.
 *
 * @since  1.0
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor01()
	{
		// Create a callback function
		$callback = create_function('$entry', 'return;');

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor02()
	{
		// Create a callback function (since php 5.3)
		$callback = function ($entry)
		{
			return;
		};

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor03()
	{
		// Use a defined function
		$callback = 'jLogLoggerCallbackTestHelperFunction';

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor04()
	{
		// Use a defined static method
		$callback = array('\\Joomla\\Log\\Tests\\Logger\\CallbackHelper', 'callback01');

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor05()
	{
		// Use a defined static method (since php 5.2.3)
		$callback = '\\Joomla\\Log\\Tests\\Logger\\CallbackHelper::callback01';

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::__construct method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testConstructor06()
	{
		// Use a defined object method
		$obj = new CallbackHelper;
		$callback = array($obj, 'callback02');

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);

		// Callback was set.
		$this->assertEquals(TestHelper::getValue($logger, 'callback'), $callback, 'Line: ' . __LINE__);

		// Callback is callable
		$this->assertTrue(is_callable(TestHelper::getValue($logger, 'callback')), 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\Callback::addEntry method.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function testAddEntry()
	{
		// Use a defined static method (since php 5.2.3)
		$callback = '\\Joomla\\Log\\Tests\\Logger\\CallbackHelper::callback01';

		// Setup the basic configuration.
		$config = array(
			'callback' => $callback
		);

		$logger = new Callback($config);
		$entry  = new LogEntry('Testing Entry');

		$logger->addEntry($entry);
		$this->assertEquals(CallbackHelper::$lastEntry, $entry, 'Line: ' . __LINE__);
	}
}
