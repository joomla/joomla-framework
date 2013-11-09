<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor\Text;

use Joomla\Console\Console;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Console\Descriptor\AbstractDescriptorHelper;

/**
 * A descriptor helper to get different descriptor and render it.
 *
 * @since  1.0
 */
class TextDescriptorHelper extends AbstractDescriptorHelper
{
	/**
	 * Template of console.
	 *
	 * @var string
	 *
	 * @since  1.0
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
	 * Describe a command detail.
	 *
	 * @param   AbstractCommand  $command  The command to described.
	 *
	 * @return  string  Return the described text.
	 *
	 * @throws  \RuntimeException
	 *
	 * @since  1.0
	 */
	public function describe(AbstractCommand $command)
	{
		// Describe Options
		$options          = $command->getAllOptions();
		$optionDescriptor = $this->getOptionDescriptor();

		foreach ($options as $option)
		{
			$optionDescriptor->addItem($option);
		}

		$render['option'] = $optionDescriptor->render();

		// Describe Commands
		$commands          = $command->getArguments();
		$commandDescriptor = $this->getCommendDescriptor();

		foreach ($commands as $cmd)
		{
			$commandDescriptor->addItem($cmd);
		}

		$render['command'] = $commandDescriptor->render();

		// Render Help template
		/** @var Console $console */
		$console = $command->getApplication();

		if (!($console instanceof Console))
		{
			throw new \RuntimeException('Help descriptor need Console object in default command.');
		}

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

		$template = sprintf(
			$this->template,
			$consoleName,
			$version,
			$commandName,
			$description,
			$usage,
			$help
		);

		return str_replace(
			array('{OPTIONS}', '{COMMANDS}'),
			$render,
			$template
		);
	}
}
