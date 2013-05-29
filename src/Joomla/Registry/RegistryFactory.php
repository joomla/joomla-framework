<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry;

use Joomla\Registry\Registry;
use Joomla\Registry\Format\IniFormat;
use Joomla\Registry\Format\JsonFormat;
use Joomla\Registry\Format\PhpFormat;
use Joomla\Registry\Format\XmlFormat;
use Joomla\Registry\Format\YamlFormat;

use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Dumper as YamlDumper;

/**
 * Factory for the Registry Package
 *
 * @since  1.0
 */
class RegistryFactory
{
	/**
	 * Returns a Registry object with all default Formats.
	 *
	 * @return  Registry  The Registry object.
	 *
	 * @since   1.0
	 */
	public static function getRegistry()
	{
		$instance = new Registry;
		$instance->registerFormat(new IniFormat);
		$instance->registerFormat(new JsonFormat);
		$instance->registerFormat(new PhpFormat);
		$instance->registerFormat(new XmlFormat);

		if (class_exists('Symfony\Component\Yaml\Parser') && class_exists('Symfony\Component\Yaml\Dumper'))
		{
			$instance->registerFormat(new YamlFormat(new YamlParser, new YamlDumper));
		}

		return $instance;
	}
}
