<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Tests\Prompter;

use Joomla\Console\Prompter\ValidatePrompter;

/**
 * Class ValidatePrompterTest
 *
 * @since  1.0
 */
class ValidatePrompterTest extends AbstractPrompterTest
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

		$this->instance = $prompter = new ValidatePrompter('Tell me something: ', null, array('flower', 'sakura', 'rose'), null, $this->output);
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
		$this->setStream("4\n5\n6");

		$this->assertEquals($this->instance->ask(null, 'sakura'), 'sakura', 'Should validate fail and return default.');

		$this->setStream("4\n5\n6");

		$this->assertNull($this->instance->ask(null), 'Should validate fail and get NULL.');

		$this->setStream('sakura');

		$this->assertEquals($this->instance->ask('Tell me something: '), 'sakura', 'Should validate success and pass.');
	}
}
