<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Console\Prompter\PasswordPrompter;

/**
 * Class PasswordPrompterTest
 *
 * @since  1.0
 */
class PasswordPrompterTest extends AbstractPrompterTest
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

		$this->instance = $prompter = new PasswordPrompter(null, $this->output);
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
		if (defined('PHP_WINDOWS_VERSION_BUILD'))
		{
			$this->markTestSkipped('This test is not supported on Windows');
		}

		$this->setStream("1234qwer\n");

		$in = $this->instance->ask('Enter password: ');

		$this->assertEquals('1234qwer', $in);
	}
}
