<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

use Joomla\Registry\AbstractRegistryFormat;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

/**
 * YAML format handler for Registry.
 *
 * @since  1.0
 */
class Yaml extends AbstractRegistryFormat
{
	/**
	 * Converts an object into a YAML formatted string.
	 *
	 * @param   object  $object   Data source object.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  string  YAML formatted string.
	 *
	 * @since   1.0
	 */
	public function objectToString($object, $options = array())
	{
		$array = json_decode(json_encode($object), true);

		return SymfonyYaml::dump($array);
	}

	/**
	 * Parse a YAML formatted string and convert it into an object.
	 *
	 * @param   string  $data     YAML formatted string to convert.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  object  Data object.
	 *
	 * @since   1.0
	 */
	public function stringToObject($data, array $options = array())
	{
		$array = SymfonyYaml::parse(trim($data));

		return json_decode(json_encode($array));
	}
}
