<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

use Joomla\Registry\AbstractRegistryFormat;
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml format handler for Registry.
 *
 * @since  1.0
 */
class Yaml extends AbstractRegistryFormat
{
	/**
	 * Converts an object into a Yaml formatted string.
	 *
	 * @param   object  $object   Data source object.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  string  Yaml formatted string.
	 *
	 * @since   1.0
	 */
	public function objectToString($object, $options = array())
	{
		return Yaml::dump((array)$object);
	}

	/**
	 * Parse a JSON formatted string and convert it into an object.
	 *
	 * If the string is not in JSON format, this method will attempt to parse it as INI format.
	 *
	 * @param   string  $data     JSON formatted string to convert.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  object   Data object.
	 *
	 * @since   1.0
	 */
	public function stringToObject($data, array $options = array())
	{
		$data = trim($data);

		return Yaml::parse($data);
	}
}
