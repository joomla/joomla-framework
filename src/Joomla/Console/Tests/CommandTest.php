<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Tests;

use Joomla\Console\Command\Command;
use Joomla\Console\Command\RootCommand;
use Joomla\Console\Console;
use Joomla\Console\Option\Option;
use Joomla\Console\Tests\Output\TestStdout;
use Joomla\Console\Tests\Stubs\FooCommand;
use Joomla\Input;
use Joomla\Test\TestHelper;

/**
 * Class CommandTest
 *
 * @since  1.0
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test instance.
	 *
	 * @var Command
	 */
	protected $instance;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 */
	protected function setUp()
	{
		$command = new RootCommand('default', null, new TestStdout);

		$command
			->addCommand(
				'yoo',
				'yoo desc'
			)
			->setHandler(
				function($command)
				{
					return 123;
				}
			);

		$this->instance = $command;
	}

	/**
	 * Test the execute.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::execute
	 */
	public function testExecute()
	{
		$this->assertEquals(123, $this->instance->execute(), 'Return code is not match.');
	}

	/**
	 * Test the input setter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::setInput
	 */
	public function testSetInput()
	{
		// Using mock to make sure we get same object.
		$mockInput = $this->getMock('Joomla\Input\Cli', array('test'), array(), '', false);
		$mockInput
			->expects($this->any())
			->method('test')
			->will(
				$this->returnValue('ok')
			);

		$this->instance->setInput($mockInput);

		$input = TestHelper::getValue($this->instance, 'input');
		$this->assertEquals('ok', $input->test());
	}

	/**
	 * Test the output setter
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::setOutput
	 */
	public function testSetOutput()
	{
		// Using mock to make sure we get same object.
		$mockOutput = $this->getMock('Joomla\Application\Cli\Output\Stdout', array('test'), array(), '', false);
		$mockOutput
			->expects($this->any())
			->method('test')
			->will(
				$this->returnValue('ok')
			);

		$this->instance->setOutput($mockOutput);

		$output = TestHelper::getValue($this->instance, 'output');
		$this->assertEquals('ok', $output->test());
	}

	/**
	 * Test the parent getter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getParent
	 */
	public function testGetParent()
	{
		$parentCommand = new Command('foo');

		$this->instance->setParent($parentCommand);

		$this->assertEquals('foo', $this->instance->getParent()->getName(), 'Parent command not match');
	}

	/**
	 * Test the parent setter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::setParent
	 */
	public function testSetParent()
	{
		$this->instance->setParent(null);

		$this->assertEquals(null, $this->instance->getParent(), 'Parent command not match');
	}

	/**
	 * Test the add argument method.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::addCommand
	 */
	public function testaddCommand()
	{
		$this->instance->addCommand(
			'bar',
			'bar desc',
			array(
				new Option('a', 0, 'a desc'),
				new Option('b', 0, 'b desc')
			),
			function($command)
			{
				if ($command->getOption('a'))
				{
					return 56;
				}
				else
				{
					return 65;
				}
			}
		);

		$command = $this->instance->getChild('bar');

		$this->assertEquals(65, $command->execute(), 'Wrong exit code returned.');

		// Test option
		$this->instance->getInput()->set('a', 1);

		$this->assertEquals(56, $command->execute(), 'Wrong exit code returned.');

		// Test send an instance
		$this->instance->addCommand(new FooCommand);

		$this->assertInstanceOf(
			'Joomla\\Console\\Tests\\Stubs\\FooCommand',
			$this->instance->getChild('foo'),
			'Argument not FooCommand.'
		);
	}

	/**
	 * Test the argument getter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getChild
	 */
	public function testgetChild()
	{
		$yoo = $this->instance->getChild('yoo');

		$this->assertEquals('yoo desc', $yoo->getDescription(), 'Yoo command desc not match.');
	}

	/**
	 * Test the getChildren methods.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getChildren
	 */
	public function testgetChildren()
	{
		$args = $this->instance->getChildren();

		$this->assertInternalType('array', $args, 'Return not array');

		$this->assertInstanceOf('Joomla\\Console\\Command\\AbstractCommand', array_shift($args), 'Array element not Command object');
	}

	/**
	 * Test the Add & Get Option.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::addOption
	 */
	public function testAddAndGetOption()
	{
		$cmd = $this->instance;

		$cmd->addOption(
			array('y', 'yell', 'Y'),
			false,
			'Make return uppercase',
			Option::IS_GLOBAL
		);

		$cmd->getInput()->set('y', 1);

		$this->assertSame(1, (int) $cmd->getOption('y'), 'Option value not matched.');

		$this->assertSame(1, (int) $cmd->getOption('yell'), 'Long option value not matched.');

		$this->assertSame(1, (int) $cmd->getOption('Y'), 'uppercase option value not matched.');

		// Test for global option
		$cmd->addCommand(new FooCommand);

		$this->assertSame(1, (int) $cmd->getChild('foo')->getOption('y'), 'Sub command should have global option');

		// Test for children
		$bbb = $cmd->getChild('foo/aaa/bbb');

		$this->assertInstanceOf('Joomla\\Console\\Option\\Option', $bbb->getOptionSet(true)->offsetGet('y'), 'Sub command "bbb" should have global option');
	}

	/**
	 * Test the options getter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getOptions
	 */
	public function testGetOptions()
	{
		$cmd = $this->instance;

		$cmd->addOption(
			array('y', 'yell', 'Y'),
			false,
			'Make return uppercase'
		);


		$array = $this->instance->getOptions();

		$this->assertInternalType('array', $array);

		$this->assertInstanceOf('Joomla\\Console\\Option\\Option', array_shift($array), 'Array element not Option object');
	}

	/**
	 * Test get arg.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getArgument
	 */
	public function testGetArgument()
	{
		$this->instance->getInput()->args = array('flower', 'sakura');

		$this->assertEquals('flower', $this->instance->getArgument(0), 'First arg not matched.');

		$this->assertEquals('rose', $this->instance->getArgument(2, 'rose'), 'Default value not matched.');

		$callback = function()
		{
			return 'Morning Glory';
		};

		$this->assertEquals('Morning Glory', $this->instance->getArgument(2, $callback), 'Default value not matched.');
	}

	/**
	 * Test get all options.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getAllOptions
	 */
	public function testSetAndGetAllOptions()
	{
		$cmd = $this->instance;

		$cmd->setOptions(
			new Option(
				array('y', 'yell', 'Y'),
				false,
				'Make return uppercase',
				Option::IS_GLOBAL
			)
		);


		$array = $this->instance->getAllOptions();

		$this->assertInternalType('array', $array);

		$this->assertInstanceOf('Joomla\\Console\\Option\\Option', array_shift($array), 'Array element not Option object');
	}

	/**
	 * Test the description getter & setter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getDescription
	 */
	public function testSetAndGetDescription()
	{
		$this->instance->setDescription('Wu la la~~~');

		$this->assertEquals('Wu la la~~~', $this->instance->getDescription(), 'Description not matched');
	}

	/**
	 * Test the name getter & setter.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getName
	 */
	public function testSetAndGetName()
	{
		$this->instance->setName('yoo');

		$this->assertEquals('yoo', $this->instance->getName(), 'Wrong name');
	}

	/**
	 * Test get & set code.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getHandler
	 */
	public function testSetAndgetHandler()
	{
		$code = $this->instance->getHandler();

		$this->assertInstanceOf('\Closure', $code, 'Handler not exists');

		$this->instance->setHandler(null);

		$this->assertEquals(null, $this->instance->getHandler(), 'Handler should have been cleaned');
	}

	/**
	 * Test get & set code by callable.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getHandler
	 */
	public function testSetAndgetCallableHandler()
	{
		$this->instance->setHandler(array($this, 'fakeHandler'));

		$code = $this->instance->getHandler();

		$this->assertTrue(is_callable($code), 'Handler not exists');

		$this->assertEquals('Hello', $this->instance->execute(), 'Handler result failure.');

		$this->instance->setHandler(null);

		$this->assertEquals(null, $this->instance->getHandler(), 'Handler should have been cleaned');
	}

	/**
	 * Test get option alias.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getOptionAlias
	 */
	public function testGetOptionAlias()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test set option alias.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::setOptionAlias
	 */
	public function testSetOptionAlias()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test set & get application.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getApplication
	 */
	public function testSetAndGetApplication()
	{
		$this->instance->setApplication(new Console);

		$this->assertInstanceOf('Joomla\\Console\\Console', $this->instance->getApplication(), 'Returned not Console object.');
	}

	/**
	 * Test set & get help.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getHelp
	 */
	public function testSetAndGetHelp()
	{
		$this->instance->setHelp('Ha Ha Ha');

		$this->assertEquals('Ha Ha Ha', $this->instance->getHelp(), 'Help text not matched.');
	}

	/**
	 * Test set & get usage.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::getUsage
	 */
	public function testSetAndGetUsage()
	{
		$this->instance->setUsage('yoo <command> [option]');

		$this->assertEquals('yoo <command> [option]', $this->instance->getUsage(), 'Usage text not matched.');
	}


	/**
	 * Test renderAlternatives.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::renderAlternatives
	 */
	public function testRenderAlternatives()
	{
		$compare = 'Command "yo" not found.

Did you mean one of these?
    yoo';

		$this->instance->getInput()->args = array('yo');

		$this->instance->getInput()->set('no-ansi', 1);

		$this->instance->execute();

		$this->assertEquals(
			str_replace(array("\n", "\r"), '', trim($compare)),
			str_replace(array("\n", "\r"), '', trim($this->instance->getOutput()->getOutput()))
		);
	}

	/**
	 * Test render exception.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::renderException
	 */
	public function testRenderException()
	{
		// Remove the following lines when you implement this test.
		$this->markTestSkipped(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the out method.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::out
	 */
	public function testOut()
	{
		$this->instance->getOutput()->setOutput('');

		$this->instance->out('gogo', false);

		$this->assertEquals('gogo', $this->instance->getOutput()->getOutput());
	}

	/**
	 * Test the err method.
	 *
	 * @return void
	 *
	 * @since  1.0
	 *
	 * @covers Joomla\Console\Command\AbstractCommand::err
	 */
	public function testErr()
	{
		$this->instance->getOutput()->setOutput('');

		$this->instance->err('errrr', false);

		$this->assertEquals('errrr', $this->instance->getOutput()->getOutput());
	}

	/**
	 * fakeHandler
	 *
	 * @param $command
	 *
	 * @return  string
	 */
	public function fakeHandler($command)
	{
		return 'Hello';
	}
}
