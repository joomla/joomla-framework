<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\Driver\Sqlsrv;

/**
 * Test class for \Joomla\Database\Driver\Sqlsrv.
 *
 * @since  1.0
 */
class DriverSqlsrvTest extends DatabaseSqlsrvCase
{
	/**
	 * Data for the testEscape test.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function dataTestEscape()
	{
		return array(
			array("'%_abc123", false, "''%_abc123"),
			array("'%_abc123", true, "''%[_]abc123"),
		);
	}

	/**
	 * Tests the destructor
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement test__destruct().
	 */
	public function test__destruct()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the connected method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testConnected().
	 */
	public function testConnected()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the dropTable method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDropTable()
	{
		$this->assertThat(
			self::$driver->dropTable('#__bar', true),
			$this->isInstanceOf('\\Joomla\\Database\\Driver\\Sqlsrv'),
			'The table is dropped if present.'
		);
	}

	/**
	 * Tests the escape method.
	 *
	 * @param   string   $text      The string to be escaped.
	 * @param   boolean  $extra     Optional parameter to provide extra escaping.
	 * @param   string   $expected  The expected result.
	 *
	 * @return  void
	 *
	 * @dataProvider  dataTestEscape
	 * @since         1.0
	 */
	public function testEscape($text, $extra, $expected)
	{
		$this->assertThat(
			self::$driver->escape($text, $extra),
			$this->equalTo($expected),
			'The string was not escaped properly'
		);
	}

	/**
	 * Tests the getAffectedRows method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetAffectedRows().
	 */
	public function testGetAffectedRows()
	{
		$query = self::$driver->getQuery(true);
		$query->delete();
		$query->from('jos_dbtest');
		self::$driver->setQuery($query);

		self::$driver->execute();

		$this->assertThat(self::$driver->getAffectedRows(), $this->equalTo(4), __LINE__);
	}

