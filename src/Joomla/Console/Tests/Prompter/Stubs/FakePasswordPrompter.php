<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Tests\Prompter\Stubs;

use Joomla\Console\Prompter\PasswordPrompter;

/**
 * Class Fake Password Prompter
 *
 * @since 1.0
 */
class FakePasswordPrompter extends PasswordPrompter
{
	/**
	 * We dont't test bash because it break test process in IDE.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	protected function findStty()
	{
		return true;
	}

	/**
	 * We dont't test bash because it break test process in IDE.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	protected function findShell()
	{
		return false;
	}
}
 