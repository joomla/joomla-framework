<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests\Mock;

use Joomla\Test\TestHelper;

/**
 * Class to mock JDatabaseDriver.
 *
 * @since  1.0
 */
class Driver
{
	/**
	 * A query string or object.
	 *
	 * @var    mixed
	 * @since  1.0
	 */
	public static $lastQuery = null;

	/**
	 * Creates and instance of the mock JDatabase object.
	 *
	 * @param   \PHPUnit_Framework_TestCase  $test        A test object.
	 * @param   string                       $nullDate    A null date string for the driver.
	 * @param   string                       $dateFormat  A date format for the driver.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public static function create(\PHPUnit_Framework_TestCase $test, $nullDate = '0000-00-00 00:00:00', $dateFormat = 'Y-m-d H:i:s')
	{
		// Collect all the relevant methods in JDatabase.
		$methods = array(
			'connect',
			'connected',
			'disconnect',
			'dropTable',
			'escape',
			'execute',
			'fetchArray',
			'fetchAssoc',
			'fetchObject',
			'freeResult',
			'getAffectedRows',
			'getCollation',
			'getConnectors',
			'getDateFormat',
			'getInstance',
			'getLog',
			'getNullDate',
			'getNumRows',
			'getPrefix',
			'getQuery',
			'getTableColumns',
			'getTableCreate',
			'getTableKeys',
			'getTableList',
			'getUtfSupport',
			'getVersion',
			'insertId',
			'insertObject',
			'loadAssoc',
			'loadAssocList',
			'loadColumn',
			'loadObject',
			'loadObjectList',
			'loadResult',
			'loadRow',
			'loadRowList',
			'lockTable',
			'query',
			'quote',
			'quoteName',
			'renameTable',
			'replacePrefix',
			'select',
			'setQuery',
			'setUTF',
			'splitSql',
			'test',
			'isSupported',
			'transactionCommit',
			'transactionRollback',
			'transactionStart',
			'unlockTables',
			'updateObject',
		);

		// Create the mock.
		$mockObject = $test->getMock(
			'\Joomla\Database\DatabaseDriver',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		// Mock selected methods.
		TestHelper::assignMockReturns(
			$mockObject,
			$test,
			array(
				'getNullDate' => $nullDate,
				'getDateFormat' => $dateFormat
			)
		);

		TestHelper::assignMockCallbacks(
			$mockObject,
			$test,
			array(
				'escape' => array((is_callable(array($test, 'mockEscape')) ? $test : __CLASS__), 'mockEscape'),
				'getQuery' => array((is_callable(array($test, 'mockGetQuery')) ? $test : __CLASS__), 'mockGetQuery'),
				'quote' => array((is_callable(array($test, 'mockQuote')) ? $test : __CLASS__), 'mockQuote'),
				'quoteName' => array((is_callable(array($test, 'mockQuoteName')) ? $test : __CLASS__), 'mockQuoteName'),
				'setQuery' => array((is_callable(array($test, 'mockSetQuery')) ? $test : __CLASS__), 'mockSetQuery'),
			)
		);

		return $mockObject;
	}

	/**
	 * Callback for the dbo escape method.
	 *
	 * @param   string  $text  The input text.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function mockEscape($text)
	{
		return "_{$text}_";
	}

	/**
	 * Callback for the dbo setQuery method.
	 *
	 * @param   boolean  $new  True to get a new query, false to get the last query.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function mockGetQuery($new = false)
	{
		if ($new)
		{
			return new \Joomla\Database\Tests\Mock\Query;
		}
		else
		{
			return self::$lastQuery;
		}
	}

	/**
	 * Mocking the quote method.
	 *
	 * @param   string   $value   The value to be quoted.
	 * @param   boolean  $escape  Optional parameter to provide extra escaping.
	 *
	 * @return  string  The value passed wrapped in MySQL quotes.
	 *
	 * @since   1.0
	 */
	public static function mockQuote($value, $escape = true)
	{
		if (is_array($value))
		{
			foreach ($value as $k => $v)
			{
				$value[$k] = self::mockQuote($v, $escape);
			}

			return $value;
		}

		return '\'' . ($escape ? self::mockEscape($value) : $value) . '\'';
	}

	/**
	 * Mock quoteName method.
	 *
	 * @param   string  $value  The value to be quoted.
	 *
	 * @return  string  The value passed wrapped in MySQL quotes.
	 *
	 * @since   1.0
	 */
	public static function mockQuoteName($value)
	{
		return "`$value`";
	}

	/**
	 * Callback for the dbo setQuery method.
	 *
	 * @param   string  $query  The query.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function mockSetQuery($query)
	{
		self::$lastQuery = $query;
	}
}
