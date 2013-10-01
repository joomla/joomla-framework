<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

use Joomla\Registry\Format\FormatInterface;

/**
 * JSON format handler for Registry.
 *
 * @since  1.0
 */
class JsonFormat implements FormatInterface
{
	/**
	 * Converts an object into a JSON formatted string.
	 *
	 * @param   object  $object   Data source object.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  string  JSON formatted string.
	 *
	 * @since   1.0
	 */
	public function objectToString($object, array $options = array())
	{
		return json_encode($object);
	}

	/**
	 * Parse a JSON formatted string and convert it into an object.
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
		$obj = json_decode(trim($data));

		return $obj;
	}

	/**
	 * Get the name of the format handled by this class.
	 *
	 * @return  string  The name
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return 'JSON';
	}
}
