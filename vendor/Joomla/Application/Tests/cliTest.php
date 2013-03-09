<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests;

use Joomla\Application\Cli;
use Joomla\Registry\Registry;
use Joomla\Test\Config;
use Joomla\Test\Helper;

include_once __DIR__ . '/Stubs/CliInspector.php';

/**
 * Test class for Joomla\Application\Cli.
 *
 * @since    1.0
 */
class CliTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the object to test.
	 *
	 * @var    CliInspector
	 * @since  1.0
	 */
	protected $class;

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUp()
	{
		// Get a new CliInspector instance.
		$this->class = new CliInspector;
	}

	/**
	 * Tests the Joomla\Application\Cli::__construct method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertInstanceOf('Joomla\\Input\\Input', $this->class->input, 'Input property wrong type');

		$this->assertAttributeInstanceOf('Joomla\Registry\Registry', 'config', $this->class, 'Checks config property');

		// TODO Test that configuration data loaded.

		$this->assertGreaterThan(2001, $this->class->get('execution.datetime'), 'Tests execution.datetime was set.');
		$this->assertGreaterThan(1, $this->class->get('execution.timestamp'), 'Tests execution.timestamp was set.');
	}

	/**
	 * Tests the Joomla\Application\Cli::__construct method with dependancy injection.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__constructDependancyInjection()
	{
		$mockInput = $this->getMock('Joomla\\Input\\Cli', array('test'), array(), '', false);
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

		$class = new CliInspector($mockInput, $mockConfig);

		$this->assertEquals('ok', $class->input->test(), 'Tests input injection.');

		$this->assertEquals('ok', Helper::getValue($class, 'config')->test(), 'Tests config injection.');
	}

	/**
	 * Tests the Joomla\Application\Cli::close method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testClose()
	{
		// Make sure the application is not already closed.
		$this->assertSame(
			$this->class->closed,
			null,
			'Checks the application doesn\'t start closed.'
		);

		$this->class->close(3);

		// Make sure the application is closed with code 3.
		$this->assertSame(
			$this->class->closed,
			3,
			'Checks the application was closed with exit code 3.'
		);
	}

	/**
	 * Data for fetchConfigurationData method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getFetchConfigurationData()
	{
		return array(
			// Note: file, class, expectsClass, (expected result array), whether there should be an exception
			'Default configuration class' => array(null, null, '\\Joomla\\Test\\Config', 'ConfigEval'),
			'Custom file, invalid class' => array(JPATH_BASE . '/config.JCli-wrongclass.php', 'noclass', false, array(), true),
		);
	}

	/**
	 * Tests the Joomla\Application\Cli::fetchConfigurationData method.
	 *
	 * @param   string   $file               The name of the configuration file.
	 * @param   string   $class              The name of the class.
	 * @param   boolean  $expectsClass       The result is expected to be a class.
	 * @param   array    $expects            The expected result as an array.
	 * @param   boolean  $expectedException  The expected exception
	 *
	 * @return  void
	 *
	 * @dataProvider getFetchConfigurationData
	 * @since    1.0
	 */
	public function testFetchConfigurationData($file, $class, $expectsClass, $expects, $expectedException = false)
	{
		if ($expectedException)
		{
			$this->setExpectedException('RuntimeException');
		}

		$method = new \ReflectionMethod($this->class, 'fetchConfigurationData');
		$method->setAccessible(true);

		if (is_null($file) && is_null($class))
		{
			$config = $method->invoke($this->class);
		}
		elseif (is_null($class))
		{
			$args = array($file);

			$config = $method->invokeArgs($this->class, $args);
		}
		else
		{
			$args = array($file, $class);

			$config = $method->invokeArgs($this->class, $args);
		}

		if ($expects == 'ConfigEval')
		{
			$expects = new Config;
			$expects = (array) $expects;
		}

		if ($expectsClass)
		{
			$this->assertInstanceOf(
				$expectsClass,
				$config,
				'Checks the configuration object is the appropriate class.'
			);
		}

		$this->assertEquals(
			$expects,
			(array) $config,
			'Checks the content of the configuration object.'
		);
	}

	/**
	 * Tests the Joomla\Application\Cli::get method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGet()
	{
		$config = new Registry(array('foo' => 'bar'));

		Helper::getValue($this->class, 'config');

		$this->assertEquals('bar', $this->class->get('foo', 'car'), 'Checks a known configuration setting is returned.');

		$this->assertEquals('car', $this->class->get('goo', 'car'), 'Checks an unknown configuration setting returns the default.');
	}

	/**
	 * Tests the Joomla\Application\Cli::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf(
			'\\Joomla\\Application\\Tests\\CliInspector',
			Cli::getInstance('Joomla\Application\Tests\CliInspector'),
			'Tests that getInstance will instantiate a valid child class of Joomla\Application\Cli.'
		);

		Helper::setValue('Joomla\\Application\\Cli', 'instance', 'foo');

		$this->assertEquals('foo', Cli::getInstance('CliInspector'), 'Tests that singleton value is returned.');
	}

	/**
	 * Tests the Joomla\Application\Cli::getInstance method for an expected exception
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  RuntimeException
	 */
	public function testGetInstanceException()
	{
		Helper::setValue('Joomla\\Application\\Cli', 'instance', null);

		Cli::getInstance('Foo');
	}

	/**
	 * Tests the Joomla\Application\Cli::loadConfiguration method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadConfiguration()
	{
		$this->assertThat(
			$this->class->loadConfiguration(
				array(
					'foo' => 'bar',
				)
			),
			$this->identicalTo($this->class),
			'Check chaining.'
		);

		$this->assertEquals('bar', Helper::getValue($this->class, 'config')->get('foo'), 'Check the configuration array was loaded.');

		$this->class->loadConfiguration(
			(object) array(
				'goo' => 'car',
			)
		);

		$this->assertEquals('car', Helper::getValue($this->class, 'config')->get('goo'), 'Check the configuration object was loaded.');
	}

	/**
	 * Tests the Joomla\Application\Cli::set method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSet()
	{
		$config = new Registry(array('foo' => 'bar'));

		Helper::setValue($this->class, 'config', $config);

		$this->assertEquals('bar', $this->class->set('foo', 'car'), 'Checks set returns the previous value.');

		$this->assertEquals('car', $config->get('foo'), 'Checks the new value has been set.');
	}
}
