<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Data\Tests;

use Joomla\Data;

/**
 * Derived JData class for testing.
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */
class JDataBuran extends Data\Data
{
	public $rocket = false;

	/**
	 * Method to set the test_value.
	 *
	 * @param   string  $value  The test value.
	 *
	 * @return  JData  Chainable.
	 *
	 * @since   1.0
	 */
	protected function setTestValue($value)
	{
		// Set the property as uppercase.
		return strtoupper($value);
	}
}
