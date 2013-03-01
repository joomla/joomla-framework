<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Data
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Derived JDataSet class for testing.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Data
 * @since       12.1
 *
 * @method      launch() launch(string $status)
 */
class JDataVostok extends Joomla\Data\Data
{
	/**
	 * An array method.
	 *
	 * @param   string  $status  A method argument.
	 *
	 * @return  string  The return value for the method.
	 *
	 * @since   12.3
	 */
	public function launch($status)
	{
		return $status;
	}

	/**
	 * Set an object property.
	 *
	 * @param   string  $property  The property name.
	 * @param   mixed   $value     The property value.
	 *
	 * @return  mixed  The property value.
	 *
	 * @since   12.3
	 */
	protected function setProperty($property, $value)
	{
		switch ($property)
		{
			case 'successful':
				$value = strtoupper($value);
				break;
		}

		return parent::setProperty($property, $value);
	}
}
