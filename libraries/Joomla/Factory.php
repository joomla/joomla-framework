<?php
/**
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla;

defined('JPATH_PLATFORM') or die;

// @todo JSimplepieFactory is removed
use Joomla\Mail\Helper as MailHelper;
use Joomla\Document\Document;
use Joomla\Registry\Registry;
use Joomla\Language\Language;
use Joomla\Filesystem\Stream;
use Joomla\Session\Session;
use Joomla\Database\Driver;
use Joomla\Language\Text;
use Joomla\Access\Access;
use Joomla\Client\Helper;
use Joomla\Cache\Cache;
use Joomla\Date\Date;
use Joomla\Mail\Mail;
use Joomla\User\User;
use Joomla\Log\Log;
use Joomla\Uri\Uri;
use BadMethodCallException;
use RuntimeException;
use Exception;

// Legacy classes.
use JApplication;
use JConfig;
use JVersion;

/**
 * Joomla Platform Factory class
 *
 * @package  Joomla.Platform
 * @since    11.1
 */
abstract class Factory
{
	/**
	 * @var    JApplication
	 * @since  11.1
	 */
	public static $application = null;

	/**
	 * @var    Cache
	 * @since  11.1
	 */
	public static $cache = null;

	/**
	 * @var    JConfig
	 * @since  11.1
	 */
	public static $config = null;

	/**
	 * @var    array
	 * @since  11.3
	 */
	public static $dates = array();

	/**
	 * @var    Session
	 * @since  11.1
	 */
	public static $session = null;

	/**
	 * @var    Language
	 * @since  11.1
	 */
	public static $language = null;

	/**
	 * @var    Document
	 * @since  11.1
	 */
	public static $document = null;

	/**
	 * @var    Access
	 * @since  11.1
	 * @deprecated  13.3
	 */
	public static $acl = null;

	/**
	 * @var    Driver
	 * @since  11.1
	 */
	public static $database = null;

	/**
	 * @var    Mail
	 * @since  11.1
	 */
	public static $mailer = null;

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
	 * @since   11.1
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
	 * Returns the global {@link JRegistry} object, only creating it if it doesn't already exist.
	 *
	 * @param   string  $file       The path to the configuration file
	 * @param   string  $type       The type of the configuration file
	 * @param   string  $namespace  The namespace of the configuration file
	 *
	 * @return  Registry
	 *
	 * @see     JRegistry
	 * @since   11.1
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
	 * @since   11.1
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
	 * @since   11.1
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
	 * Get a document object.
	 *
	 * Returns the global {@link JDocument} object, only creating it if it doesn't already exist.
	 *
	 * @return  Document object
	 *
	 * @see     JDocument
	 * @since   11.1
	 */
	public static function getDocument()
	{
		if (!self::$document)
		{
			self::$document = self::createDocument();
		}

		return self::$document;
	}

	/**
	 * Get an user object.
	 *
	 * Returns the global {@link User} object, only creating it if it doesn't already exist.
	 *
	 * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @return  User object
	 *
	 * @see     JUser
	 * @since   11.1
	 */
	public static function getUser($id = null)
	{
		$instance = self::getSession()->get('user');

		if (is_null($id))
		{
			if (!($instance instanceof User))
			{
				$instance = User::getInstance();
			}
		}
		elseif (!($instance instanceof User) || $instance->id != $id)
		{
			$instance = User::getInstance($id);
		}

		return $instance;
	}

	/**
	 * Get a cache object
	 *
	 * Returns the global {@link Cache} object
	 *
	 * @param   string  $group    The cache group name
	 * @param   string  $handler  The handler to use
	 * @param   string  $storage  The storage method
	 *
	 * @return  \Joomla\Cache\Controller object
	 *
	 * @see     Cache
	 */
	public static function getCache($group = '', $handler = 'callback', $storage = null)
	{
		$hash = md5($group . $handler . $storage);

		if (isset(self::$cache[$hash]))
		{
			return self::$cache[$hash];
		}
		$handler = ($handler == 'function') ? 'callback' : $handler;

		$options = array('defaultgroup' => $group);

		if (isset($storage))
		{
			$options['storage'] = $storage;
		}

		$cache = Cache::getInstance($handler, $options);

		self::$cache[$hash] = $cache;

		return self::$cache[$hash];
	}

	/**
	 * Get an authorization object
	 *
	 * Returns the global {@link JAccess} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  Access object
	 *
	 * @deprecated  13.3  Use JAccess directly.
	 */
	public static function getACL()
	{
		Log::add(__METHOD__ . ' is deprecated. Use JAccess directly.', Log::WARNING, 'deprecated');

		if (!self::$acl)
		{
			self::$acl = new Access;
		}

		return self::$acl;
	}

	/**
	 * Get a database object.
	 *
	 * Returns the global {@link JDatabaseDriver} object, only creating it if it doesn't already exist.
	 *
	 * @return  Driver
	 *
	 * @see     Driver
	 * @since   11.1
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
	 * Get a mailer object.
	 *
	 * Returns the global {@link JMail} object, only creating it if it doesn't already exist.
	 *
	 * @return  Mail object
	 *
	 * @see     Mail
	 * @since   11.1
	 */
	public static function getMailer()
	{
		if (!self::$mailer)
		{
			self::$mailer = self::createMailer();
		}
		$copy = clone self::$mailer;

		return $copy;
	}

