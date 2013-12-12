<?php
/**
 * Part of the Joomla Framework Session Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Session\Storage;

use Joomla\Session\Storage;

/**
 * Google App Engine Memcached session storage handler for PHP
 *
 * @since  1.0
 */
class Gaememcached extends Memcached
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Optional parameters.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($options = array())
	{
		if (!self::isSupported())
		{
			throw new \RuntimeException('Memcached Extension is not available', 404);
		}

		Storage::__construct($options);
	}

	/**
	 * Register the functions of this class with PHP's session handler
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function register()
	{
		ini_set('session.save_handler', 'memcached');
	}

	/**
	 * Test to see if the SessionHandler is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	static public function isSupported()
	{

		if (isset($_SERVER['SERVER_SOFTWARE'])
			&& (strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false)
		)
		{
			return class_exists('Memcached');
		}

		return false;
	}
}
