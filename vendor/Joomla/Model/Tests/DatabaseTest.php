<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Model\Tests;

use Joomla\Database\Tests\Mock as DatabaseMock;
use Joomla\Test\Helper;

require_once __DIR__ . '/stubs/tdatabase.php';

/**
 * Tests for the Joomla\Model\Database class.
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Model\Database
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Database::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertInstanceOf('Joomla\Database\Driver', $this->instance->getDb());
	}

	/**
	 * Tests the getDb method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Database::getDb
	 * @since   1.0
	 */
	public function testGetDb()
	{
		// Reset the db property to a known value.
		Helper::setValue($this->instance, 'db', 'foo');

		$this->assertEquals('foo', $this->instance->getDb());
	}

	/**
	 * Tests the setDb method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Database::setDb
	 * @since   1.0
	 */
	public function testSetDb()
	{
		$db = DatabaseMock\Driver::create($this);
		$this->instance->setDb($db);

		$this->assertAttributeSame($db, 'db', $this->instance);
	}

	/**
	 * Tests the loadDb method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Database::loadDb
	 * @since   1.0
	 */
	public function testLoadDb()
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

		$this->instance = new DatabaseModel(null, DatabaseMock\Driver::create($this));
	}
}