	/**
	 * Get a parsed XML Feed Source
	 *
	 * @param   string   $url         Url for feed source.
	 * @param   integer  $cache_time  Time to cache feed for (using internal cache mechanism).
	 *
	 * @return  mixed  SimplePie parsed object on success, false on failure.
	 *
	 * @since   11.1
	 * @deprecated  13.3  Use JSimplepieFactory::getFeedParser() instead.
	 */
	public static function getFeedParser($url, $cache_time = 0)
	{
		if (!class_exists('JSimplepieFactory'))
		{
			throw new BadMethodCallException('JSimplepieFactory not found');
		}

		Log::add(__METHOD__ . ' is deprecated.   Use JSimplepieFactory::getFeedParser() instead.', Log::WARNING, 'deprecated');

		return JSimplepieFactory::getFeedParser($url, $cache_time);
	}

	/**
	 * Reads a XML file.
	 *
	 * @param   string   $data    Full path and file name.
	 * @param   boolean  $isFile  true to load a file or false to load a string.
	 *
	 * @return  mixed    JXMLElement or SimpleXMLElement on success or false on error.
	 *
	 * @see     JXMLElement
	 * @since   11.1
	 * @note    When JXMLElement is not present a SimpleXMLElement will be returned.
	 * @deprecated  13.3 Use SimpleXML directly.
	 */
	public static function getXML($data, $isFile = true)
	{
		Log::add(__METHOD__ . ' is deprecated. Use SimpleXML directly.', Log::WARNING, 'deprecated');

		$class = 'SimpleXMLElement';

		if (class_exists('JXMLElement'))
		{
			$class = 'JXMLElement';
		}

		// Disable libxml errors and allow to fetch error information as needed
		libxml_use_internal_errors(true);

		if ($isFile)
		{
			// Try to load the XML file
			$xml = simplexml_load_file($data, $class);
		}
		else
		{
			// Try to load the XML string
			$xml = simplexml_load_string($data, $class);
		}

		if ($xml === false)
		{
			Log::add(Text::_('JLIB_UTIL_ERROR_XML_LOAD'), Log::WARNING, 'jerror');

			if ($isFile)
			{
				Log::add($data, Log::WARNING, 'jerror');
			}

			foreach (libxml_get_errors() as $error)
			{
				Log::add($error->message, Log::WARNING, 'jerror');
			}
		}

		return $xml;
	}

	/**
	 * Return a reference to the {@link JURI} object
	 *
	 * @param   string  $uri  Uri name.
	 *
	 * @return  Uri object
	 *
	 * @see     JURI
	 * @since   11.1
	 * @deprecated  13.3 Use JURI directly.
	 */
	public static function getURI($uri = 'SERVER')
	{
		Log::add(__METHOD__ . ' is deprecated. Use JURI directly.', Log::WARNING, 'deprecated');

		return Uri::getInstance($uri);
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
	 * @since   11.1
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
					$classname = 'JDate';
				}
			}
			else
			{
				// No tag, so default to JDate
				$classname = 'JDate';
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
	 * @since   11.1
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
	 * @since   11.1
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
	 * @since   11.1
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
	 * Create a mailer object
	 *
	 * @return  Mail object
	 *
	 * @see     Mail
	 * @since   11.1
	 */
	protected static function createMailer()
	{
		$conf = self::getConfig();

		$smtpauth = ($conf->get('smtpauth') == 0) ? null : 1;
		$smtpuser = $conf->get('smtpuser');
		$smtppass = $conf->get('smtppass');
		$smtphost = $conf->get('smtphost');
		$smtpsecure = $conf->get('smtpsecure');
		$smtpport = $conf->get('smtpport');
		$mailfrom = $conf->get('mailfrom');
		$fromname = $conf->get('fromname');
		$mailer = $conf->get('mailer');

		// Create a JMail object
		$mail = Mail::getInstance();

		// Set default sender without Reply-to
		$mail->SetFrom(MailHelper::cleanLine($mailfrom), MailHelper::cleanLine($fromname), 0);

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp':
				$mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;

			case 'sendmail':
				$mail->IsSendmail();
				break;

			default:
				$mail->IsMail();
				break;
		}

		return $mail;
	}

	/**
	 * Create a language object
	 *
	 * @return  Language object
	 *
	 * @see     JLanguage
	 * @since   11.1
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
	 * Create a document object
	 *
	 * @return  Document object
	 *
	 * @see     Document
	 * @since   11.1
	 */
	protected static function createDocument()
	{
		$lang = self::getLanguage();

		$input = self::getApplication()->input;
		$type = $input->get('format', 'html', 'word');

		$attributes = array('charset' => 'utf-8', 'lineend' => 'unix', 'tab' => '  ', 'language' => $lang->getTag(),
			'direction' => $lang->isRTL() ? 'rtl' : 'ltr');

		return Document::getInstance($type, $attributes);
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
	 * @see Stream
	 * @since   11.1
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
