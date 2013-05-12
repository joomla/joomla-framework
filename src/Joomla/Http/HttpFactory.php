<?php
/**
 * Part of the Joomla Framework Http Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;

use Joomla\Registry\Registry;

/**
 * HTTP factory class.
 *
 * @since  1.0
 */
class HttpFactory
{
	/**
	 * Method to recieve Http instance.
	 *
	 * @param   Registry  $options   Client options object.
	 * @param   mixed     $adapters  Adapter (string) or queue of adapters (array) to use for communication.
	 *
	 * @return  Http  Joomla Http class
	 *
	 * @since   1.0
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
	 * @return  TransportInterface  Interface sub-class
	 *
	 * @since   1.0
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
			/* @var  $class  TransportInterface */
			$class = '\\Joomla\\Http\\Transport\\' . ucfirst($adapter);

			if (class_exists($class))
			{
				if ($class::isSupported())
				{
					return new $class($options);
				}
			}
		}

		return false;
	}

	/**
	 * Get the http transport handlers
	 *
	 * @return  array  An array of available transport handlers
	 *
	 * @since   1.0
	 */
	public static function getHttpTransports()
	{
		$names = array();
		$iterator = new \DirectoryIterator(__DIR__ . '/Transport');

		/*  @var  $file  \DirectoryIterator */
		foreach ($iterator as $file)
		{
			$fileName = $file->getFilename();

			// Only load for php files.
			if ($file->isFile() && $file->getExtension() == 'php')
			{
				$names[] = substr($fileName, 0, strrpos($fileName, '.'));
			}
		}

		return $names;
	}
}
