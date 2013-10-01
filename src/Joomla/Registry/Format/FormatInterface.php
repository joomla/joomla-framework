<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

/**
 * Interface for Registry formats
 *
 * @since  1.0
 */
interface FormatInterface
{
	/**
	 * Converts an object into a formatted string.
	 *
	 * @param   object  $object   Data Source Object.
	 * @param   array   $options  An array of options for the formatter.
	 *
	 * @return  string  Formatted string.
	 *
	 * @since   1.0
	 */
	public function objectToString($object, array $options = null);

	/**
	 * Converts a formatted string into an object.
	 *
	 * @param   string  $data     Formatted string
	 * @param   array   $options  An array of options for the formatter.
	 *
	 * @return  object  Data Object
	 *
	 * @since   1.0
	 */
	public function stringToObject($data, array $options = array());

	/**
	 * Get the name of the format handled by this class.
	 *
	 * @return  string  The name
	 *
	 * @since   1.0
	 */
	public function getName();
}
