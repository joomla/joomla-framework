<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Test\TestHelper;
use Joomla\Test\TestDatabase;
use Psr\Log;

require_once __DIR__ . '/Stubs/nosqldriver.php';

/**
 * Test class for Joomla\Database\DatabaseDriver.
 * Generated by PHPUnit on 2009-10-08 at 22:00:38.
 *
 * @since  1.0
 */
class DriverTest extends TestDatabase
{
	/**
	 * @var    \Joomla\Database\DatabaseDriver
	 * @since  1.0
	 */
	protected $instance;

	/**
	 * A store to track if logging is working.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $logs;

	/**
	 * Mocks the log method to track if logging is working.
	 *
	 * @param   Log\LogLevel  $level    The level.
	 * @param   string        $message  The message.
	 * @param   array         $context  The context.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function mockLog($level, $message, $context)
	{
		$this->logs[] = array(
			'level' => $level,
			'message' => $message,
			'context' => $context,
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseDriver::__call method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__callQuote()
	{
		$this->assertThat(
			$this->instance->q('foo'),
			$this->equalTo($this->instance->quote('foo')),
			'Tests the q alias of quote.'
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseDriver::__call method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__callQuoteName()
	{
		$this->assertThat(
			$this->instance->qn('foo'),
			$this->equalTo($this->instance->quoteName('foo')),
			'Tests the qn alias of quoteName.'
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseDriver::__call method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__callUnknown()
	{
		$this->assertThat(
			$this->instance->foo(),
			$this->isNull(),
			'Tests for an unknown method.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement test__construct().
	 *
	 * @return void
	 */
	public function test__construct()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetInstance().
	 *
	 * @return void
	 */
	public function testGetInstance()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement test__destruct().
	 *
	 * @return void
	 */
	public function test__destruct()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getConnection method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetConnection()
	{
		$this->assertNull($this->instance->getConnection());
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getConnectors method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetConnectors()
	{
		$this->assertContains(
			'Sqlite',
			$this->instance->getConnectors(),
			'The getConnectors method should return an array with Sqlite as an available option.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getCount method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCount()
	{
		$this->assertEquals(0, $this->instance->getCount());
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getDatabase method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDatabase()
	{
		$this->assertEquals('europa', TestHelper::invoke($this->instance, 'getDatabase'));
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getDateFormat method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDateFormat()
	{
		$this->assertThat(
			$this->instance->getDateFormat(),
			$this->equalTo('Y-m-d H:i:s')
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::splitSql method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSplitSql()
	{
		$this->assertThat(
			$this->instance->splitSql('SELECT * FROM #__foo;SELECT * FROM #__bar;'),
			$this->equalTo(
				array(
					'SELECT * FROM #__foo;',
					'SELECT * FROM #__bar;'
				)
			),
			'splitSql method should split a string of multiple queries into an array.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getPrefix method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPrefix()
	{
		$this->assertThat(
			$this->instance->getPrefix(),
			$this->equalTo('&')
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getNullDate method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNullDate()
	{
		$this->assertThat(
			$this->instance->getNullDate(),
			$this->equalTo('1BC')
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::getMinimum method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetMinimum()
	{
		$this->assertThat(
			$this->instance->getMinimum(),
			$this->equalTo('12.1'),
			'getMinimum should return a string with the minimum supported database version number'
		);
	}

	/**
	 * Tests the Driver::log method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Database\DatabaseDriver::log
	 * @covers  Joomla\Database\DatabaseDriver::setLogger
	 * @since   1.0
	 */
	public function testLog()
	{
		$this->logs = array();

		$mockLogger = $this->getMock('Psr\Log\AbstractLogger', array('log'), array(), '', false);
		$mockLogger->expects($this->any())
			->method('log')
			->will($this->returnCallback(array($this, 'mockLog')));

		$this->instance->log(Log\LogLevel::DEBUG, 'Debug', array('sql' => true));

		$this->assertEmpty($this->logs, 'Logger not set up yet.');

		// Set the logger and try again.

		$this->instance->setLogger($mockLogger);

		$this->instance->log(Log\LogLevel::DEBUG, 'Debug', array('sql' => true));

		$this->assertEquals(Log\LogLevel::DEBUG, $this->logs[0]['level']);
		$this->assertEquals('Debug', $this->logs[0]['message']);
		$this->assertEquals(array('sql' => true), $this->logs[0]['context']);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::isMinimumVersion method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testIsMinimumVersion()
	{
		$this->assertThat(
			$this->instance->isMinimumVersion(),
			$this->isTrue(),
			'isMinimumVersion should return a boolean true if the database version is supported by the driver'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::setDebug method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetDebug()
	{
		$this->assertThat(
			$this->instance->setDebug(true),
			$this->isType('boolean'),
			'setDebug should return a boolean value containing the previous debug state.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::setQuery method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetQuery()
	{
		$this->assertThat(
			$this->instance->setQuery('SELECT * FROM #__dbtest'),
			$this->isInstanceOf('Joomla\Database\DatabaseDriver'),
			'setQuery method should return an instance of Joomla\Database\DatabaseDriver.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::replacePrefix method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testReplacePrefix()
	{
		$this->assertThat(
			$this->instance->replacePrefix('SELECT * FROM #__dbtest'),
			$this->equalTo('SELECT * FROM &dbtest'),
			'replacePrefix method should return the query string with the #__ prefix replaced by the actual table prefix.'
		);

		$this->assertThat(
			$this->instance->replacePrefix('SELECT * FROM #_dbtest'),
			$this->equalTo('SELECT * FROM #_dbtest'),
			'replacePrefix method should return the query string without the #_ prefix replaced.'
		);

		$this->assertThat(
			$this->instance->replacePrefix('SELECT * FROM #___dbtest'),
			$this->equalTo('SELECT * FROM &_dbtest'),
			'replacePrefix method should return the query string with the `#__` prefix (first 2 of 3 underscores) replaced.'
		);

		$this->assertThat(
			$this->instance->replacePrefix('SELECT * FROM $_#__dbtest'),
			$this->equalTo('SELECT * FROM $_&dbtest'),
			'replacePrefix method should return the query string with the #__ prefix in the middle of a table string replaced.'
		);

		$this->assertThat(
			$this->instance->replacePrefix('SELECT * FROM #__#__dbtest'),
			$this->equalTo('SELECT * FROM &&dbtest'),
			'replacePrefix method should return the query string with multiple #__ prefixes replaced.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Database\DatabaseDriver::quote
	 * @since   1.0
	 */
	public function testQuote()
	{
		$this->assertThat(
			$this->instance->quote('test', false),
			$this->equalTo("'test'"),
			'Tests the without escaping.'
		);

		$this->assertThat(
			$this->instance->quote('test'),
			$this->equalTo("'-test-'"),
			'Tests the with escaping (default).'
		);

		$this->assertEquals(
			array("'-test1-'", "'-test2-'"),
			$this->instance->quote(array('test1', 'test2')),
			'Check that the array is quoted.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteBooleanTrue()
	{
		$this->assertThat(
			$this->instance->quote(true),
			$this->equalTo("'-1-'"),
			'Tests handling of boolean true with escaping (default).'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteBooleanFalse()
	{
		$this->assertThat(
			$this->instance->quote(false),
			$this->equalTo("'--'"),
			'Tests handling of boolean false with escaping (default).'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteNull()
	{
		$this->assertThat(
			$this->instance->quote(null),
			$this->equalTo("'--'"),
			'Tests handling of null with escaping (default).'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteInteger()
	{
		$this->assertThat(
			$this->instance->quote(42),
			$this->equalTo("'-42-'"),
			'Tests handling of integer with escaping (default).'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quote method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteFloat()
	{
		$this->assertThat(
			$this->instance->quote(3.14),
			$this->equalTo("'-3.14-'"),
			'Tests handling of float with escaping (default).'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::quoteName method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testQuoteName()
	{
		$this->assertThat(
			$this->instance->quoteName('test'),
			$this->equalTo('[test]'),
			'Tests the left-right quotes on a string.'
		);

		$this->assertThat(
			$this->instance->quoteName('a.test'),
			$this->equalTo('[a].[test]'),
			'Tests the left-right quotes on a dotted string.'
		);

		$this->assertThat(
			$this->instance->quoteName(array('a', 'test')),
			$this->equalTo(array('[a]', '[test]')),
			'Tests the left-right quotes on an array.'
		);

		$this->assertThat(
			$this->instance->quoteName(array('a.b', 'test.quote')),
			$this->equalTo(array('[a].[b]', '[test].[quote]')),
			'Tests the left-right quotes on an array.'
		);

		$this->assertThat(
			$this->instance->quoteName(array('a.b', 'test.quote'), array(null, 'alias')),
			$this->equalTo(array('[a].[b]', '[test].[quote] AS [alias]')),
			'Tests the left-right quotes on an array.'
		);

		$this->assertThat(
			$this->instance->quoteName(array('a.b', 'test.quote'), array('alias1', 'alias2')),
			$this->equalTo(array('[a].[b] AS [alias1]', '[test].[quote] AS [alias2]')),
			'Tests the left-right quotes on an array.'
		);

		$this->assertThat(
			$this->instance->quoteName((object) array('a', 'test')),
			$this->equalTo(array('[a]', '[test]')),
			'Tests the left-right quotes on an object.'
		);

// 		TestHelper::setValue($this->db, 'nameQuote', '/');

		$refl = new \ReflectionClass($this->instance);
		$property = $refl->getProperty('nameQuote');
		$property->setAccessible(true);
		$property->setValue($this->instance, '/');

		$this->assertThat(
			$this->instance->quoteName('test'),
			$this->equalTo('/test/'),
			'Tests the uni-quotes on a string.'
		);
	}

	/**
	 * Tests the Joomla\Database\DatabaseDriver::truncateTable method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testTruncateTable()
	{
		$this->assertNull(
			$this->instance->truncateTable('#__dbtest'),
			'truncateTable should not return anything if successful.'
		);
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->instance = \Joomla\Database\DatabaseDriver::getInstance(
			array(
				'driver' => 'nosql',
				'database' => 'europa',
				'prefix' => '&',
			)
		);
	}

	/**
	 * Tears down the fixture.
	 *
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		// We need this to be empty.
	}
}
