<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

use Joomla\Console\Command\Command;
use Joomla\Console\Console;

/**
 * Class ConsoleDescriptor
 *
 * @package  Joomla\Console\Descriptor
 * @since    1.0
 */
class HelpDescriptor extends Descriptor
{
	/**
	 * The Console to describe.
	 *
	 * @var  Console
	 */
	protected $console;

	/**
	 * Template of console.
	 *
	 * @var string
	 */
	protected $template = <<<EOF

<comment>%s</comment> - version: %s
------------------------------------------------------------

[<comment>%s</comment> Help]

%s
Usage:
  %s


Options:

{OPTIONS}

Available commands:

{COMMANDS}

%s
EOF;

	/**
	 * Render an item description.
	 *
	 * @param   Command  $console  The item to br described.
	 *
	 * @throws  \InvalidArgumentException
	 * @return  string  Rendered description.
	 */
	protected function renderItem($command)
	{

		/** @var $command Command */
		if (!($command instanceof Command))
		{
			throw new \InvalidArgumentException('Help descriptor need Command object to describe it.');
		}

		$console = $command->getApplication();

		/** @var Console $console */
		$consoleName = $console->getName();
		$version     = $console->getVersion();

		$commandName = $command->getName();
		$description = $command->getDescription();
		$usage       = $command->getUsage();
		$help        = $command->getHelp();

		// Clean line indent of description
		$description = explode("\n", $description);

		foreach ($description as &$line)
		{
			$line = trim($line);
		}

		$description = implode("\n", $description);
		$description = $description ? $description . "\n" : '';

		return sprintf(
			$this->template,
			$consoleName,
			$version,
			$commandName,
			$description,
			$usage,
			$help
		);
	}
}
