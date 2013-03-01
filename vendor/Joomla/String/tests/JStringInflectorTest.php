<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  String
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\String\Inflector;

/**
 * Test for the Inflector class.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  String
 * @link        http://en.wikipedia.org/wiki/English_plural
 * @since       12.1
 */
class InflectorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    Inflector
	 * @since  12.1
	 */
	protected $inflector;

	/**
	 * Method to seed data to testIsCountable.
	 *
	 * @return  array
	 *
	 * @since   12.1
	 */
	public function seedIsCountable()
	{
		return array(
			array('id', true),
			array('title', false),
		);
	}

	/**
	 * Method to seed data to testToPlural.
	 *
	 * @return  array
	 *
	 * @since   12.1
	 */
	public function seedSinglePlural()
	{
		return array(
			// Regular plurals
			array('bus', 'buses'),
			array('notify', 'notifies'),
			array('click', 'clicks'),

			// Almost regular plurals.
			array('photo', 'photos'),
			array('zero', 'zeros'),

			// Irregular identicals
			array('salmon', 'salmon'),

			// Irregular plurals
			array('ox', 'oxen'),
			array('quiz', 'quizes'),
			array('status', 'statuses'),
			array('matrix', 'matrices'),
			array('index', 'indices'),
			array('vertex', 'vertices'),
			array('hive', 'hives'),

			// Ablaut plurals
			array('foot', 'feet'),
			array('goose', 'geese'),
			array('louse', 'lice'),
			array('man', 'men'),
			array('mouse', 'mice'),
			array('tooth', 'teeth'),
			array('woman', 'women'),
		);
	}

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->inflector = Inflector::getInstance(true);
	}

	/**
	 * Method to test Inflector::addRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::addRule
	 */
	public function testAddRule()
	{
		// Case 1
		TestReflection::invoke($this->inflector, 'addRule', '/foo/', 'singular');

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('/foo/', $rules['singular']),
			$this->isTrue(),
			'Checks if the singular rule was added correctly.'
		);

		// Case 2
		TestReflection::invoke($this->inflector, 'addRule', '/bar/', 'plural');

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('/bar/', $rules['plural']),
			$this->isTrue(),
			'Checks if the plural rule was added correctly.'
		);

		// Case 3
		TestReflection::invoke($this->inflector, 'addRule', array('/goo/', '/car/'), 'singular');

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('/goo/', $rules['singular']),
			$this->isTrue(),
			'Checks if an array of rules was added correctly (1).'
		);

		$this->assertThat(
			in_array('/car/', $rules['singular']),
			$this->isTrue(),
			'Checks if an array of rules was added correctly (2).'
		);
	}

	/**
	 * Method to test Inflector::addRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  InvalidArgumentException
	 * @covers  Joomla\String\Inflector::addRule
	 */
	public function testAddRuleException()
	{
		TestReflection::invoke($this->inflector, 'addRule', new stdClass, 'singular');
	}

	/**
	 * Method to test Inflector::getCachedPlural().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::getCachedPlural
	 */
	public function testGetCachedPlural()
	{
		// Reset the cache.
		TestReflection::setValue($this->inflector, 'cache', array('foo' => 'bar'));

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'getCachedPlural', 'bar'),
			$this->isFalse(),
			'Checks for an uncached plural.'
		);

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'getCachedPlural', 'foo'),
			$this->equalTo('bar'),
			'Checks for a cached plural word.'
		);
	}

	/**
	 * Method to test Inflector::getCachedSingular().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::getCachedSingular
	 */
	public function testGetCachedSingular()
	{
		// Reset the cache.
		TestReflection::setValue($this->inflector, 'cache', array('foo' => 'bar'));

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'getCachedSingular', 'foo'),
			$this->isFalse(),
			'Checks for an uncached singular.'
		);

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'getCachedSingular', 'bar'),
			$this->equalTo('foo'),
			'Checks for a cached singular word.'
		);
	}

	/**
	 * Method to test Inflector::matchRegexRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::matchRegexRule
	 */
	public function testMatchRegexRule()
	{
		$this->assertThat(
			TestReflection::invoke($this->inflector, 'matchRegexRule', 'xyz', 'plural'),
			$this->equalTo('xyzs'),
			'Checks pluralising against the basic regex.'
		);

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'matchRegexRule', 'xyzs', 'singular'),
			$this->equalTo('xyz'),
			'Checks singularising against the basic regex.'
		);

		$this->assertThat(
			TestReflection::invoke($this->inflector, 'matchRegexRule', 'xyz', 'singular'),
			$this->isFalse(),
			'Checks singularising against an unmatched regex.'
		);
	}

	/**
	 * Method to test Inflector::setCache().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::setCache
	 */
	public function testSetCache()
	{
		TestReflection::invoke($this->inflector, 'setCache', 'foo', 'bar');

		$cache = TestReflection::getValue($this->inflector, 'cache');

		$this->assertThat(
			$cache['foo'],
			$this->equalTo('bar'),
			'Checks the cache was set.'
		);

		TestReflection::invoke($this->inflector, 'setCache', 'foo', 'car');

		$cache = TestReflection::getValue($this->inflector, 'cache');

		$this->assertThat(
			$cache['foo'],
			$this->equalTo('car'),
			'Checks an existing value in the cache was reset.'
		);
	}

	/**
	 * Method to test Inflector::addCountableRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::addCountableRule
	 */
	public function testAddCountableRule()
	{
		// Add string.
		$this->inflector->addCountableRule('foo');

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('foo', $rules['countable']),
			$this->isTrue(),
			'Checks a countable rule was added.'
		);

		// Add array.
		$this->inflector->addCountableRule(array('goo', 'car'));

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('car', $rules['countable']),
			$this->isTrue(),
			'Checks a countable rule was added by array.'
		);
	}

	/**
	 * Method to test Inflector::addPluraliseRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::addPluraliseRule
	 */
	public function testAddPluraliseRule()
	{
		$chain = $this->inflector->addPluraliseRule(array('/foo/', '/bar/'));

		$this->assertThat(
			$chain,
			$this->identicalTo($this->inflector),
			'Checks chaining.'
		);

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('/bar/', $rules['plural']),
			$this->isTrue(),
			'Checks a pluralisation rule was added.'
		);
	}

	/**
	 * Method to test Inflector::addSingulariseRule().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::addSingulariseRule
	 */
	public function testAddSingulariseRule()
	{
		$chain = $this->inflector->addSingulariseRule(array('/foo/', '/bar/'));

		$this->assertThat(
			$chain,
			$this->identicalTo($this->inflector),
			'Checks chaining.'
		);

		$rules = TestReflection::getValue($this->inflector, 'rules');

		$this->assertThat(
			in_array('/bar/', $rules['singular']),
			$this->isTrue(),
			'Checks a singularisation rule was added.'
		);
	}

	/**
	 * Method to test Inflector::getInstance().
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::getInstance
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf(
			'Joomla\\String\\Inflector',
			Inflector::getInstance(),
			'Check getInstance returns the right class.'
		);

		// Inject an instance an test.
		TestReflection::setValue($this->inflector, 'instance', new stdClass);

		$this->assertThat(
			Inflector::getInstance(),
			$this->equalTo(new stdClass),
			'Checks singleton instance is returned.'
		);

		$this->assertInstanceOf(
			'Joomla\\String\\Inflector',
			Inflector::getInstance(true),
			'Check getInstance a fresh object with true argument even though the instance is set to something else.'
		);
	}

	/**
	 * Method to test Inflector::isCountable().
	 *
	 * @param   string   $input     A string.
	 * @param   boolean  $expected  The expected result of the function call.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedIsCountable
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::isCountable
	 */
	public function testIsCountable($input, $expected)
	{
		$this->assertThat(
			$this->inflector->isCountable($input),
			$this->equalTo($expected)
		);
	}

	/**
	 * Method to test Inflector::isPlural().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedSinglePlural
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::isPlural
	 */
	public function testIsPlural($singular, $plural)
	{
		$this->assertThat(
			$this->inflector->isPlural($plural),
			$this->isTrue(),
			'Checks the plural is a plural.'
		);

		if ($singular != $plural)
		{
			$this->assertThat(
				$this->inflector->isPlural($singular),
				$this->isFalse(),
				'Checks the singular is not plural.'
			);
		}
	}

	/**
	 * Method to test Inflector::isSingular().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedSinglePlural
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::isSingular
	 */
	public function testIsSingular($singular, $plural)
	{
		$this->assertThat(
			$this->inflector->isSingular($singular),
			$this->isTrue(),
			'Checks the singular is a singular.'
		);

		if ($singular != $plural)
		{
			$this->assertThat(
				$this->inflector->isSingular($plural),
				$this->isFalse(),
				'Checks the plural is not singular.'
			);
		}
	}

	/**
	 * Method to test Inflector::toPlural().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedSinglePlural
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::toPlural
	 */
	public function testToPlural($singular, $plural)
	{
		$this->assertThat(
			$this->inflector->toPlural($singular),
			$this->equalTo($plural)
		);
	}

	/**
	 * Method to test Inflector::toPlural().
	 *
	 * @param   string  $singular  The singular form of a word.
	 * @param   string  $plural    The plural form of a word.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedSinglePlural
	 * @since   12.1
	 * @covers  Joomla\String\Inflector::toSingular
	 */
	public function testToSingular($singular, $plural)
	{
		$this->assertThat(
			$this->inflector->toSingular($plural),
			$this->equalTo($singular)
		);
	}
}
