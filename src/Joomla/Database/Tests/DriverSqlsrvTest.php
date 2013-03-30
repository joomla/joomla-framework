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
			array("'%_abc123", false, '\\\'%_abc123'),
			array("'%_abc123", true, '\\\'\\%\_abc123'),
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
			$this->isInstanceOf('JDatabaseDriverSqlsrv'),
			'The table is dropped if present.'
		);
	}

	/**
	 * Tests the escape method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testEscape().
	 */
	public function testEscape()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
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
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
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
	 * @todo    Implement testLoadAssoc().
	 */
	public function testLoadAssoc()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadAssocList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadAssocList().
	 */
	public function testLoadAssocList()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadColumn method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadColumn().
	 */
	public function testLoadColumn()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadObject method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadObject().
	 */
	public function testLoadObject()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadObjectList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadObjectList().
	 */
	public function testLoadObjectList()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadResult method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadResult().
	 */
	public function testLoadResult()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadRow method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadRow().
	 */
	public function testLoadRow()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the loadRowList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testLoadRowList().
	 */
	public function testLoadRowList()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
	}

	/**
	 * Tests the execute method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @todo    Implement testExecute().
	 */
	public function testExecute()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped('PHPUnit does not support testing queries on SQL Server.');
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
