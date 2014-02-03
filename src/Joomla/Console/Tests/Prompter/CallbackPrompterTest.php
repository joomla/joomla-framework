<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Console\Prompter\CallbackPrompter;

/**
 * Class PrompterTest
 *
 * @since  1.0
 */
class CallbackPrompterTest extends AbstractPrompterTest
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

		$this->instance = $prompter = new CallbackPrompter(null, $this->output);
	}

	public function testAsk()
	{
		$this->instance->setHandler(
			function($value)
			{
				if ($value == 3)
				{
					return true;
				}

				return false;
			}
		);

		$this->setStream("4\n5\n6");

		$this->assertEquals($this->instance->ask('Tell me something: ', 3), 3);

		$this->setStream("4\n5\n6");

		$this->assertNull($this->instance->ask('Tell me something: '));

		$this->setStream(3);

		$this->assertEquals($this->instance->ask('Tell me something: '), 3);
	}
}
