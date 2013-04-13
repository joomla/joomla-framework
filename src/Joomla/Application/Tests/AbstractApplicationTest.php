<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests;

use Joomla\Application\Base;
use Joomla\Test\Helper;
use Joomla\Registry\Registry;

require_once __DIR__ . '/Stubs/ConcreteBase.php';

/**
 * Test class for Joomla\Application\Base.
 *
 * @since  1.0
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to test.
	 *
	 * @var    Base
	 * @since  1.0
	 */
	protected $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertInstanceOf(
			'Joomla\\Input\\Input',
			$this->instance->input,
			'Input property wrong type'
		);

		$this->assertInstanceOf(
			'Joomla\Registry\Registry',
			Helper::getValue($this->instance, 'config'),
			'Config property wrong type'
		);

		// Test dependancy injection.

		$mockInput = $this->getMock('Joomla\Input\Input', array('test'), array(), '', false);
		$mockInput
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$mockConfig = $this->getMock('Joomla\Registry\Registry', array('test'), array(null), '', true);
		$mockConfig
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$instance = new ConcreteBase($mockInput, $mockConfig);

		$input = Helper::getValue($instance, 'input');
		$this->assertEquals('ok', $input->test());

		$config = Helper::getValue($instance, 'config');
		$this->assertEquals('ok', $config->test());
	}

	/**
	 * Test the close method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::close
	 * @since   1.0
	 */
	public function testClose()
	{
		// Make sure the application is not already closed.
		$this->assertSame(
			$this->instance->closed,
			null,
			'Checks the application doesn\'t start closed.'
		);

		$this->instance->close(3);

		// Make sure the application is closed with code 3.
		$this->assertSame(
			$this->instance->closed,
			3,
			'Checks the application was closed with exit code 3.'
		);
	}

	/**
	 * Test the execute method
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::execute
	 * @since   1.0
	 */
	public function testExecute()
	{
		$this->instance->doExecute = false;

		$this->instance->execute();

		$this->assertTrue($this->instance->doExecute);
	}

	/**
	 * Tests the get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$mockInput = $this->getMock('Joomla\Input\Input', array('test'), array(), '', false);
		$config = new Registry(array('foo' => 'bar'));

		$instance = new ConcreteBase($mockInput, $config);

		$this->assertEquals('bar', $instance->get('foo', 'car'), 'Checks a known configuration setting is returned.');
		$this->assertEquals('car', $instance->get('goo', 'car'), 'Checks an unknown configuration setting returns the default.');
	}

	/**
	 * Tests the Joomla\Application\Base::getLogger for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers             Joomla\Application\Base::getLogger
	 * @expectedException  UnexpectedValueException
	 * @since              1.0
	 */
	public function testGetLogger_exception()
	{
		$this->instance->getLogger();
	}

	/**
	 * Tests the Joomla\Application\Base::hasLogger for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::hasLogger
	 * @since   1.0
	 */
	public function testHasLogger()
	{
		$this->assertFalse($this->instance->hasLogger());

		$mockLogger = $this->getMock('Psr\Log\AbstractLogger', array('log'), array(), '', false);
		$this->instance->setLogger($mockLogger);

		$this->assertTrue($this->instance->hasLogger());
	}

	/**
	 * Tests the set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$mockInput = $this->getMock('Joomla\Input\Input', array('test'), array(), '', false);
		$config = new Registry(array('foo' => 'bar'));

		$instance = new ConcreteBase($mockInput, $config);

		$this->assertEquals('bar', $instance->set('foo', 'car'), 'Checks set returns the previous value.');

		$this->assertEquals('car', $instance->get('foo'), 'Checks the new value has been set.');
	}

	/**
	 * Tests the set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::setConfiguration
	 * @since   1.0
	 */
	public function testSetConfiguration()
	{
		$config = new Registry(array('foo' => 'bar'));

		$this->assertSame($this->instance, $this->instance->setConfiguration($config), 'Checks chainging.');
		$this->assertEquals('bar', $this->instance->get('foo'), 'Checks the configuration was set.');
	}

	/**
	 * Tests the Joomla\Application\Base::setLogger and getLogger methods.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Base::setLogger
	 * @covers  Joomla\Application\Base::getLogger
	 * @since   1.0
	 */
	public function testSetLogger()
	{
		$mockLogger = $this->getMock('Psr\Log\AbstractLogger', array('log'), array(), '', false);

		$this->assertSame($this->instance, $this->instance->setLogger($mockLogger), 'Checks chainging.');
		$this->assertSame($mockLogger, $this->instance->getLogger(), 'Checks the get method.');
	}

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		// Create the class object to be tested.
		$this->instance = new ConcreteBase;
	}
}
