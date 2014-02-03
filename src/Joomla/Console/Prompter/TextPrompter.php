<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

/**
 * Class Prompter
 *
 * @since 1.0
 */
class TextPrompter extends AbstractPrompter
{
	/**
	 * ask
	 *
	 * @param string $msg
	 * @param string $default
	 *
	 * @return  mixed
	 */
	public function ask($msg = '', $default = null)
	{
		return $this->in($msg) ? : $default;
	}
}
 