<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

use Joomla\Registry\AbstractRegistryFormat;
use Symfony\Component\Yaml\Parser as SymfonyYamlParser;
use Symfony\Component\Yaml\Dumper as SymfonyYamlDumper;

/**
 * YAML format handler for Registry.
 *
 * @since  1.0
 */
class Yaml extends AbstractRegistryFormat
{
	private $parser;
	private $dumper;

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

		if (null === $this->dumper) {
			$this->dumper = new SymfonyYamlDumper();
		}

		return $this->dumper->dump($array, 2, 0);
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
		if (null === $this->parser) {
			$this->parser = new SymfonyYamlParser();
		}

		$array = $this->parser->parse(trim($data));

		return json_decode(json_encode($array));
	}
}
