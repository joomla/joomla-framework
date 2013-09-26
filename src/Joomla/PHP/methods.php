<?php
/**
 * Part of the Joomla Framework Package
 *
 * @copyright  Copyright (C) 20013 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

if (!function_exists('with'))
{
	/**
	 * Return the given object. Useful for chaining.
	 *
	 * This method provides forward compatibility for the PHP 5.4 feature Class member access on instantiation.
	 * e.g. (new Foo)->bar().
	 * See: http://php.net/manual/en/migration54.new-features.php
	 *
	 * @param   mixed  $object  The object to return.
	 *
	 * @since  1.0
	 *
	 * @return mixed
	 */
	function with($object)
	{
		return $object;
	}
}
