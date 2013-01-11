<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTTP
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;
use DirectoryIterator;

/**
 * HTTP factory class.
 *
 * @package     Joomla.Platform
 * @subpackage  HTTP
 * @since       12.1
 */
class Factory
{
	/**
	 * Method to recieve Http instance.
	 *
	 * @param   Registry  $options   Client options object.
	 * @param   mixed     $adapters  Adapter (string) or queue of adapters (array) to use for communication.
	 *
	 * @return  Http  Joomla Http class
	 *
	 * @since   12.1
	 */
	public static function getHttp(Registry $options = null, $adapters = null)
	{
		if (empty($options))
		{
			$options = new Registry;
		}
		return new Http($options, self::getAvailableDriver($options, $adapters));
	}

	/**
	 * Finds an available http transport object for communication
	 *
	 * @param   Registry  $options  Option for creating http transport object
	 * @param   mixed     $default  Adapter (string) or queue of adapters (array) to use
	 *
	 * @return  Transport  Interface sub-class
	 *
	 * @since   12.1
	 */
	public static function getAvailableDriver(Registry $options, $default = null)
	{
		if (is_null($default))
		{
			$availableAdapters = self::getHttpTransports();
		}
		else
		{
			settype($default, 'array');
			$availableAdapters = $default;
		}
		// Check if there is at least one available http transport adapter
		if (!count($availableAdapters))
		{
			return false;
		}
		foreach ($availableAdapters as $adapter)
		{
			$class = '\\Joomla\\Http\\Transport\\' . ucfirst($adapter);

			if ($class::isSupported())
			{
				return new $class($options);
			}
		}
		return false;
	}

	/**
	 * Get the http transport handlers
	 *
	 * @return  array  An array of available transport handlers
	 *
	 * @since   12.1
	 */
	public static function getHttpTransports()
	{
		$names = array();
		$iterator = new DirectoryIterator(__DIR__ . '/transport');

		foreach ($iterator as $file)
		{
			$fileName = $file->getFilename();

			// Only load for php files.
			// Note: DirectoryIterator::getExtension only available PHP >= 5.3.6
			if ($file->isFile() && substr($fileName, strrpos($fileName, '.') + 1) == 'php')
			{
				$names[] = substr($fileName, 0, strrpos($fileName, '.'));
			}
		}

		return $names;
	}
}