	/**
	 * Tests the getCollation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetCollation().
	 */
	public function testGetCollation()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getExporter method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetExporter().
	 */
	public function testGetExporter()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('Implement this test when the exporter is added.');
	}

	/**
	 * Tests the getImporter method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetImporter().
	 */
	public function testGetImporter()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('Implement this test when the importer is added.');
	}

	/**
	 * Tests the getNumRows method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetNumRows().
	 */
	public function testGetNumRows()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getTableCreate method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetTableCreate()
	{
		$this->assertThat(
			self::$driver->getTableCreate('#__dbtest'),
			$this->isType('string'),
			'A blank string is returned since this is not supported on SQL Server.'
		);
	}

	/**
	 * Tests the getTableColumns method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testGetTableColumns().
	 */
	public function testGetTableColumns()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getTableKeys method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetTableKeys()
	{
		$this->assertThat(
			self::$driver->getTableKeys('#__dbtest'),
			$this->isType('array'),
			'The list of keys for the table is returned in an array.'
		);
	}

	/**
	 * Tests the getTableList method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetTableList()
	{
		$this->assertThat(
			self::$driver->getTableList(),
			$this->isType('array'),
			'The list of tables for the database is returned in an array.'
		);
	}

	/**
	 * Tests the getVersion method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetVersion()
	{
		$this->assertThat(
			self::$driver->getVersion(),
			$this->isType('string'),
			'Line:' . __LINE__ . ' The getVersion method should return a string containing the driver version.'
		);
	}

	/**
	 * Tests the insertid method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testInsertid().
	 */
	public function testInsertid()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the loadAssoc method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadAssoc()
	{
		$query = self::$driver->getQuery(true);
		$query->select('title');
		$query->from('jos_dbtest');
		self::$driver->setQuery($query);
		$result = self::$driver->loadAssoc();

		$this->assertThat($result, $this->equalTo(array('title' => 'Testing')), __LINE__);
	}

	/**
	 * Tests the loadAssocList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadAssocList()
	{
		$query = self::$driver->getQuery(true);
		$query->select('title');
		$query->from('jos_dbtest');
		self::$driver->setQuery($query);
		$result = self::$driver->loadAssocList();

		$this->assertThat(
			$result,
			$this->equalTo(
				array(
					array('title' => 'Testing'),
					array('title' => 'Testing2'),
					array('title' => 'Testing3'),
					array('title' => 'Testing4')
				)
			),
			__LINE__
		);
	}

	/**
	 * Tests the loadColumn method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadColumn()
	{
		$query = self::$driver->getQuery(true);
		$query->select('title');
		$query->from('jos_dbtest');
		self::$driver->setQuery($query);
		$result = self::$driver->loadColumn();

		$this->assertThat($result, $this->equalTo(array('Testing', 'Testing2', 'Testing3', 'Testing4')), __LINE__);
	}

	/**
	 * Tests the loadObject method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadObject()
	{
		$query = self::$driver->getQuery(true);
		$query->select('*');
		$query->from('jos_dbtest');
		$query->where('description=' . self::$driver->quote('three'));
		self::$driver->setQuery($query);
		$result = self::$driver->loadObject();

		$objCompare = new \stdClass;
		$objCompare->id = 3;
		$objCompare->title = 'Testing3';
		$objCompare->start_date = '1980-04-18 00:00:00.000';
		$objCompare->description = 'three';

		$this->assertThat($result, $this->equalTo($objCompare), __LINE__);
	}

	/**
	 * Tests the loadObjectList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadObjectList()
	{
		$query = self::$driver->getQuery(true);
		$query->select('*');
		$query->from('jos_dbtest');
		$query->order('id');
		self::$driver->setQuery($query);
		$result = self::$driver->loadObjectList();

		$expected = array();

		$objCompare = new \stdClass;
		$objCompare->id = 1;
		$objCompare->title = 'Testing';
		$objCompare->start_date = '1980-04-18 00:00:00.000';
		$objCompare->description = 'one';

		$expected[] = clone $objCompare;

		$objCompare = new \stdClass;
		$objCompare->id = 2;
		$objCompare->title = 'Testing2';
		$objCompare->start_date = '1980-04-18 00:00:00.000';
		$objCompare->description = 'one';

		$expected[] = clone $objCompare;

		$objCompare = new \stdClass;
		$objCompare->id = 3;
		$objCompare->title = 'Testing3';
		$objCompare->start_date = '1980-04-18 00:00:00.000';
		$objCompare->description = 'three';

		$expected[] = clone $objCompare;

		$objCompare = new \stdClass;
		$objCompare->id = 4;
		$objCompare->title = 'Testing4';
		$objCompare->start_date = '1980-04-18 00:00:00.000';
		$objCompare->description = 'four';

		$expected[] = clone $objCompare;

		$this->assertThat($result, $this->equalTo($expected), __LINE__);
	}

	/**
	 * Tests the loadResult method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadResult()
	{
		$query = self::$driver->getQuery(true);
		$query->select('id');
		$query->from('jos_dbtest');
		$query->where('title=' . self::$driver->quote('Testing2'));

		self::$driver->setQuery($query);
		$result = self::$driver->loadResult();

		$this->assertThat($result, $this->equalTo(2), __LINE__);
	}

	/**
	 * Tests the loadRow method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadRow()
	{
		$query = self::$driver->getQuery(true);
		$query->select('*');
		$query->from('jos_dbtest');
		$query->where('description=' . self::$driver->quote('three'));
		self::$driver->setQuery($query);
		$result = self::$driver->loadRow();

		$expected = array(3, 'Testing3', '1980-04-18 00:00:00.000', 'three');

		$this->assertThat($result, $this->equalTo($expected), __LINE__);
	}

	/**
	 * Tests the loadRowList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadRowList()
	{
		$query = self::$driver->getQuery(true);
		$query->select('*');
		$query->from('jos_dbtest');
		$query->where('description=' . self::$driver->quote('one'));
		self::$driver->setQuery($query);
		$result = self::$driver->loadRowList();

		$expected = array(array(1, 'Testing', '1980-04-18 00:00:00.000', 'one'), array(2, 'Testing2', '1980-04-18 00:00:00.000', 'one'));

		$this->assertThat($result, $this->equalTo($expected), __LINE__);
	}

	/**
	 * Tests the execute method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testExecute()
	{
		self::$driver->setQuery("INSERT INTO [jos_dbtest] ([title],[start_date],[description]) VALUES ('testTitle','2013-04-01 00:00:00.000','description')");

		$this->assertNotEquals(self::$driver->execute(), false, __LINE__);
	}

	/**
	 * Tests the select method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testSelect().
	 */
	public function testSelect()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the setUTF method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testSetUTF().
	 */
	public function testSetUTF()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the isSupported method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testIsSupported()
	{
		$this->assertThat(
			\Joomla\Database\Driver\Sqlsrv::isSupported(),
			$this->isTrue(),
			__LINE__
		);
	}

	/**
	 * Tests the updateObject method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testUpdateObject().
	 */
	public function testUpdateObject()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
