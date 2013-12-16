<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Gcloudsqli;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\Mysqli;
use Psr\Log;

/**
 * Google Cloud SQL Database Driver based on mysqli
 *
 * @see    https://developers.google.com/appengine/docs/php/cloud-sql/
 * @since  1.0
 */
class GcloudsqliDriver extends Mysqli\MysqliDriver
{
	/**
	 * The name of the database driver -
	 * this is used to build.other class names
	 * and function calls, so it must match
	 * the actual php database driver in use
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $name = 'mysqli';
	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @since   1.0
	 */
	public function __construct($options)
	{
		// Pass initialisation through ancestry first.
		parent::__construct($options);

		// Retrieve configured options
		$options = $this->options;

		// Retrieve Google Cloud SQL socket for GAE from host string
		list($host, $s) = explode('|', $options['host']);

		// Assume we are n ot running on GAE
		$socket = null;
		/**
		 *  Google App Engine Server Software is either
		 * Development or
		 * Google App Engine
		 */
		if ( isset($_SERVER['SERVER_SOFTWARE']) )
		{
			// Only GAE Instance uses socket
			$beginsWith = substr($_SERVER['SERVER_SOFTWARE'],11);
			if ( $beginsWith == 'Google App ')
			{
				$host = 'localhost';
				$socket = $s;
			}
		}
		// Reset host and socket options for GAE
		$options['host'] = $host;
		$options['socket'] = $socket;

		// Save the updated options
		$this->options = $options;

	}

	/**
	 * Connects to the database if needed.
	 *
	 * @return  void  Returns void if the database connected successfully.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function connect()
	{
		if ($this->connection)
		{

			return;
		}

		// If we are not running under Google App Engine we can use the regular MySQLi driver
		if(!isset($_SERVER['SERVER_SOFTWARE']) || strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') === false) {
			parent::connect();

			return;
		}

		// Make sure the mysqli extension for PHP is installed and enabled.
		if (!static::isSupported())
		{
			throw new \RuntimeException('The Google CloudSQL requires the mysqli extension which is not available');
		}

		$this->connection = @mysqli_connect(
			$this->options['host'], $this->options['user'], $this->options['password'], null, $this->options['port'], $this->options['socket']
		);

		// Attempt to connect to the server.
		if (!$this->connection)
		{
			throw new \RuntimeException('Could not connect to Google Cloud SQL from GAE.');
		}

		// Set sql_mode to non_strict mode
		mysqli_query($this->connection, "SET @@SESSION.sql_mode = '';");

		// If auto-select is enabled select the given database.
		if ($this->options['select'] && !empty($this->options['database']))
		{
			$this->select($this->options['database']);
		}

		// Set charactersets (needed for MySQL 4.1.2+).
		$this->setUTF();

		return;
	}


}
