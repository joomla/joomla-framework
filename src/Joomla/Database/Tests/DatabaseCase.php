<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Test\TestHelper;

/**
 * Abstract test case class for database testing.
 *
 * @since  1.0
 */
abstract class DatabaseCase extends \PHPUnit_Extensions_Database_TestCase
{
	/**
	 * @var    \Joomla\Database\DatabaseDriver  The active database driver being used for the tests.
	 * @since  1.0
	 */
	protected static $driver;

	/**
	 * This method is called before the first test of this test class is run.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function setUpBeforeClass()
	{
		// We always want the default database test case to use an SQLite memory database.
		$options = array(
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => 'jos_'
		);

		try
		{
			// Attempt to instantiate the driver.
			self::$driver = \Joomla\Database\DatabaseDriver::getInstance($options);

			// Create a new PDO instance for an SQLite memory database and load the test schema into it.
			$pdo = new \PDO('sqlite::memory:');
			$pdo->exec(file_get_contents(__DIR__ . '/Stubs/ddl.sql'));

			// Set the PDO instance to the driver using reflection whizbangery.
			TestHelper::setValue(self::$driver, 'connection', $pdo);
		}
		catch (\RuntimeException $e)
		{
			self::$driver = null;
		}

		// If for some reason an exception object was returned set our database object to null.
		if (self::$driver instanceof \Exception)
		{
			self::$driver = null;
		}
	}

	/**
	 * This method is called after the last test of this test class is run.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function tearDownAfterClass()
	{
		self::$driver = null;
	}

	/**
	 * Returns the default database connection for running the tests.
	 *
	 * @return  \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
	 *
	 * @since   1.0
	 */
	protected function getConnection()
	{
		if (!is_null(self::$driver))
		{
			return $this->createDefaultDBConnection(self::$driver->getConnection(), ':memory:');
		}
		else
		{
			return null;
		}
	}

	/**
	 * Gets the data set to be loaded into the database during setup.
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 *
	 * @since   1.0
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/stubs/database.xml');
	}

	/**
	 * Returns the database operation executed in test setup.
	 *
	 * @return  \PHPUnit_Extensions_Database_Operation_Composite
	 *
	 * @since   1.0
	 */
	protected function getSetUpOperation()
	{
		// Required given the use of InnoDB contraints.
		return new \PHPUnit_Extensions_Database_Operation_Composite(
			array(
				\PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL(),
				\PHPUnit_Extensions_Database_Operation_Factory::INSERT()
			)
		);
	}

	/**
	 * Returns the database operation executed in test cleanup.
	 *
	 * @return  \PHPUnit_Extensions_Database_Operation_IDatabaseOperation
	 *
	 * @since   1.0
	 */
	protected function getTearDownOperation()
	{
		// Required given the use of InnoDB contraints.
		return \PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL();
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
		if (empty(static::$driver))
		{
			$this->markTestSkipped('There is no database driver.');
		}

		parent::setUp();
	}
}
