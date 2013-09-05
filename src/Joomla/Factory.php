<?php
/**
 * Part of the Joomla Framework
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla;

use Joomla\Registry\Registry;
use Joomla\Language\Language;
use Joomla\Filesystem\Stream;
use Joomla\Session\Session;
use Joomla\Database\DatabaseDriver;
use Joomla\Language\Text;
use Joomla\Client\ClientHelper;
use Joomla\Date\Date;

// Legacy classes.
use JApplication;
use JConfig;
use JVersion;

/**
 * Joomla Framework Factory class
 *
 * @since  1.0
 */
abstract class Factory
{
	/**
	 * Application object instance
	 *
	 * @var    JApplication
	 * @since  1.0
	 */
	public static $application = null;

	/**
	 * Configuration object instance
	 *
	 * @var    JConfig
	 * @since  1.0
	 */
	public static $config = null;

	/**
	 * Date object array
	 *
	 * @var    array
	 * @since  1.0
	 */
	public static $dates = array();

	/**
	 * Session object instance
	 *
	 * @var    Session
	 * @since  1.0
	 */
	public static $session = null;

	/**
	 * Driver object instance
	 *
	 * @var    DatabaseDriver
	 * @since  1.0
	 */
	public static $database = null;

	/**
	 * Get a configuration object
	 *
	 * Returns the global {@link Registry} object, only creating it if it doesn't already exist.
	 *
	 * @param   string  $file       The path to the configuration file
	 * @param   string  $type       The type of the configuration file
	 * @param   string  $namespace  The namespace of the configuration file
	 *
	 * @return  Registry
	 *
	 * @see     Registry
	 * @since   1.0
	 */
	public static function getConfig($file = null, $type = 'PHP', $namespace = '')
	{
		if (!self::$config)
		{
			if ($file === null)
			{
				$file = JPATH_FRAMEWORK . '/config.php';
			}

			self::$config = self::createConfig($file, $type, $namespace);
		}

		return self::$config;
	}

	/**
	 * Get a session object.
	 *
	 * Returns the global {@link JSession} object, only creating it if it doesn't already exist.
	 *
	 * @param   array  $options  An array containing session options
	 *
	 * @return  Session object
	 *
	 * @see     Session
	 * @since   1.0
	 */
	public static function getSession(array $options = array())
	{
		if (!self::$session)
		{
			self::$session = self::createSession($options);
		}

		return self::$session;
	}

	/**
	 * Get a database object.
	 *
	 * Returns the global {@link DatabaseDriver} object, only creating it if it doesn't already exist.
	 *
	 * @return  DatabaseDriver
	 *
	 * @see     DatabaseDriver
	 * @since   1.0
	 */
	public static function getDbo()
	{
		if (!self::$database)
		{
			self::$database = self::createDbo();
		}

		return self::$database;
	}

	/**
	 * Create a configuration object
	 *
	 * @param   string  $file       The path to the configuration file.
	 * @param   string  $type       The type of the configuration file.
	 * @param   string  $namespace  The namespace of the configuration file.
	 *
	 * @return  Registry
	 *
	 * @see     Registry
	 * @since   1.0
	 */
	protected static function createConfig($file, $type = 'PHP', $namespace = '')
	{
		if (is_file($file))
		{
			include_once $file;
		}

		// Create the registry with a default namespace of config
		$registry = new Registry;

		// Sanitize the namespace.
		$namespace = ucfirst((string) preg_replace('/[^A-Z_]/i', '', $namespace));

		// Build the config name.
		$name = 'JConfig' . $namespace;

		// Handle the PHP configuration type.
		if ($type == 'PHP' && class_exists($name))
		{
			// Create the JConfig object
			$config = new $name;

			// Load the configuration values into the registry
			$registry->loadObject($config);
		}

		return $registry;
	}

	/**
	 * Create a session object
	 *
	 * @param   array  $options  An array containing session options
	 *
	 * @return  Session object
	 *
	 * @since   1.0
	 */
	protected static function createSession(array $options = array())
	{
		// Get the editor configuration setting
		$conf = self::getConfig();
		$handler = $conf->get('session_handler', 'none');

		// Config time is in minutes
		$options['expire'] = ($conf->get('lifetime')) ? $conf->get('lifetime') * 60 : 900;

		$session = Session::getInstance($handler, $options);

		if ($session->getState() == 'expired')
		{
			$session->restart();
		}

		return $session;
	}

	/**
	 * Create an database object
	 *
	 * @return  DatabaseDriver
	 *
	 * @see     DatabaseDriver
	 * @since   1.0
	 */
	protected static function createDbo()
	{
		$conf = self::getConfig();

		$host = $conf->get('host');
		$user = $conf->get('user');
		$password = $conf->get('password');
		$database = $conf->get('db');
		$prefix = $conf->get('dbprefix');
		$driver = $conf->get('dbtype');
		$debug = $conf->get('debug');

		$options = array('driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => $database, 'prefix' => $prefix);

		try
		{
			$db = DatabaseDriver::getInstance($options);
		}
		catch (\RuntimeException $e)
		{
			if (!headers_sent())
			{
				header('HTTP/1.1 500 Internal Server Error');
			}

			exit('Database Error: ' . $e->getMessage());
		}

		$db->setDebug($debug);

		return $db;
	}

	/**
	 * Creates a new stream object with appropriate prefix
	 *
	 * @param   boolean  $use_prefix   Prefix the connections for writing
	 * @param   boolean  $use_network  Use network if available for writing; use false to disable (e.g. FTP, SCP)
	 * @param   string   $ua           UA User agent to use
	 * @param   boolean  $uamask       User agent masking (prefix Mozilla)
	 *
	 * @return  Stream
	 *
	 * @see     Stream
	 * @since   1.0
	 */
	public static function getStream($use_prefix = true, $use_network = true, $ua = null, $uamask = false)
	{
		// Setup the context; Joomla! UA and overwrite
		$context = array();
		$version = new JVersion;

		// Set the UA for HTTP and overwrite for FTP
		$context['http']['user_agent'] = $version->getUserAgent($ua, $uamask);
		$context['ftp']['overwrite'] = true;

		if ($use_prefix)
		{
			$FTPOptions = ClientHelper::getCredentials('ftp');
			$SCPOptions = ClientHelper::getCredentials('scp');

			if ($FTPOptions['enabled'] == 1 && $use_network)
			{
				$prefix = 'ftp://' . $FTPOptions['user'] . ':' . $FTPOptions['pass'] . '@' . $FTPOptions['host'];
				$prefix .= $FTPOptions['port'] ? ':' . $FTPOptions['port'] : '';
				$prefix .= $FTPOptions['root'];
			}
			elseif ($SCPOptions['enabled'] == 1 && $use_network)
			{
				$prefix = 'ssh2.sftp://' . $SCPOptions['user'] . ':' . $SCPOptions['pass'] . '@' . $SCPOptions['host'];
				$prefix .= $SCPOptions['port'] ? ':' . $SCPOptions['port'] : '';
				$prefix .= $SCPOptions['root'];
			}
			else
			{
				$prefix = JPATH_ROOT . '/';
			}

			$retval = new Stream($prefix, JPATH_ROOT, $context);
		}
		else
		{
			$retval = new Stream('', '', $context);
		}

		return $retval;
	}
}
