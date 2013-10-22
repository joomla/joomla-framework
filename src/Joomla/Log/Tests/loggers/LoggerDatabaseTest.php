<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/stubs/database/inspector.php';

use Joomla\Log\Log;
use Joomla\Log\LogEntry;
use Joomla\Test\TestDatabase;

/**
 * Test class for LoggerDatabase.
 *
 * @since  1.0
 */
class LoggerDatabaseTest extends TestDatabase
{
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  \PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/stubs/database/S01.xml');
	}

	/**
	 * Test the Joomla\Log\Logger\Database::__construct method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testConstructor01()
	{
		$config = array(
			'db' => self::$driver
		);

		$logger = new JLogLoggerDatabaseInspector($config);

		$this->assertInstanceOf(
			'Joomla\\Database\\DatabaseDriver',
			$logger->db,
			'The $db property of a properly configured Database storage must be an instance of Joomla\\Database\\DatabaseDriver'
		);
	}

	/**
	 * Test that the constructor throws an exception when
	 * it's not passed a configured database driver.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  \RuntimeException
	 */
	public function testConstructorException()
	{
		$config = array();
		$logger = new JLogLoggerDatabaseInspector($config);
	}

	/**
	 * Test the Joomla\Log\Logger\Database::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry01()
	{
		$config = array(
			'db' => self::$driver
		);
		$logger = new JLogLoggerDatabaseInspector($config);

		// Get the expected database from XML.
		$expected = $this->createXMLDataSet(__DIR__ . '/stubs/database/S01E01.xml');

		// Add the new entries to the database.
		$logger->addEntry(new LogEntry('Testing Entry 02', Log::INFO, null, '2009-12-01 12:30:00'));
		$logger->addEntry(new LogEntry('Testing3', Log::EMERGENCY, 'deprecated', '2010-12-01 02:30:00'));

		// Get the actual dataset from the database.
		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('jos_log_entries');

		// Verify that the data sets are equal.
		$this->assertDataSetsEqual($expected, $actual);
	}
}
