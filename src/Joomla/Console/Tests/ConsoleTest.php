<?php
/**
 * Created by PhpStorm.
 * User: Ezio
 * Date: 2013/11/9
 * Time: 下午 1:44
 */

namespace Joomla\Console\Tests;

use Joomla\Console\Console;
use Joomla\Console\Output\Stdout;
use Joomla\Console\Tests\Output\TestStdout;
use Joomla\Console\Tests\Stubs\FooCommand;
use Joomla\Input;
use Joomla\Test\TestHelper;

class ConsoleTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Console
	 */
	public $instance;

	/**
	 * Set up test.
	 */
	protected function setUp()
	{
		$input = new Input\Cli;

		$input->args = array('foo');

		/** @var $console Console */
		$console = with(new Console($input, null, new TestStdout))
			->setName('Test Console')
			->setVersion('1.2.3')
			->setDescription('Test desc.')
			->setAutoExit(false);

		$this->instance = $console;
	}

	public function testNestedCall()
	{
		$this->instance->addCommand(new FooCommand);

		$this->instance->input->args = array('foo', 'aaa', 'bbb');

		$code = $this->instance->execute();

		$output = $this->instance->getOutput()->getOutput();

		$this->assertEquals(99, $code, 'return code not matched.');

		$this->assertEquals('Bbb Command', $output, 'Output not matched.');
	}

	/**
	 * testSetAutoExit
	 */
	public function testSetAutoExit()
	{
		$this->instance->setAutoExit(true);

		$this->assertEquals(true, TestHelper::getValue($this->instance, 'autoExit'), 'Auto exit need to be TRUE');

		$this->instance->setAutoExit(false);
	}

	/**
	 * testAddCommand
	 */
	public function testAddCommand()
	{
		$this->instance->addCommand(new FooCommand);

		$this->assertEquals('foo', $this->instance->getDefaultCommand()->getArgument('foo')->getName());
	}

	/**
	 * testConstruct
	 */
	public function testConstruct()
	{
		$console = new Console(null, null, new Stdout);

		$this->assertInstanceOf('Joomla\\Input\\Cli', $console->input);

		$this->assertInstanceOf('Joomla\\Registry\\Registry', TestHelper::getValue($console, 'config'));
	}

	/**
	 * testDoExecute
	 */
	public function testDoExecute()
	{
		$this->instance->addCommand(new FooCommand);

		$result = $this->instance->execute();

		// Return exit code.
		$this->assertEquals(123, $result, 'Return value wrong');
	}

	public function testRegisterDefaultCommand()
	{
		$this->assertInstanceOf('Joomla\\Console\\Command\\DefaultCommand', $this->instance->getDefaultCommand(), 'Default Command wrong');
	}

	/**
	 * testRegister
	 */
	public function testRegister()
	{
		$this->instance->register('bar');

		$this->assertInstanceOf('Joomla\\Console\\Command\\Command', $this->instance->getDefaultCommand()->getArgument('bar'), 'Need Command instance');
	}

	/**
	 * testGetName
	 */
	public function testGetName()
	{
		$this->assertEquals('Test Console', $this->instance->getName());
	}

	/**
	 * testSetName
	 */
	public function testSetName()
	{
		$this->instance->setName('Test Console2');

		$this->assertEquals('Test Console2', $this->instance->getName());
	}

	/**
	 * testGetVersion
	 */
	public function testGetVersion()
	{
		$this->assertEquals('1.2.3', $this->instance->getVersion());
	}

	/**
	 * testSetVersion
	 */
	public function testSetVersion()
	{
		$this->instance->setVersion('3.2.1');

		$this->assertEquals('3.2.1', $this->instance->getVersion());
	}

	/**
	 * testGetDescription
	 */
	public function testGetDescription()
	{
		$this->assertEquals('Test desc.', $this->instance->getDescription());
	}

	/**
	 * testSetDescription
	 */
	public function testSetDescription()
	{
		$this->instance->setDescription('Test desc 2.');

		$this->assertEquals('Test desc 2.', $this->instance->getDescription());
	}

	/**
	 * testSetCode
	 */
	public function testSetCode()
	{
		$this->instance->setCode(
			function($command)
			{
				return 221;
			}
		);

		$this->assertInstanceOf('\Closure', $this->instance->getDefaultCommand()->getCode(), 'Code need to be a closure.');

		$this->assertEquals(221, $this->instance->getDefaultCommand()->setInput(new Input\Cli)->execute());
	}
}
