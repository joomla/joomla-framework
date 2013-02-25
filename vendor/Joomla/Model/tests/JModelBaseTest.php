<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

require_once __DIR__ . '/stubs/tbase.php';

/**
 * Tests for the JViewBase class.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Model
 * @since       12.1
 */
class JModelBaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    BaseModel
	 * @since  12.1
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::__construct
	 * @since   12.1
	 */
	public function test__construct()
	{
		// @codingStandardsIgnoreStart
		// @todo check the instanciating new classes without brackets sniff
		$this->assertEquals(new Registry, $this->instance->getState(), 'Checks default state.');
		// @codingStandardsIgnoreEnd

		$state = new Registry(array('foo' => 'bar'));
		$class = new BaseModel($state);
		$this->assertEquals($state, $class->getState(), 'Checks state injection.');
	}

	/**
	 * Tests the getState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::getState
	 * @since   12.1
	 */
	public function testGetState()
	{
		// Reset the state property to a known value.
		TestReflection::setValue($this->instance, 'state', 'foo');

		$this->assertEquals('foo', $this->instance->getState());
	}

	/**
	 * Tests the setState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::setState
	 * @since   12.1
	 */
	public function testSetState()
	{
		$state = new Registry(array('foo' => 'bar'));
		$this->instance->setState($state);
		$this->assertSame($state, $this->instance->getState());
	}

	/**
	 * Tests the loadState method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Model\Base::loadState
	 * @since   12.1
	 */
	public function testLoadState()
	{
		$this->assertInstanceOf('Joomla\Registry\Registry', TestReflection::invoke($this->instance, 'loadState'));
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new BaseModel;
	}
}
