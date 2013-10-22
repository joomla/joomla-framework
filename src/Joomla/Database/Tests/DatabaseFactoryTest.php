<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\DatabaseFactory;
use Joomla\Test\TestHelper;
use Joomla\Test\TestDatabase;

/**
 * Test class for Joomla\Database\DatabaseFactory.
 *
 * @since  1.0
 */
class DatabaseFactoryTest extends TestDatabase
{
	/**
	 * Object being tested
	 *
	 * @var    DatabaseFactory
	 * @since  1.0
	 */
	protected static $instance;

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
		parent::setUp();

		static::$instance = DatabaseFactory::getInstance();
	}

	/**
	 * Test for the Joomla\Database\DatabaseFactory::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertThat(
			DatabaseFactory::getInstance(),
			$this->isInstanceOf('\\Joomla\\Database\\DatabaseFactory'),
			'Tests that getInstance returns an instance of DatabaseFactory.'
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseFactory::getExporter method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetExporter()
	{
		$object = static::$instance;

		$this->assertThat(
			$object->getExporter('mysqli'),
			$this->isInstanceOf('\\Joomla\\Database\\Mysqli\\MysqliExporter'),
			'Tests that getExporter with "mysqli" param returns an instance of MysqliExporter.'
		);

		try
		{
			$object->getExporter('mariadb');
		}
		catch (\RuntimeException $e)
		{
			$this->assertThat(
				$e->getMessage(),
				$this->equalTo('Database Exporter not found.'),
				'Tests that getExporter with "mariadb" param throws an exception due to a class not existing.'
			);
		}

		$exporter = $object->getExporter('mysqli', static::$driver);

		$this->assertThat(
			TestHelper::getValue($exporter, 'db'),
			$this->isInstanceOf('\\Joomla\\Database\\Sqlite\\SqliteDriver'),
			'Tests that getExporter with the test database driver returns an instance of SqliteDriver.'
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseFactory::getImporter method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetImporter()
	{
		$object = static::$instance;

		$this->assertThat(
			$object->getImporter('mysqli'),
			$this->isInstanceOf('\\Joomla\\Database\\Mysqli\\MysqliImporter'),
			'Tests that getImporter with "mysqli" param returns an instance of MysqliImporter.'
		);

		try
		{
			$object->getImporter('mariadb');
		}
		catch (\RuntimeException $e)
		{
			$this->assertThat(
				$e->getMessage(),
				$this->equalTo('Database importer not found.'),
				'Tests that getImporter with "mariadb" param throws an exception due to a class not existing.'
			);
		}

		$importer = $object->getImporter('mysqli', static::$driver);

		$this->assertThat(
			TestHelper::getValue($importer, 'db'),
			$this->isInstanceOf('\\Joomla\\Database\\Sqlite\\SqliteDriver'),
			'Tests that getImporter with the test database driver returns an instance of SqliteDriver.'
		);
	}

	/**
	 * Test for the Joomla\Database\DatabaseFactory::getQuery method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetQuery()
	{
		$object = static::$instance;

		$this->assertThat(
			$object->getQuery('sqlite', static::$driver),
			$this->isInstanceOf('\\Joomla\\Database\\Sqlite\\SqliteQuery'),
			'Tests that getQuery with the test database driver and "sqlite" name returns an instance of SqliteQuery.'
		);

		try
		{
			$object->getQuery('mariadb', static::$driver);
		}
		catch (\RuntimeException $e)
		{
			$this->assertThat(
				$e->getMessage(),
				$this->equalTo('Database Query class not found'),
				'Tests that getQuery with "mariadb" param throws an exception due to a class not existing.'
			);
		}
	}
}
