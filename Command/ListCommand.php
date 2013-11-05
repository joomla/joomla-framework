<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

use Joomla\Console;

class ListCommand extends Command
{
	/**
	 * Configure command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('list')
			->setDescription('Lists commands');
	}

	/**
	 * doExecute
	 *
	 */
	protected function doExecute()
	{
		echo 'List Command';
	}
}