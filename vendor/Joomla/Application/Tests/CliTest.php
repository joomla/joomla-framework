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

include_once __DIR__ . '/Stubs/ConcreteCli.php';

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
	 * @var    Cli
	 * @since  1.0
	 */
	protected $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Cli::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		// @TODO Test that configuration data loaded.

		$this->assertGreaterThan(2001, $this->instance->get('execution.datetime'), 'Tests execution.datetime was set.');
		$this->assertGreaterThan(1, $this->instance->get('execution.timestamp'), 'Tests execution.timestamp was set.');

		// Test dependancy injection.

		$mockInput = $this->getMock('Joomla\Input\Cli', array('test'), array(), '', false);
		$mockInput
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$mockConfig = $this->getMock('Joomla\Registry\Registry', array('test'), array(null), '', true);

		$instance = new ConcreteCli($mockInput, $mockConfig);

		$input = Helper::getValue($instance, 'input');
		$this->assertEquals('ok', $input->test());
	}

	/**
	 * Tests the close method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Cli::close
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
	 * Tests the Joomla\Application\Cli::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf(
			'\\Joomla\\Application\\Tests\\ConcreteCli',
			Cli::getInstance('Joomla\Application\Tests\ConcreteCli'),
			'Tests that getInstance will instantiate a valid child class of Joomla\Application\Cli.'
		);

		Helper::setValue('Joomla\\Application\\Cli', 'instance', 'foo');

		$this->assertEquals('foo', Cli::getInstance('ConcreteCli'), 'Tests that singleton value is returned.');
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
		Helper::setValue('Joomla\Application\Cli', 'instance', null);

		Cli::getInstance('Foo');
	}

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUp()
	{
		// Get a new ConcreteCli instance.
		$this->instance = new ConcreteCli;
	}
}
