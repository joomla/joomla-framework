<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

use Joomla\Console\Console;

/**
 * Class ConsoleDescriptor
 *
 * @package  Joomla\Console\Descriptor
 * @since    1.0
 */
class ConsoleDescriptor extends Descriptor
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
%s
Usage:
  %s <cmd><command></cmd> <option>[options]</option>


Options:

{OPTIONS}

Available commands:

{COMMANDS}

EOF;

	/**
	 * Render an item description.
	 *
	 * @param   Console  $console  The item to br described.
	 *
	 * @throws  \InvalidArgumentException
	 * @return  string  Rendered description.
	 */
	protected function renderItem($console)
	{
		if (!($console instanceof Console))
		{
			throw new \InvalidArgumentException('Console descriptor need Console object to describe it.');
		}

		/** @var Console $console */
		$name        = $console->getName();
		$description = $console->getDescription();
		$version     = $console->getVersion();

		$description = $description ? $description . "\n" : '';

		// Get application file name
		$file = $_SERVER['argv'][0];
		$file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $file);
		$file = explode(DIRECTORY_SEPARATOR, $file);
		$file = array_pop($file);

		return sprintf($this->template, $name, $version, $description, $file);
	}
}
