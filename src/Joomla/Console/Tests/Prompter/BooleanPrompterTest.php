<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Tests\Prompter;

use Joomla\Console\Prompter\BooleanPrompter;

/**
 * Class BooleanPrompterTest
 *
 * @since  1.0
 */
class BooleanPrompterTest extends AbstractPrompterTest
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = $prompter = new BooleanPrompter('True or False [Y/n]: ', null, null, $this->output);
	}

	/**
	 * Test prompter ask.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testAsk()
	{
		$this->setStream("y");

		$in = $this->instance->ask();

		$this->assertTrue($in, 'Input result should be TRUE.');


		$this->setStream("yes");

		$in = $this->instance->ask();

		$this->assertTrue($in, 'Input result should be TRUE.');


		$this->setStream("Y");

		$in = $this->instance->ask();

		$this->assertTrue($in, 'Input result should be TRUE.');


		$this->setStream("n");

		$in = $this->instance->ask();

		$this->assertFalse($in, 'Input result should be FALSE.');
	}
}
