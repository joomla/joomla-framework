<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

use Joomla\Console\Command\Command;

/**
 * Class CommandDescriptor
 *
 * @package  Joomla\Console\Descriptor
 * @since    1.0
 */
class CommandDescriptor extends Descriptor
{
	/**
	 * Offset that between every commands and their descriptions.
	 *
	 * @var int
	 */
	protected $offsetAfterCommand = 4;

	/**
	 * Template of every commands.
	 *
	 * @var string
	 */
	protected $template = <<<EOF
  <info>%-{WIDTH}s</info>%s
EOF;

	/**
	 * The max length of command.
	 *
	 * @var int
	 */
	protected $maxLength = 0;

	/**
	 * Render an item description.
	 *
	 * @param   mixed  $command  The item to br described.
	 *
	 * @throws  \InvalidArgumentException
	 * @return  string  Rendered description.
	 */
	protected function renderItem($command)
	{
		if (!($command instanceof Command))
		{
			throw new \InvalidArgumentException('Command descriptor need Command object to describe it.');
		}

		/** @var Command $command */
		$name        = $command->getName();
		$description = $command->getDescription() ?: 'No description';

		$template = str_replace('{WIDTH}', $this->maxLength + $this->offsetAfterCommand, $this->template);

		// Sets the body indent.
		$body = array();

		$description = explode("\n", $description);

		$line1  = array_shift($description);
		$body[] = sprintf($template, $name, $line1);

		foreach ($description as $line)
		{
			$line = trim($line);
			$line = sprintf($template, '', $line);
			$body[] = $line;
		}

		return implode("\n", $body) . "\n";
	}

	/**
	 * Render all items description.
	 *
	 * @return  string
	 */
	public function render()
	{
		// Count the max command length as column width.
		foreach ($this->items as $item)
		{
			$length = strlen($item->getName());

			if ($length > $this->maxLength)
			{
				$this->maxLength = $length;
			}
		}

		return parent::render();
	}
}
