<?php
/**
 * Part of jframework project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;


interface PrompterInterface
{
	/**
	 * ask
	 *
	 * @param string $msg
	 * @param string $default
	 *
	 * @return  mixed
	 */
	public function ask($msg = '', $default = '');
}
 