<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor\Text;

use Joomla\Console\Command\AbstractCommand;
use Joomla\Console\Command\Command;
use Joomla\Console\Descriptor\AbstractDescriptor;

/**
 * Class TextCommandDescriptor
 *
 * @since    1.0
 */
class TextCommandDescriptor extends AbstractDescriptor
{
	/**
	 * Offset that between every commands and their descriptions.
	 *
	 * @var int
	 *
	 * @since  1.0
	 */
	protected $offsetAfterCommand = 4;

	/**
	 * Template of every commands.
	 *
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $template = <<<EOF
  <info>%-{WIDTH}s</info>%s
EOF;

	/**
	 * The max length of command.
	 *
	 * @var int
	 *
	 * @since  1.0
	 */
	protected $maxLength = 0;

	/**
	 * Render an item description.
	 *
	 * @param   mixed  $command  The item to be described.
	 *
	 * @throws  \InvalidArgumentException
	 * @return  string  Rendered description.
	 *
	 * @since  1.0
	 */
	protected function renderItem($command)
	{
		if (!($command instanceof AbstractCommand))
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
	 *
	 * @since  1.0
	 */
	public function render()
	{
		// Count the max command length as column width.
		foreach ($this->items as $item)
		{
			/** @var $item AbstractCommand */
			$length = strlen($item->getName());

			if ($length > $this->maxLength)
			{
				$this->maxLength = $length;
			}
		}

		return parent::render();
	}
}
