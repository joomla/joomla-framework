<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests;

use Joomla\Application\Cli\ColorProcessor;
use Joomla\Application\Cli\ColorStyle;

/**
 * Test class.
 *
 * @since  1.0
 */
class ColorProcessorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var ColorProcessor
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new ColorProcessor;
	}

	/**
	 * @covers Joomla\Application\Cli\ColorProcessor::addStyle
	 */
	public function testAddStyle()
	{
		$style = new ColorStyle('red');
		$this->object->addStyle('foo', $style);

		$this->assertThat(
			$this->object->process('<foo>foo</foo>'),
			$this->equalTo('[31mfoo[0m')
		);
	}

	/**
	 * @covers Joomla\Application\Cli\ColorProcessor::stripColors
	 */
	public function testStripColors()
	{
		$this->assertThat(
			$this->object->stripColors('<foo>foo</foo>'),
			$this->equalTo('foo')
		);
	}

	/**
	 * @covers Joomla\Application\Cli\ColorProcessor::process
	 */
	public function testProcess()
	{
		$this->assertThat(
			$this->object->process('<fg=red>foo</fg=red>'),
			$this->equalTo('[31mfoo[0m')
		);
	}

	/**
	 * @covers Joomla\Application\Cli\ColorProcessor::process
	 */
	public function testProcessNamed()
	{
		$style = new ColorStyle('red');
		$this->object->addStyle('foo', $style);

		$this->assertThat(
			$this->object->process('<foo>foo</foo>'),
			$this->equalTo('[31mfoo[0m')
		);
	}

	/**
	 * @covers Joomla\Application\Cli\ColorProcessor::replaceColors
	 */
	public function testProcessReplace()
	{
		$this->assertThat(
			$this->object->process('<fg=red>foo</fg=red>'),
			$this->equalTo('[31mfoo[0m')
		);
	}
}
