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
use Joomla\Database\Driver;
use Joomla\Language\Text;
use Joomla\Client\Helper;
use Joomla\Date\Date;
use RuntimeException;
use Exception;

// Legacy classes.
use JApplication;
use JConfig;
use JVersion;

/**
 * Joomla Platform Factory class
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
	 * Language object instance
	 *
	 * @var    Language
	 * @since  1.0
	 */
	public static $language = null;

	/**
	 * Driver object instance
	 *
	 * @var    Driver
	 * @since  1.0
	 */
	public static $database = null;

	/**
	 * Get a application object.
	 *
	 * Returns the global {@link JApplication} object, only creating it if it doesn't already exist.
	 *
	 * @param   mixed   $id      A client identifier or name.
	 * @param   array   $config  An optional associative array of configuration settings.
	 * @param   string  $prefix  Application prefix
	 *
	 * @return  JApplication object
	 *
	 * @see     JApplication
	 * @since   1.0
	 * @throws  Exception
	 */
	public static function getApplication($id = null, array $config = array(), $prefix = 'J')
	{
		if (!self::$application)
		{
			if (!$id)
			{
				throw new Exception('Application Instantiation Error', 500);
			}

			self::$application = JApplication::getInstance($id, $config, $prefix);
		}

		return self::$application;
	}

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
				$file = JPATH_PLATFORM . '/config.php';
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
	 * @see     JSession
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
	 * Get a language object.
	 *
	 * Returns the global {@link JLanguage} object, only creating it if it doesn't already exist.
	 *
	 * @return  Language object
	 *
	 * @see     JLanguage
	 * @since   1.0
	 */
	public static function getLanguage()
	{
		if (!self::$language)
		{
			self::$language = self::createLanguage();
		}

		return self::$language;
	}

	/**
	 * Get a database object.
	 *
	 * Returns the global {@link JDatabaseDriver} object, only creating it if it doesn't already exist.
	 *
	 * @return  Driver
	 *
	 * @see     Driver
	 * @since   1.0
	 */
	public static function getDbo()
	{
		if (!self::$database)
		{
			// Get the debug configuration setting
			$conf = self::getConfig();
			$debug = $conf->get('debug');

			self::$database = self::createDbo();
			self::$database->setDebug($debug);
		}

		return self::$database;
	}

	/**
	 * Return the {@link JDate} object
	 *
	 * @param   mixed  $time      The initial time for the JDate object
	 * @param   mixed  $tzOffset  The timezone offset.
	 *
	 * @return  Date object
	 *
	 * @see     Date
	 * @since   1.0
	 */
	public static function getDate($time = 'now', $tzOffset = null)
	{
		static $classname;
		static $mainLocale;

		$language = self::getLanguage();
		$locale = $language->getTag();

		if (!isset($classname) || $locale != $mainLocale)
		{
			// Store the locale for future reference
			$mainLocale = $locale;

			if ($mainLocale !== false)
			{
				$classname = str_replace('-', '_', $mainLocale) . 'Date';

				if (!class_exists($classname))
				{
					// The class does not exist, default to JDate
					$classname = 'Joomla\\Date\\Date';
				}
			}
			else
			{
				// No tag, so default to Joomla\\Date\\Date
				$classname = 'Joomla\\Date\\Date';
			}
		}

		$key = $time . '-' . ($tzOffset instanceof \DateTimeZone ? $tzOffset->getName() : (string) $tzOffset);

		if (!isset(self::$dates[$classname][$key]))
		{
			self::$dates[$classname][$key] = new $classname($time, $tzOffset);
		}

		$date = clone self::$dates[$classname][$key];

		return $date;
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
	 * @return  Driver
	 *
	 * @see     Driver
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
			$db = Driver::getInstance($options);
		}
		catch (RuntimeException $e)
		{
			if (!headers_sent())
			{
				header('HTTP/1.1 500 Internal Server Error');
			}
			jexit('Database Error: ' . $e->getMessage());
		}

		$db->setDebug($debug);

		return $db;
	}

	/**
	 * Create a language object
	 *
	 * @return  Language object
	 *
	 * @see     Language
	 * @since   1.0
	 */
	protected static function createLanguage()
	{
		$conf = self::getConfig();
		$locale = $conf->get('language');
		$debug = $conf->get('debug_lang');
		$lang = Language::getInstance($locale, $debug);

		return $lang;
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
			$FTPOptions = Helper::getCredentials('ftp');
			$SCPOptions = Helper::getCredentials('scp');

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
