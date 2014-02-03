<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Console\Prompter\TextPrompter;

/**
 * Class TextPrompterTest
 *
 * @since  1.0
 */
class TextPrompterTest extends AbstractPrompterTest
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

		$this->instance = $prompter = new TextPrompter(null, $this->output);
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

		$in = $this->instance->ask('Tell me something: ');

		$this->assertEquals(
			trim($this->output->getOutput()),
			trim('Tell me something: ')
		);

		$this->assertEquals($in, 'y');
	}
}
