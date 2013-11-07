<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

use Joomla\Console;
use Joomla\Console\Option\Option;

/**
 * The default command.
 *
 * @since  1.0
 */
class DefaultCommand extends Command
{
	/**
	 * Configure command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		// Get application file name
		$file = $_SERVER['argv'][0];
		$file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $file);
		$file = explode(DIRECTORY_SEPARATOR, $file);
		$file = array_pop($file);

		$this->setName($file)
			->setDescription('The default application command')
			->addArgument(
				with(new ListCommand('list', $this->input, $this->output))
			)
			->addOption(
				array('h', 'help'),
				0,
				'Display this help message.',
				Option::IS_GLOBAL
			)
			->addOption(
				array('q', 'quiet'),
				0,
				'Do not output any message.',
				Option::IS_GLOBAL
			)
			->setHelp(
<<<HELP
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sollicitudin
pellentesque nulla in lobortis. Mauris ullamcorper dapibus diam eu mattis.
Mauris vitae nisi mi.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sollicitudin
pellentesque nulla in lobortis. Mauris ullamcorper dapibus diam eu mattis.
Mauris vitae nisi mi.
HELP
			);
	}
}
