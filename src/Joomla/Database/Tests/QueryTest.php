<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Test\TestHelper;

/**
 * Test class for \Joomla\Database\DatabaseQuery.
 *
 * @since  1.0
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * A mock of the Driver object for testing purposes.
	 *
	 * @var    \Joomla\Database\DatabaseDriver
	 * @since  1.0
	 */
	protected $dbo;

	/**
	 * The instance of the object to test.
	 *
	 * @var    \Joomla\Database\DatabaseQuery
	 * @since  1.0
	 */
	private $instance;

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

		$this->dbo = Mock\Driver::create($this);

		$this->instance = new Mock\Query($this->dbo);
	}

	/**
	 * Data for the testNullDate test.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedNullDateTest()
	{
		return array(
			// @todo quoted, expected
			array(true, "'_0000-00-00 00:00:00_'"),
			array(false, "0000-00-00 00:00:00"),
		);
	}

	/**
	 * Data for the testNullDate test.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedQuoteTest()
	{
		return array(
			// Text, escaped, expected
			array('text', false, "'text'"),
			array('text', true, "'_text_'"),
			array(array('text1', 'text2'), false, array("'text1'", "'text2'")),
			array(array('text1', 'text2'), true, array("'_text1_'", "'_text2_'")),
		);
	}

	/**
	 * Test for the \Joomla\Database\DatabaseQuery::__call method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__call
	 * @since   1.0
	 */
	public function test__call()
	{
		$this->assertThat(
			$this->instance->e('foo'),
			$this->equalTo($this->instance->escape('foo')),
			'Tests the e alias of escape.'
		);

		$this->assertThat(
			$this->instance->q('foo'),
			$this->equalTo($this->instance->quote('foo')),
			'Tests the q alias of quote.'
		);

		$this->assertThat(
			$this->instance->qn('foo'),
			$this->equalTo($this->instance->quoteName('foo')),
			'Tests the qn alias of quoteName.'
		);

		$this->assertThat(
			$this->instance->foo(),
			$this->isNull(),
			'Tests for an unknown method.'
		);
	}

	/**
	 * Test for the \Joomla\Database\DatabaseQuery::__get method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__get
	 * @since   1.0
	 */
	public function test__get()
	{
		$this->instance->select('*');
		$this->assertEquals('select', TestHelper::getValue($this->instance, 'type'));
	}

	/**
	 * Test for FROM clause with subquery.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringFrom_subquery()
	{
		$subq = $this->dbo->getQuery(true);
		$subq->select('col2')->from('table')->where('a=1');

		$this->instance->select('col')->from($subq, 'alias');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(
				PHP_EOL . "SELECT col" . PHP_EOL .
				"FROM ( " . PHP_EOL . "SELECT col2" . PHP_EOL . "FROM table" . PHP_EOL . "WHERE a=1 ) AS `alias`"
			)
		);
	}

	/**
	 * Test for INSERT INTO clause with subquery.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringInsert_subquery()
	{
		$subq = $this->dbo->getQuery(true);
		$subq->select('col2')->where('a=1');

		$this->instance->insert('table')->columns('col')->values($subq);

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "INSERT INTO table" . PHP_EOL . "(col)" . PHP_EOL . "(" . PHP_EOL . "SELECT col2" . PHP_EOL . "WHERE a=1)")
		);

		$this->instance->clear();
		$this->instance->insert('table')->columns('col')->values('3');
		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "INSERT INTO table" . PHP_EOL . "(col) VALUES " . PHP_EOL . "(3)")
		);
	}

	/**
	 * Test for year extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringYear()
	{
		$this->instance->select($this->instance->year($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT YEAR(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for month extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringMonth()
	{
		$this->instance->select($this->instance->month($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT MONTH(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for day extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringDay()
	{
		$this->instance->select($this->instance->day($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT DAY(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for hour extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringHour()
	{
		$this->instance->select($this->instance->hour($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT HOUR(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for minute extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringMinute()
	{
		$this->instance->select($this->instance->minute($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT MINUTE(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for seconds extraction from date.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringSecond()
	{
		$this->instance->select($this->instance->second($this->instance->quoteName('col')))->from('table');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(PHP_EOL . "SELECT SECOND(`col`)" . PHP_EOL . "FROM table")
		);
	}

	/**
	 * Test for the \Joomla\Database\DatabaseQuery::__string method for a 'select' case.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringSelect()
	{
		$this->instance->select('a.id')
			->from('a')
			->innerJoin('b ON b.id = a.id')
			->where('b.id = 1')
			->group('a.id')
			->having('COUNT(a.id) > 3')
			->order('a.id');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(
				PHP_EOL . "SELECT a.id" .
					PHP_EOL . "FROM a" .
					PHP_EOL . "INNER JOIN b ON b.id = a.id" .
					PHP_EOL . "WHERE b.id = 1" .
					PHP_EOL . "GROUP BY a.id" .
					PHP_EOL . "HAVING COUNT(a.id) > 3" .
					PHP_EOL . "ORDER BY a.id"
			),
			'Tests for correct rendering.'
		);
	}

	/**
	 * Test for the \Joomla\Database\DatabaseQuery::__string method for a 'update' case.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__toStringUpdate()
	{
		$this->instance->update('#__foo AS a')
			->join('INNER', 'b ON b.id = a.id')
			->set('a.id = 2')
			->where('b.id = 1');

		$this->assertThat(
			(string) $this->instance,
			$this->equalTo(
				PHP_EOL . "UPDATE #__foo AS a" .
					PHP_EOL . "INNER JOIN b ON b.id = a.id" .
					PHP_EOL . "SET a.id = 2" .
					PHP_EOL . "WHERE b.id = 1"
			),
			'Tests for correct rendering.'
		);
	}

	/**
	 * Tests the union element of __toString.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__toString
	 * @since   1.0
	 */
	public function test__toStringUnion()
	{
		$this->markTestIncomplete('This test does not work!');
		$this->instance->select('*')
			->union('SELECT id FROM a');

		$this->assertEquals("UNION (SELECT id FROM a)", trim($this->instance));
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::call method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::call
	 * @since   1.0
	 */
	public function testCall()
	{
		$this->assertSame($this->instance, $this->instance->call('foo'), 'Checks chaining');
		$this->instance->call('bar');
		$this->assertEquals('CALL foo,bar', trim(TestHelper::getValue($this->instance, 'call')), 'Checks method by rendering.');
	}

	/**
	 * Tests the call property in  method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__toString
	 * @since   1.0
	 */
	public function testCall__toString()
	{
		$this->assertEquals('CALL foo', trim($this->instance->call('foo')), 'Checks method by rendering.');
	}

	/**
	 * Test for the castAsChar method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::castAsChar
	 * @since   1.0
	 */
	public function testCastAsChar()
	{
		$this->assertThat(
			$this->instance->castAsChar('123'),
			$this->equalTo('123'),
			'The default castAsChar behaviour is to return the input.'
		);
	}

	/**
	 * Test for the charLength method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::charLength
	 * @since   1.0
	 */
	public function testCharLength()
	{
		$this->assertThat(
			$this->instance->charLength('a.title'),
			$this->equalTo('CHAR_LENGTH(a.title)')
		);

		$this->assertThat(
			$this->instance->charLength('a.title', '!=', '0'),
			$this->equalTo('CHAR_LENGTH(a.title) != 0')
		);

		$this->assertThat(
			$this->instance->charLength('a.title', 'IS', 'NOT NULL'),
			$this->equalTo('CHAR_LENGTH(a.title) IS NOT NULL')
		);
	}

	/**
	 * Test for the clear method (clearing all types and clauses).
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::clear
	 * @since   1.0
	 */
	public function testClear_all()
	{
		$properties = array(
			'select',
			'delete',
			'update',
			'insert',
			'from',
			'join',
			'set',
			'where',
			'group',
			'having',
			'order',
			'columns',
			'values',
			'union',
			'exec',
			'call',
		);

		// First pass - set the values.
		foreach ($properties as $property)
		{
			TestHelper::setValue($this->instance, $property, $property);
		}

		// Clear the whole query.
		$this->instance->clear();

		// Check that all properties have been cleared
		foreach ($properties as $property)
		{
			$this->assertThat(
				TestHelper::getValue($this->instance, $property),
				$this->equalTo(null)
			);
		}

		// And check that the type has been cleared.
		$this->assertThat(
			TestHelper::getValue($this->instance, 'type'),
			$this->equalTo(null)
		);
	}

	/**
	 * Test for the clear method (clearing each clause).
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::clear
	 * @since   1.0
	 */
	public function testClear_clause()
	{
		$clauses = array(
			'from',
			'join',
			'set',
			'where',
			'group',
			'having',
			'order',
			'columns',
			'values',
			'union',
			'exec',
			'call',
		);

		// Test each clause.
		foreach ($clauses as $clause)
		{
			$q = $this->dbo->getQuery(true);

			// Set the clauses
			foreach ($clauses as $clause2)
			{
				TestHelper::setValue($q, $clause2, $clause2);
			}

			// Clear the clause.
			$q->clear($clause);

			// Check that clause was cleared.
			$this->assertThat(
				TestHelper::getValue($q, $clause),
				$this->equalTo(null)
			);

			// Check the state of the other clauses.
			foreach ($clauses as $clause2)
			{
				if ($clause != $clause2)
				{
					$this->assertThat(
						TestHelper::getValue($q, $clause2),
						$this->equalTo($clause2),
						"Clearing $clause resulted in $clause2 having a value of " . TestHelper::getValue($q, $clause2) . '.'
					);
				}
			}
		}
	}

	/**
	 * Test for the clear method (clearing each query type).
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::clear
	 * @since   1.0
	 */
	public function testClear_type()
	{
		$types = array(
			'select',
			'delete',
			'update',
			'insert',
			'union',
		);

		$clauses = array(
			'from',
			'join',
			'set',
			'where',
			'group',
			'having',
			'order',
			'columns',
			'values',
		);

		// Set the clauses.
		foreach ($clauses as $clause)
		{
			TestHelper::setValue($this->instance, $clause, $clause);
		}

		// Check that all properties have been cleared
		foreach ($types as $type)
		{
			// Set the type.
			TestHelper::setValue($this->instance, $type, $type);

			// Clear the type.
			$this->instance->clear($type);

			// Check the type has been cleared.
			$this->assertThat(
				TestHelper::getValue($this->instance, 'type'),
				$this->equalTo(null)
			);

			$this->assertThat(
				TestHelper::getValue($this->instance, $type),
				$this->equalTo(null)
			);

			// Now check the claues have not been affected.
			foreach ($clauses as $clause)
			{
				$this->assertThat(
					TestHelper::getValue($this->instance, $clause),
					$this->equalTo($clause)
				);
			}
		}
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::columns method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::columns
	 * @since   1.0
	 */
	public function testColumns()
	{
		$this->assertThat(
			$this->instance->columns('foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'columns')),
			$this->equalTo('(foo)'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->columns('bar');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'columns')),
			$this->equalTo('(foo,bar)'),
			'Tests rendered value after second use.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::concatenate method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::concatenate
	 * @since   1.0
	 */
	public function testConcatenate()
	{
		$this->assertThat(
			$this->instance->concatenate(array('foo', 'bar')),
			$this->equalTo('CONCATENATE(foo || bar)'),
			'Tests without separator.'
		);

		$this->assertThat(
			$this->instance->concatenate(array('foo', 'bar'), ' and '),
			$this->equalTo("CONCATENATE(foo || '_ and _' || bar)"),
			'Tests without separator.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::currentTimestamp method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::currentTimestamp
	 * @since   1.0
	 */
	public function testCurrentTimestamp()
	{
		$this->assertThat(
			$this->instance->currentTimestamp(),
			$this->equalTo('CURRENT_TIMESTAMP()')
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::dateFormat method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::dateFormat
	 * @since   1.0
	 */
	public function testDateFormat()
	{
		$this->assertThat(
			$this->instance->dateFormat(),
			$this->equalTo('Y-m-d H:i:s')
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::dateFormat method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             \Joomla\Database\DatabaseQuery::dateFormat
	 * @expectedException  \RuntimeException
	 * @since           1.0
	 */
	public function testDateFormatException()
	{
		// Override the internal database for testing.
		TestHelper::setValue($this->instance, 'db', new \stdClass);

		$this->instance->dateFormat();
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::delete method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::delete
	 * @since   1.0
	 */
	public function testDelete()
	{
		$this->assertThat(
			$this->instance->delete('#__foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			TestHelper::getValue($this->instance, 'type'),
			$this->equalTo('delete'),
			'Tests the type property is set correctly.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'delete')),
			$this->equalTo('DELETE'),
			'Tests the delete element is set correctly.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'from')),
			$this->equalTo('FROM #__foo'),
			'Tests the from element is set correctly.'
		);
	}

	/**
	 * Tests the delete property in \Joomla\Database\DatabaseQuery::__toString method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__toString
	 * @since   1.0
	 */
	public function testDelete__toString()
	{
		$this->instance->delete('#__foo')
			->innerJoin('join')
			->where('bar=1');

		$this->assertEquals(
			implode(PHP_EOL, array('DELETE ', 'FROM #__foo', 'INNER JOIN join', 'WHERE bar=1')),
			trim($this->instance)
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::dump method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::dump
	 * @since   1.0
	 */
	public function testDump()
	{
		$this->instance->select('*')
			->from('#__foo');

		$this->assertThat(
			$this->instance->dump(),
			$this->equalTo(
				'<pre class="jdatabasequery">' .
					PHP_EOL . "SELECT *" . PHP_EOL . "FROM foo" .
					'</pre>'
			),
			'Tests that the dump method replaces the prefix correctly.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::escape method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::escape
	 * @since   1.0
	 */
	public function testEscape()
	{
		$this->assertThat(
			$this->instance->escape('foo'),
			$this->equalTo('_foo_')
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::escape method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             \Joomla\Database\DatabaseQuery::escape
	 * @expectedException  \RuntimeException
	 * @since           1.0
	 */
	public function testEscapeException()
	{
		// Override the internal database for testing.
		TestHelper::setValue($this->instance, 'db', new \stdClass);

		$this->instance->escape('foo');
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::exec method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::exec
	 * @since   1.0
	 */
	public function testExec()
	{
		$this->assertSame($this->instance, $this->instance->exec('a.*'), 'Checks chaining');
		$this->instance->exec('b.*');
		$this->assertEquals('EXEC a.*,b.*', trim(TestHelper::getValue($this->instance, 'exec')), 'Checks method by rendering.');
	}

	/**
	 * Tests the exec property in \Joomla\Database\DatabaseQuery::__toString method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__toString
	 * @since   1.0
	 */
	public function testExec__toString()
	{
		$this->assertEquals('EXEC a.*', trim($this->instance->exec('a.*')));
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::from method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::from
	 * @since   1.0
	 */
	public function testFrom()
	{
		$this->assertThat(
			$this->instance->from('#__foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'from')),
			$this->equalTo('FROM #__foo'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->from('#__bar');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'from')),
			$this->equalTo('FROM #__foo,#__bar'),
			'Tests rendered value after second use.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::group method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::group
	 * @since   1.0
	 */
	public function testGroup()
	{
		$this->assertThat(
			$this->instance->group('foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'group')),
			$this->equalTo('GROUP BY foo'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->group('bar');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'group')),
			$this->equalTo('GROUP BY foo,bar'),
			'Tests rendered value after second use.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::having method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::having
	 * @since   1.0
	 */
	public function testHaving()
	{
		$this->assertThat(
			$this->instance->having('COUNT(foo) > 1'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'having')),
			$this->equalTo('HAVING COUNT(foo) > 1'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->having('COUNT(bar) > 2');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'having')),
			$this->equalTo('HAVING COUNT(foo) > 1 AND COUNT(bar) > 2'),
			'Tests rendered value after second use.'
		);

		// Reset the field to test the glue.
		TestHelper::setValue($this->instance, 'having', null);
		$this->instance->having('COUNT(foo) > 1', 'OR');
		$this->instance->having('COUNT(bar) > 2');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'having')),
			$this->equalTo('HAVING COUNT(foo) > 1 OR COUNT(bar) > 2'),
			'Tests rendered value with OR glue.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::innerJoin method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::innerJoin
	 * @since   1.0
	 */
	public function testInnerJoin()
	{
		$q1 = $this->dbo->getQuery(true);
		$q2 = $this->dbo->getQuery(true);
		$condition = 'foo ON foo.id = bar.id';

		$this->assertThat(
			$q1->innerJoin($condition),
			$this->identicalTo($q1),
			'Tests chaining.'
		);

		$q2->join('INNER', $condition);

		$this->assertThat(
			TestHelper::getValue($q1, 'join'),
			$this->equalTo(TestHelper::getValue($q2, 'join')),
			'Tests that innerJoin is an alias for join.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::insert method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::insert
	 * @since   1.0
	 */
	public function testInsert()
	{
		$this->assertThat(
			$this->instance->insert('#__foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			TestHelper::getValue($this->instance, 'type'),
			$this->equalTo('insert'),
			'Tests the type property is set correctly.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'insert')),
			$this->equalTo('INSERT INTO #__foo'),
			'Tests the delete element is set correctly.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::join method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::join
	 * @since   1.0
	 */
	public function testJoin()
	{
		$this->assertThat(
			$this->instance->join('INNER', 'foo ON foo.id = bar.id'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$join = TestHelper::getValue($this->instance, 'join');

		$this->assertThat(
			trim($join[0]),
			$this->equalTo('INNER JOIN foo ON foo.id = bar.id'),
			'Tests that first join renders correctly.'
		);

		$this->instance->join('OUTER', 'goo ON goo.id = car.id');

		$join = TestHelper::getValue($this->instance, 'join');

		$this->assertThat(
			trim($join[1]),
			$this->equalTo('OUTER JOIN goo ON goo.id = car.id'),
			'Tests that second join renders correctly.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::leftJoin method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::leftJoin
	 * @since   1.0
	 */
	public function testLeftJoin()
	{
		$q1 = $this->dbo->getQuery(true);
		$q2 = $this->dbo->getQuery(true);
		$condition = 'foo ON foo.id = bar.id';

		$this->assertThat(
			$q1->leftJoin($condition),
			$this->identicalTo($q1),
			'Tests chaining.'
		);

		$q2->join('LEFT', $condition);

		$this->assertThat(
			TestHelper::getValue($q1, 'join'),
			$this->equalTo(TestHelper::getValue($q2, 'join')),
			'Tests that leftJoin is an alias for join.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::length method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::length
	 * @since   1.0
	 */
	public function testLength()
	{
		$this->assertThat(
			trim($this->instance->length('foo')),
			$this->equalTo('LENGTH(foo)'),
			'Tests method renders correctly.'
		);
	}

	/**
	 * Tests the quoteName method.
	 *
	 * @param   boolean  $quoted    The value of the quoted argument.
	 * @param   string   $expected  The expected result.
	 *
	 * @return  void
	 *
	 * @covers        \Joomla\Database\DatabaseQuery::nullDate
	 * @dataProvider  seedNullDateTest
	 * @since      1.0
	 */
	public function testNullDate($quoted, $expected)
	{
		$this->assertThat(
			$this->instance->nullDate($quoted),
			$this->equalTo($expected),
			'The nullDate method should be a proxy for the JDatabase::getNullDate method.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::nullDate method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             \Joomla\Database\DatabaseQuery::nullDate
	 * @expectedException  \RuntimeException
	 * @since           1.0
	 */
	public function testNullDateException()
	{
		// Override the internal database for testing.
		TestHelper::setValue($this->instance, 'db', new \stdClass);

		$this->instance->nullDate();
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::order method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::order
	 * @since   1.0
	 */
	public function testOrder()
	{
		$this->assertThat(
			$this->instance->order('foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'order')),
			$this->equalTo('ORDER BY foo'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->order('bar');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'order')),
			$this->equalTo('ORDER BY foo,bar'),
			'Tests rendered value after second use.'
		);

		$this->instance->order(
			array(
				'goo', 'car'
			)
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'order')),
			$this->equalTo('ORDER BY foo,bar,goo,car'),
			'Tests array input.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::outerJoin method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::outerJoin
	 * @since   1.0
	 */
	public function testOuterJoin()
	{
		$q1 = $this->dbo->getQuery(true);
		$q2 = $this->dbo->getQuery(true);
		$condition = 'foo ON foo.id = bar.id';

		$this->assertThat(
			$q1->outerJoin($condition),
			$this->identicalTo($q1),
			'Tests chaining.'
		);

		$q2->join('OUTER', $condition);

		$this->assertThat(
			TestHelper::getValue($q1, 'join'),
			$this->equalTo(TestHelper::getValue($q2, 'join')),
			'Tests that outerJoin is an alias for join.'
		);
	}

	/**
	 * Tests the quote method.
	 *
	 * @param   boolean  $text      The value to be quoted.
	 * @param   boolean  $escape    True to escape the string, false to leave it unchanged.
	 * @param   string   $expected  The expected result.
	 *
	 * @return  void
	 *
	 * @covers        \Joomla\Database\DatabaseQuery::quote
	 * @since      1.0
	 * @dataProvider  seedQuoteTest
	 */
	public function testQuote($text, $escape, $expected)
	{
		$this->assertEquals($expected, $this->instance->quote($text, $escape));
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::nullDate method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             \Joomla\Database\DatabaseQuery::quote
	 * @expectedException  \RuntimeException
	 * @since           1.0
	 */
	public function testQuoteException()
	{
		// Override the internal database for testing.
		TestHelper::setValue($this->instance, 'db', new \stdClass);

		$this->instance->quote('foo');
	}

	/**
	 * Tests the quoteName method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::quoteName
	 * @since   1.0
	 */
	public function testQuoteName()
	{
		$this->assertThat(
			$this->instance->quoteName("test"),
			$this->equalTo("`test`"),
			'The quoteName method should be a proxy for the JDatabase::escape method.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::quoteName method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             \Joomla\Database\DatabaseQuery::quoteName
	 * @expectedException  \RuntimeException
	 * @since           1.0
	 */
	public function testQuoteNameException()
	{
		// Override the internal database for testing.
		TestHelper::setValue($this->instance, 'db', new \stdClass);

		$this->instance->quoteName('foo');
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::rightJoin method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::rightJoin
	 * @since   1.0
	 */
	public function testRightJoin()
	{
		$q1 = $this->dbo->getQuery(true);
		$q2 = $this->dbo->getQuery(true);
		$condition = 'foo ON foo.id = bar.id';

		$this->assertThat(
			$q1->rightJoin($condition),
			$this->identicalTo($q1),
			'Tests chaining.'
		);

		$q2->join('RIGHT', $condition);

		$this->assertThat(
			TestHelper::getValue($q1, 'join'),
			$this->equalTo(TestHelper::getValue($q2, 'join')),
			'Tests that rightJoin is an alias for join.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::select method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::select
	 * @since   1.0
	 */
	public function testSelect()
	{
		$this->assertThat(
			$this->instance->select('foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			TestHelper::getValue($this->instance, 'type'),
			$this->equalTo('select'),
			'Tests the type property is set correctly.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'select')),
			$this->equalTo('SELECT foo'),
			'Tests the select element is set correctly.'
		);

		$this->instance->select('bar');

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'select')),
			$this->equalTo('SELECT foo,bar'),
			'Tests the second use appends correctly.'
		);

		$this->instance->select(
			array(
				'goo', 'car'
			)
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'select')),
			$this->equalTo('SELECT foo,bar,goo,car'),
			'Tests the second use appends correctly.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::set method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->assertThat(
			$this->instance->set('foo = 1'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'set')),
			$this->identicalTo('SET foo = 1'),
			'Tests set with a string.'
		);

		$this->instance->set('bar = 2');
		$this->assertEquals("SET foo = 1" . PHP_EOL . "\t, bar = 2", trim(TestHelper::getValue($this->instance, 'set')), 'Tests appending with set().');

		// Clear the set.
		TestHelper::setValue($this->instance, 'set', null);
		$this->instance->set(
			array(
				'foo = 1',
				'bar = 2',
			)
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'set')),
			$this->identicalTo("SET foo = 1" . PHP_EOL . "\t, bar = 2"),
			'Tests set with an array.'
		);

		// Clear the set.
		TestHelper::setValue($this->instance, 'set', null);
		$this->instance->set(
			array(
				'foo = 1',
				'bar = 2',
			),
			';'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'set')),
			$this->identicalTo("SET foo = 1" . PHP_EOL . "\t; bar = 2"),
			'Tests set with an array and glue.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::setQuery method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::setQuery
	 * @since   1.0
	 */
	public function testSetQuery()
	{
		$this->assertSame($this->instance, $this->instance->setQuery('Some SQL'), 'Check chaining.');
		$this->assertAttributeEquals('Some SQL', 'sql', $this->instance, 'Checks the property was set correctly.');
		$this->assertEquals('Some SQL', (string) $this->instance, 'Checks the rendering of the raw SQL.');
	}

	/**
	 * Tests rendering coupled with the \Joomla\Database\DatabaseQuery::setQuery method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::__toString
	 * @since   1.0
	 */
	public function testSetQuery__toString()
	{
		$this->assertEquals('Some SQL', trim($this->instance->setQuery('Some SQL')));
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::update method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::update
	 * @since   1.0
	 */
	public function testUpdate()
	{
		$this->assertThat(
			$this->instance->update('#__foo'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			TestHelper::getValue($this->instance, 'type'),
			$this->equalTo('update'),
			'Tests the type property is set correctly.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'update')),
			$this->equalTo('UPDATE #__foo'),
			'Tests the update element is set correctly.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::values method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::values
	 * @since   1.0
	 */
	public function testValues()
	{
		$this->assertThat(
			$this->instance->values('1,2,3'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'values')),
			$this->equalTo('(1,2,3)'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->values(
			array(
				'4,5,6',
				'7,8,9',
			)
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'values')),
			$this->equalTo('(1,2,3),(4,5,6),(7,8,9)'),
			'Tests rendered value after second use and array input.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::where method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::where
	 * @since   1.0
	 */
	public function testWhere()
	{
		$this->assertThat(
			$this->instance->where('foo = 1'),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'where')),
			$this->equalTo('WHERE foo = 1'),
			'Tests rendered value.'
		);

		// Add another column.
		$this->instance->where(
			array(
				'bar = 2',
				'goo = 3',
			)
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'where')),
			$this->equalTo('WHERE foo = 1 AND bar = 2 AND goo = 3'),
			'Tests rendered value after second use and array input.'
		);

		// Clear the where
		TestHelper::setValue($this->instance, 'where', null);
		$this->instance->where(
			array(
				'bar = 2',
				'goo = 3',
			),
			'OR'
		);

		$this->assertThat(
			trim(TestHelper::getValue($this->instance, 'where')),
			$this->equalTo('WHERE bar = 2 OR goo = 3'),
			'Tests rendered value with glue.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::__clone method properly clones an array.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__clone_array()
	{
		$baseElement = $this->dbo->getQuery(true);

		$baseElement->testArray = array();

		$cloneElement = clone($baseElement);

		$baseElement->testArray[] = 'test';

		$this->assertThat(
			TestHelper::getValue($baseElement, 'db'),
			$this->identicalTo(
				TestHelper::getValue($cloneElement, 'db')
			),
			'The cloned $db variable should be identical after cloning.'
		);

		$this->assertFalse($baseElement === $cloneElement);
		$this->assertTrue(count($cloneElement->testArray) == 0);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::__clone method properly clones an object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__clone_object()
	{
		$baseElement = $this->dbo->getQuery(true);

		$baseElement->testObject = new \stdClass;

		$cloneElement = clone($baseElement);

		$this->assertThat(
			TestHelper::getValue($baseElement, 'db'),
			$this->identicalTo(
				TestHelper::getValue($cloneElement, 'db')
			),
			'The cloned $db variable should be identical after cloning.'
		);

		$this->assertFalse($baseElement === $cloneElement);
		$this->assertFalse($baseElement->testObject === $cloneElement->testObject);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionChain()
	{
		$this->assertThat(
			$this->instance->union($this->instance),
			$this->identicalTo($this->instance),
			'Tests chaining.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionClear()
	{
		TestHelper::setValue($this->instance, 'union', null);
		TestHelper::setValue($this->instance, 'order', null);
		$this->instance->order('bar');
		$this->instance->union('SELECT name FROM foo');
		$this->assertThat(
			TestHelper::getValue($this->instance, 'order'),
			$this->equalTo(null),
			'Tests that ORDER BY is cleared with union.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionUnion()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union('SELECT name FROM foo');
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION (SELECT name FROM foo)"),
			'Tests rendered query with union.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionDistinctString()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union('SELECT name FROM foo', 'distinct');
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION DISTINCT (SELECT name FROM foo)"),
			'Tests rendered query with union distinct as a string.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionDistinctTrue()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union('SELECT name FROM foo', true);
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION DISTINCT (SELECT name FROM foo)"),
			'Tests rendered query with union distinct true.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionDistinctFalse()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union('SELECT name FROM foo', false);
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION (SELECT name FROM foo)"),
			'Tests rendered query with union distinct false.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionArray()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union(array('SELECT name FROM foo', 'SELECT name FROM bar'));
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION (SELECT name FROM foo)" . PHP_EOL . "UNION (SELECT name FROM bar)"),
			'Tests rendered query with two unions as an array.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::union method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::union
	 * @since   1.0
	 */
	public function testUnionTwo()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->union('SELECT name FROM foo');
		$this->instance->union('SELECT name FROM bar');
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION (SELECT name FROM foo)" . PHP_EOL . "UNION (SELECT name FROM bar)"),
			'Tests rendered query with two unions sequentially.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::unionDistinct method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::unionDistinct
	 * @since   1.0
	 */
	public function testUnionDistinct()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->unionDistinct('SELECT name FROM foo');
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			trim($teststring),
			$this->equalTo("UNION DISTINCT (SELECT name FROM foo)"),
			'Tests rendered query with unionDistinct.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::unionDistinct method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::unionDistinct
	 * @since   1.0
	 */
	public function testUnionDistinctArray()
	{
		TestHelper::setValue($this->instance, 'union', null);
		$this->instance->unionDistinct(array('SELECT name FROM foo', 'SELECT name FROM bar'));
		$teststring = (string) TestHelper::getValue($this->instance, 'union');
		$this->assertThat(
			$teststring,
			$this->equalTo(PHP_EOL . "UNION DISTINCT (SELECT name FROM foo)" . PHP_EOL . "UNION DISTINCT (SELECT name FROM bar)"),
			'Tests rendered query with two unions distinct.'
		);
	}

	/**
	 * Tests the \Joomla\Database\DatabaseQuery::format method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Database\DatabaseQuery::format
	 * @since   1.0
	 */
	public function testFormat()
	{
		$result = $this->instance->format('SELECT %n FROM %n WHERE %n = %a', 'foo', '#__bar', 'id', 10);
		$expected = 'SELECT ' . $this->instance->qn('foo') . ' FROM ' . $this->instance->qn('#__bar') .
			' WHERE ' . $this->instance->qn('id') . ' = 10';
		$this->assertThat(
			$result,
			$this->equalTo($expected),
			'Line: ' . __LINE__ . '.'
		);

		$result = $this->instance->format('SELECT %n FROM %n WHERE %n = %t OR %3$n = %Z', 'id', '#__foo', 'date');
		$expected = 'SELECT ' . $this->instance->qn('id') . ' FROM ' . $this->instance->qn('#__foo') .
			' WHERE ' . $this->instance->qn('date') . ' = ' . $this->instance->currentTimestamp() .
			' OR ' . $this->instance->qn('date') . ' = ' . $this->instance->nullDate(true);
		$this->assertThat(
			$result,
			$this->equalTo($expected),
			'Line: ' . __LINE__ . '.'
		);
	}
}
