<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;
use Joomla\Console\Option\Option;

/**
 * Class Option Descriptor
 *
 * @package  Joomla\Console\Descriptor
 * @since    1.0
 */
class OptionDescriptor extends Descriptor
{
	const TEMPLATE = <<<EOF
  <info>%s</info>
%s

EOF;

	const TEMPLATE_LINE_BODY = '      %s';

	/**
	 * Render an item description.
	 *
	 * @param   mixed  $option  The item to br described.
	 *
	 * @throws  \InvalidArgumentException
	 * @return  string  Rendered description.
	 */
	public function renderItem($option)
	{
		if (!($option instanceof Option))
		{
			throw new \InvalidArgumentException('Command descriptor need Command object to describe it.');
		}

		/** @var Command $command */
		$name        = $option->getName();
		$aliases     = $option->getAlias();
		$description = $option->getDescription() ?: 'No description';

		// Merge aliases
		array_unshift($aliases, $name);

		foreach ($aliases as &$alias)
		{
			$alias = strlen($alias) > 1 ? '--' . $alias : '-' . $alias;
		}

		// Sets the body indent.
		$body = array();

		$description = explode("\n", $description);

		foreach ($description as $line)
		{
			$line = trim($line);
			$line = sprintf(self::TEMPLATE_LINE_BODY, $line);
			$body[] = $line;
		}

		return sprintf(self::TEMPLATE, implode(' / ', $aliases), implode("\n", $body));
	}

	/**
	 * Render all items description.
	 *
	 * @return  string
	 */
	public function render()
	{
		return parent::render();
	}
}
