<?php
/**
 * Part of the Joomla Framework Client Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filesystem\Clients;

use Joomla\Log\Log;

/** Error Codes:
 * - 30 : Unable to connect to host
 * - 31 : Not connected
 * - 32 : Unable to send command to server
 * - 33 : Bad username
 * - 34 : Bad password
 * - 35 : Bad response
 * - 36 : Passive mode failed
 * - 37 : Data transfer error
 * - 38 : Local filesystem error
 */

if (!defined('CRLF'))
{
	define('CRLF', "\r\n");
}

if (!defined("FTP_AUTOASCII"))
{
	define("FTP_AUTOASCII", -1);
}

if (!defined("FTP_BINARY"))
{
	define("FTP_BINARY", 1);
}

if (!defined("FTP_ASCII"))
{
	define("FTP_ASCII", 0);
}

if (!defined('FTP_NATIVE'))
{
	define('FTP_NATIVE', (function_exists('ftp_connect')) ? 1 : 0);
}

/**
 * FTP client class
 *
 * @since  1.0
 */
class FtpClient
{
	/**
	 * @var    resource  Socket resource
	 * @since  1.0
	 */
	private $conn = null;

	/**
	 * @var    resource  Data port connection resource
	 * @since  1.0
	 */
	private $dataconn = null;

	/**
	 * @var    array  Passive connection information
	 * @since  1.0
	 */
	private $pasv = null;

	/**
	 * @var    string  Response Message
	 * @since  1.0
	 */
	private $response = null;

	/**
	 * @var    integer  Timeout limit
	 * @since  1.0
	 */
	private $timeout = 15;

	/**
	 * @var    integer  Transfer Type
	 * @since  1.0
	 */
	private $type = null;

	/**
	 * @var    array  Array to hold ascii format file extensions
	 * @since   1.0
	 */
	private $autoAscii = array(
		"asp",
		"bat",
		"c",
		"cpp",
		"csv",
		"h",
		"htm",
		"html",
		"shtml",
		"ini",
		"inc",
		"log",
		"php",
		"php3",
		"pl",
		"perl",
		"sh",
		"sql",
		"txt",
		"xhtml",
		"xml");

	/**
	 * Array to hold native line ending characters
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $lineEndings = array('UNIX' => "\n", 'WIN' => "\r\n");

	/**
	 * @var    array  JClientFtp instances container.
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * JClientFtp object constructor
	 *
	 * @param   array  $options  Associative array of options to set
	 *
	 * @since   1.0
	 */
	public function __construct(array $options = array())
	{
		// If default transfer type is not set, set it to autoascii detect
		if (!isset($options['type']))
		{
			$options['type'] = FTP_BINARY;
		}

		$this->setOptions($options);

		if (FTP_NATIVE)
		{
			// Autoloading fails for Buffer as the class is used as a stream handler
			class_exists('Joomla\\Filesystem\\Buffer');
		}
	}

	/**
	 * JClientFtp object destructor
	 *
	 * Closes an existing connection, if we have one
	 *
	 * @since   1.0
	 */
	public function __destruct()
	{
		if (is_resource($this->conn))
		{
			$this->quit();
		}
	}

	/**
	 * Returns the global FTP connector object, only creating it
	 * if it doesn't already exist.
	 *
	 * You may optionally specify a username and password in the parameters. If you do so,
	 * you may not login() again with different credentials using the same object.
	 * If you do not use this option, you must quit() the current connection when you
	 * are done, to free it for use by others.
	 *
	 * @param   string  $host     Host to connect to
	 * @param   string  $port     Port to connect to
	 * @param   array   $options  Array with any of these options: type=>[FTP_AUTOASCII|FTP_ASCII|FTP_BINARY], timeout=>(int)
	 * @param   string  $user     Username to use for a connection
	 * @param   string  $pass     Password to use for a connection
	 *
	 * @return  FtpClient  The FTP Client object.
	 *
	 * @since   1.0
	 */
	public static function getInstance($host = '127.0.0.1', $port = '21', array $options = array(), $user = null, $pass = null)
	{
		$signature = $user . ':' . $pass . '@' . $host . ":" . $port;

		// Create a new instance, or set the options of an existing one
		if (!isset(self::$instances[$signature]) || !is_object(self::$instances[$signature]))
		{
			self::$instances[$signature] = new static($options);
		}
		else
		{
			self::$instances[$signature]->setOptions($options);
		}

		// Connect to the server, and login, if requested
		if (!self::$instances[$signature]->isConnected())
		{
			$return = self::$instances[$signature]->connect($host, $port);

			if ($return && $user !== null && $pass !== null)
			{
				self::$instances[$signature]->login($user, $pass);
			}
		}

		return self::$instances[$signature];
	}

	/**
	 * Set client options
	 *
	 * @param   array  $options  Associative array of options to set
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function setOptions(array $options)
	{
		if (isset($options['type']))
		{
			$this->type = $options['type'];
		}

		if (isset($options['timeout']))
		{
			$this->timeout = $options['timeout'];
		}

		return true;
	}

	/**
	 * Method to connect to a FTP server
	 *
	 * @param   string   $host  Host to connect to [Default: 127.0.0.1]
	 * @param   integer  $port  Port to connect on [Default: port 21]
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function connect($host = '127.0.0.1', $port = 21)
	{
		$errno = null;
		$err = null;

		// If already connected, return
		if (is_resource($this->conn))
		{
			return true;
		}

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			$this->conn = @ftp_connect($host, $port, $this->timeout);

			if ($this->conn === false)
			{
				Log::add(sprintf('%1$s: Could not connect to host " %2$s " on port " %3$s "', __METHOD__, $host, $port), Log::WARNING, 'jerror');

				return false;
			}

			// Set the timeout for this connection
			ftp_set_option($this->conn, FTP_TIMEOUT_SEC, $this->timeout);

			return true;
		}

		// Connect to the FTP server.
		$this->conn = @ fsockopen($host, $port, $errno, $err, $this->timeout);

		if (!$this->conn)
		{
			Log::add(
				sprintf(
					'%1$s: Could not connect to host " %2$s " on port " %3$s ". Socket error number: %4$s and error message: %5$s',
					__METHOD__,
					$host,
					$port,
					$errno,
					$err
				),
				Log::WARNING,
				'jerror');

			return false;
		}

		// Set the timeout for this connection
		socket_set_timeout($this->conn, $this->timeout, 0);

		// Check for welcome response code
		if (!$this->_verifyResponse(220))
		{
			Log::add(sprintf('%1$s: Bad response. Server response: %2$s [Expected: 220]', __METHOD__, $this->response), Log::WARNING, 'jerror');

			return false;
		}

		return true;
	}

	/**
	 * Method to determine if the object is connected to an FTP server
	 *
	 * @return  boolean  True if connected
	 *
	 * @since   1.0
	 */
	public function isConnected()
	{
		return is_resource($this->conn);
	}

	/**
	 * Method to login to a server once connected
	 *
	 * @param   string  $user  Username to login to the server
	 * @param   string  $pass  Password to login to the server
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function login($user = 'anonymous', $pass = 'jftp@joomla.org')
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_login($this->conn, $user, $pass) === false)
			{
				Log::add('JFTP::login: Unable to login', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send the username
		if (!$this->_putCmd('USER ' . $user, array(331, 503)))
		{
			Log::add(
				sprintf('%1$s: Bad Username. Server response: %2$s [Expected: 331]. Username sent: %3$s', __METHOD__, $this->response, $user),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// If we are already logged in, continue :)
		if ($this->_responseCode == 503)
		{
			return true;
		}

		// Send the password
		if (!$this->_putCmd('PASS ' . $pass, 230))
		{
			Log::add(sprintf('%1$s: Bad Password. Server response: %2$s [Expected: 230].', __METHOD__, $this->response), Log::WARNING, 'jerror');

			return false;
		}

		return true;
	}

	/**
	 * Method to quit and close the connection
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function quit()
	{
		// If native FTP support is enabled lets use it...
		if (FTP_NATIVE)
		{
			@ftp_close($this->conn);

			return true;
		}

		// Logout and close connection
		@fwrite($this->conn, "QUIT\r\n");
		@fclose($this->conn);

		return true;
	}

	/**
	 * Method to retrieve the current working directory on the FTP server
	 *
	 * @return  string   Current working directory
	 *
	 * @since   1.0
	 */
	public function pwd()
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (($ret = @ftp_pwd($this->conn)) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return $ret;
		}

		$match = array(null);

		// Send print working directory command and verify success
		if (!$this->_putCmd('PWD', 257))
		{
			Log::add(sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 257]', __METHOD__, $this->response), Log::WARNING, 'jerror');

			return false;
		}

		// Match just the path
		preg_match('/"[^"\r\n]*"/', $this->response, $match);

		// Return the cleaned path
		return preg_replace("/\"/", "", $match[0]);
	}

	/**
	 * Method to system string from the FTP server
	 *
	 * @return  string   System identifier string
	 *
	 * @since   1.0
	 */
	public function syst()
	{
		// If native FTP support is enabled lets use it...
		if (FTP_NATIVE)
		{
			if (($ret = @ftp_systype($this->conn)) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}
		}
		else
		{
			// Send print working directory command and verify success
			if (!$this->_putCmd('SYST', 215))
			{
				Log::add(sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 215]', __METHOD__, $this->response), Log::WARNING, 'jerror');

				return false;
			}

			$ret = $this->response;
		}

		// Match the system string to an OS
		if (strpos(strtoupper($ret), 'MAC') !== false)
		{
			$ret = 'MAC';
		}
		elseif (strpos(strtoupper($ret), 'WIN') !== false)
		{
			$ret = 'WIN';
		}
		else
		{
			$ret = 'UNIX';
		}

		// Return the os type
		return $ret;
	}

	/**
	 * Method to change the current working directory on the FTP server
	 *
	 * @param   string  $path  Path to change into on the server
	 *
	 * @return  boolean True if successful
	 *
	 * @since   1.0
	 */
	public function chdir($path)
	{
		// If native FTP support is enabled lets use it...
		if (FTP_NATIVE)
		{
			if (@ftp_chdir($this->conn, $path) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send change directory command and verify success
		if (!$this->_putCmd('CWD ' . $path, 250))
		{
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 250].  Sent path: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to reinitialise the server, ie. need to login again
	 *
	 * NOTE: This command not available on all servers
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function reinit()
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_site($this->conn, 'REIN') === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send reinitialise command to the server
		if (!$this->_putCmd('REIN', 220))
		{
			Log::add(sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 220]', __METHOD__, $this->response), Log::WARNING, 'jerror');

			return false;
		}

		return true;
	}

	/**
	 * Method to rename a file/folder on the FTP server
	 *
	 * @param   string  $from  Path to change file/folder from
	 * @param   string  $to    Path to change file/folder to
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function rename($from, $to)
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_rename($this->conn, $from, $to) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send rename from command to the server
		if (!$this->_putCmd('RNFR ' . $from, 350))
		{
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 350].  From path sent: %3$s', __METHOD__, $this->response, $from),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Send rename to command to the server
		if (!$this->_putCmd('RNTO ' . $to, 250))
		{
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 250].  To path sent: %3$s', __METHOD__, $this->response, $to),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to change mode for a path on the FTP server
	 *
	 * @param   string  $path  Path to change mode on
	 * @param   mixed   $mode  Octal value to change mode to, e.g. '0777', 0777 or 511 (string or integer)
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function chmod($path, $mode)
	{
		// If no filename is given, we assume the current directory is the target
		if ($path == '')
		{
			$path = '.';
		}

		// Convert the mode to a string
		if (is_int($mode))
		{
			$mode = decoct($mode);
		}

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_site($this->conn, 'CHMOD ' . $mode . ' ' . $path) === false)
			{
				if (!defined('PHP_WINDOWS_VERSION_MAJOR'))
				{
					Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');
				}

				return false;
			}

			return true;
		}

		// Send change mode command and verify success [must convert mode from octal]
		if (!$this->_putCmd('SITE CHMOD ' . $mode . ' ' . $path, array(200, 250)))
		{
			if (!defined('PHP_WINDOWS_VERSION_MAJOR'))
			{
				Log::add(
					sprintf(
						'%1$s: Bad response.  Server response: %2$s [Expected: 250].  Path sent: %3$s.  Mode sent: %4$s',
						__METHOD__,
						$this->response,
						$path,
						$mode
					),
					Log::WARNING,
					'jerror'
				);
			}

			return false;
		}

		return true;
	}

	/**
	 * Method to delete a path [file/folder] on the FTP server
	 *
	 * @param   string  $path  Path to delete
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function delete($path)
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_delete($this->conn, $path) === false)
			{
				if (@ftp_rmdir($this->conn, $path) === false)
				{
					Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

					return false;
				}
			}

			return true;
		}

		// Send delete file command and if that doesn't work, try to remove a directory
		if (!$this->_putCmd('DELE ' . $path, 250))
		{
			if (!$this->_putCmd('RMD ' . $path, 250))
			{
				Log::add(
					sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 250].  Path sent: %3$s', __METHOD__, $this->response, $path),
					Log::WARNING, 'jerror'
				);

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to create a directory on the FTP server
	 *
	 * @param   string  $path  Directory to create
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function mkdir($path)
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_mkdir($this->conn, $path) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send change directory command and verify success
		if (!$this->_putCmd('MKD ' . $path, 257))
		{
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 257].  Path sent: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to restart data transfer at a given byte
	 *
	 * @param   integer  $point  Byte to restart transfer at
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function restart($point)
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			if (@ftp_site($this->conn, 'REST ' . $point) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		// Send restart command and verify success
		if (!$this->_putCmd('REST ' . $point, 350))
		{
			Log::add(
				sprintf(
					'%1$s: Bad response.  Server response: %2$s [Expected: 350].  Restart point sent: %3$s', __METHOD__, $this->response, $point
				),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to create an empty file on the FTP server
	 *
	 * @param   string  $path  Path local file to store on the FTP server
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function create($path)
	{
		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			$buffer = fopen('buffer://tmp', 'r');

			if (@ftp_fput($this->conn, $path, $buffer, FTP_ASCII) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');
				fclose($buffer);

				return false;
			}

			fclose($buffer);

			return true;
		}

		// Start passive mode
		if (!$this->_passive())
		{
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		if (!$this->_putCmd('STOR ' . $path, array(150, 125)))
		{
			@ fclose($this->dataconn);
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// To create a zero byte upload close the data port connection
		fclose($this->dataconn);

		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to read a file from the FTP server's contents into a buffer
	 *
	 * @param   string  $remote   Path to remote file to read on the FTP server
	 * @param   string  &$buffer  Buffer variable to read file contents into
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function read($remote, &$buffer)
	{
		// Determine file type
		$mode = $this->_findMode($remote);

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			$tmp = fopen('buffer://tmp', 'br+');

			if (@ftp_fget($this->conn, $tmp, $remote, $mode) === false)
			{
				fclose($tmp);
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			// Read tmp buffer contents
			rewind($tmp);
			$buffer = '';

			while (!feof($tmp))
			{
				$buffer .= fread($tmp, 8192);
			}

			fclose($tmp);

			return true;
		}

		$this->_mode($mode);

		// Start passive mode
		if (!$this->_passive())
		{
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		if (!$this->_putCmd('RETR ' . $remote, array(150, 125)))
		{
			@ fclose($this->dataconn);
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Read data from data port connection and add to the buffer
		$buffer = '';

		while (!feof($this->dataconn))
		{
			$buffer .= fread($this->dataconn, 4096);
		}

		// Close the data port connection
		fclose($this->dataconn);

		// Let's try to cleanup some line endings if it is ascii
		if ($mode == FTP_ASCII)
		{
			$os = 'UNIX';

			if (defined('PHP_WINDOWS_VERSION_MAJOR'))
			{
				$os = 'WIN';
			}

			$buffer = preg_replace("/" . CRLF . "/", $this->lineEndings[$os], $buffer);
		}

		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf(
					'%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Restart point sent: %3$s',
					__METHOD__, $this->response, $remote
				),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to get a file from the FTP server and save it to a local file
	 *
	 * @param   string  $local   Local path to save remote file to
	 * @param   string  $remote  Path to remote file to get on the FTP server
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function get($local, $remote)
	{
		// Determine file type
		$mode = $this->_findMode($remote);

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			if (@ftp_get($this->conn, $local, $remote, $mode) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		$this->_mode($mode);

		// Check to see if the local file can be opened for writing
		$fp = fopen($local, "wb");

		if (!$fp)
		{
			Log::add(sprintf('%1$s: Unable to open local file for writing.  Local path: %2$s', __METHOD__, $local), Log::WARNING, 'jerror');

			return false;
		}

		// Start passive mode
		if (!$this->_passive())
		{
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		if (!$this->_putCmd('RETR ' . $remote, array(150, 125)))
		{
			@ fclose($this->dataconn);
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Read data from data port connection and add to the buffer
		while (!feof($this->dataconn))
		{
			$buffer = fread($this->dataconn, 4096);
			fwrite($fp, $buffer, 4096);
		}

		// Close the data port connection and file pointer
		fclose($this->dataconn);
		fclose($fp);

		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to store a file to the FTP server
	 *
	 * @param   string  $local   Path to local file to store on the FTP server
	 * @param   string  $remote  FTP path to file to create
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function store($local, $remote = null)
	{
		// If remote file is not given, use the filename of the local file in the current
		// working directory.
		if ($remote == null)
		{
			$remote = basename($local);
		}

		// Determine file type
		$mode = $this->_findMode($remote);

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			if (@ftp_put($this->conn, $remote, $local, $mode) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			return true;
		}

		$this->_mode($mode);

		// Check to see if the local file exists and if so open it for reading
		if (@ file_exists($local))
		{
			$fp = fopen($local, "rb");

			if (!$fp)
			{
				Log::add(sprintf('%1$s: Unable to open local file for reading. Local path: %2$s', __METHOD__, $local), Log::WARNING, 'jerror');

				return false;
			}
		}
		else
		{
			Log::add(sprintf('%1$s: Unable to find local file. Local path: %2$s', __METHOD__, $local), Log::WARNING, 'jerror');

			return false;
		}

		// Start passive mode
		if (!$this->_passive())
		{
			@ fclose($fp);
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		// Send store command to the FTP server
		if (!$this->_putCmd('STOR ' . $remote, array(150, 125)))
		{
			@ fclose($fp);
			@ fclose($this->dataconn);
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Do actual file transfer, read local file and write to data port connection
		while (!feof($fp))
		{
			$line = fread($fp, 4096);

			do
			{
				if (($result = @ fwrite($this->dataconn, $line)) === false)
				{
					Log::add(__METHOD__ . ': Unable to write to data port socket', Log::WARNING, 'jerror');

					return false;
				}

				$line = substr($line, $result);
			}

			while ($line != "");
		}

		fclose($fp);
		fclose($this->dataconn);

		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to write a string to the FTP server
	 *
	 * @param   string  $remote  FTP path to file to write to
	 * @param   string  $buffer  Contents to write to the FTP server
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function write($remote, $buffer)
	{
		// Determine file type
		$mode = $this->_findMode($remote);

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			$tmp = fopen('buffer://tmp', 'br+');
			fwrite($tmp, $buffer);
			rewind($tmp);

			if (@ftp_fput($this->conn, $remote, $tmp, $mode) === false)
			{
				fclose($tmp);
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			fclose($tmp);

			return true;
		}

		// First we need to set the transfer mode
		$this->_mode($mode);

		// Start passive mode
		if (!$this->_passive())
		{
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		// Send store command to the FTP server
		if (!$this->_putCmd('STOR ' . $remote, array(150, 125)))
		{
			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);
			@ fclose($this->dataconn);

			return false;
		}

		// Write buffer to the data connection port
		do
		{
			if (($result = @ fwrite($this->dataconn, $buffer)) === false)
			{
				Log::add(__METHOD__ . ': Unable to write to data port socket.', Log::WARNING, 'jerror');

				return false;
			}

			$buffer = substr($buffer, $result);
		}

		while ($buffer != "");

		// Close the data connection port [Data transfer complete]
		fclose($this->dataconn);

		// Verify that the server recieved the transfer
		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $remote),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		return true;
	}

	/**
	 * Method to list the filenames of the contents of a directory on the FTP server
	 *
	 * Note: Some servers also return folder names. However, to be sure to list folders on all
	 * servers, you should use listDetails() instead if you also need to deal with folders
	 *
	 * @param   string  $path  Path local file to store on the FTP server
	 *
	 * @return  string  Directory listing
	 *
	 * @since   1.0
	 */
	public function listNames($path = null)
	{
		$data = null;

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			if (($list = @ftp_nlist($this->conn, $path)) === false)
			{
				// Workaround for empty directories on some servers
				if ($this->listDetails($path, 'files') === array())
				{
					return array();
				}

				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}

			$list = preg_replace('#^' . preg_quote($path, '#') . '[/\\\\]?#', '', $list);

			if ($keys = array_merge(array_keys($list, '.'), array_keys($list, '..')))
			{
				foreach ($keys as $key)
				{
					unset($list[$key]);
				}
			}

			return $list;
		}

		/*
		 * If a path exists, prepend a space
		 */
		if ($path != null)
		{
			$path = ' ' . $path;
		}

		// Start passive mode
		if (!$this->_passive())
		{
			Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

			return false;
		}

		if (!$this->_putCmd('NLST' . $path, array(150, 125)))
		{
			@ fclose($this->dataconn);

			// Workaround for empty directories on some servers
			if ($this->listDetails($path, 'files') === array())
			{
				return array();
			}

			Log::add(
				sprintf('%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Read in the file listing.
		while (!feof($this->dataconn))
		{
			$data .= fread($this->dataconn, 4096);
		}

		fclose($this->dataconn);

		// Everything go okay?
		if (!$this->_verifyResponse(226))
		{
			Log::add(
				sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $path),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		$data = preg_split("/[" . CRLF . "]+/", $data, -1, PREG_SPLIT_NO_EMPTY);
		$data = preg_replace('#^' . preg_quote(substr($path, 1), '#') . '[/\\\\]?#', '', $data);

		if ($keys = array_merge(array_keys($data, '.'), array_keys($data, '..')))
		{
			foreach ($keys as $key)
			{
				unset($data[$key]);
			}
		}

		return $data;
	}

	/**
	 * Method to list the contents of a directory on the FTP server
	 *
	 * @param   string  $path  Path to the local file to be stored on the FTP server
	 * @param   string  $type  Return type [raw|all|folders|files]
	 *
	 * @return  mixed  If $type is raw: string Directory listing, otherwise array of string with file-names
	 *
	 * @since   1.0
	 */
	public function listDetails($path = null, $type = 'all')
	{
		$dir_list = array();
		$data = null;
		$regs = null;

		// TODO: Deal with recurse -- nightmare
		// For now we will just set it to false
		$recurse = false;

		// If native FTP support is enabled let's use it...
		if (FTP_NATIVE)
		{
			// Turn passive mode on
			if (@ftp_pasv($this->conn, true) === false)
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			if (($contents = @ftp_rawlist($this->conn, $path)) === false)
			{
				Log::add(__METHOD__ . 'Bad response.', Log::WARNING, 'jerror');

				return false;
			}
		}
		else
		{
			// Non Native mode

			// Start passive mode
			if (!$this->_passive())
			{
				Log::add(__METHOD__ . ': Unable to use passive mode.', Log::WARNING, 'jerror');

				return false;
			}

			// If a path exists, prepend a space
			if ($path != null)
			{
				$path = ' ' . $path;
			}

			// Request the file listing
			if (!$this->_putCmd(($recurse == true) ? 'LIST -R' : 'LIST' . $path, array(150, 125)))
			{
				@ fclose($this->dataconn);
				Log::add(
					sprintf(
						'%1$s: Bad response.  Server response: %2$s [Expected: 150 or 125].  Path sent: %3$s',
						__METHOD__, $this->response, $path
					),
					Log::WARNING, 'jerror'
				);

				return false;
			}

			// Read in the file listing.
			while (!feof($this->dataconn))
			{
				$data .= fread($this->dataconn, 4096);
			}

			fclose($this->dataconn);

			// Everything go okay?
			if (!$this->_verifyResponse(226))
			{
				Log::add(
					sprintf('%1$s: Transfer failed.  Server response: %2$s [Expected: 226].  Path sent: %3$s', __METHOD__, $this->response, $path),
					Log::WARNING, 'jerror'
				);

				return false;
			}

			$contents = explode(CRLF, $data);
		}

		// If only raw output is requested we are done
		if ($type == 'raw')
		{
			return $data;
		}

		// If we received the listing of an empty directory, we are done as well
		if (empty($contents[0]))
		{
			return $dir_list;
		}

		// If the server returned the number of results in the first response, let's dump it
		if (strtolower(substr($contents[0], 0, 6)) == 'total ')
		{
			array_shift($contents);

			if (!isset($contents[0]) || empty($contents[0]))
			{
				return $dir_list;
			}
		}

		// Regular expressions for the directory listing parsing.
		$regexps = array(
			'UNIX' => '#([-dl][rwxstST-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*)'
				. ' ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{1,2}:[0-9]{2})|[0-9]{4}) (.+)#',
			'MAC' => '#([-dl][rwxstST-]+).* ?([0-9 ]*)?([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*)'
				. ' ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)#',
			'WIN' => '#([0-9]{2})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)#'
		);

		// Find out the format of the directory listing by matching one of the regexps
		$osType = null;

		foreach ($regexps as $k => $v)
		{
			if (@preg_match($v, $contents[0]))
			{
				$osType = $k;
				$regexp = $v;
				break;
			}
		}

		if (!$osType)
		{
			Log::add(__METHOD__ . ': Unrecognised directory listing format.', Log::WARNING, 'jerror');

			return false;
		}

		/*
		 * Here is where it is going to get dirty....
		 */
		if ($osType == 'UNIX' || $osType == 'MAC')
		{
			foreach ($contents as $file)
			{
				$tmp_array = null;

				if (@preg_match($regexp, $file, $regs))
				{
					$fType = (int) strpos("-dl", $regs[1]{0});

					// $tmp_array['line'] = $regs[0];
					$tmp_array['type'] = $fType;
					$tmp_array['rights'] = $regs[1];

					// $tmp_array['number'] = $regs[2];
					$tmp_array['user'] = $regs[3];
					$tmp_array['group'] = $regs[4];
					$tmp_array['size'] = $regs[5];
					$tmp_array['date'] = @date("m-d", strtotime($regs[6]));
					$tmp_array['time'] = $regs[7];
					$tmp_array['name'] = $regs[9];
				}

				// If we just want files, do not add a folder
				if ($type == 'files' && $tmp_array['type'] == 1)
				{
					continue;
				}

				// If we just want folders, do not add a file
				if ($type == 'folders' && $tmp_array['type'] == 0)
				{
					continue;
				}

				if (is_array($tmp_array) && $tmp_array['name'] != '.' && $tmp_array['name'] != '..')
				{
					$dir_list[] = $tmp_array;
				}
			}
		}
		else
		{
			foreach ($contents as $file)
			{
				$tmp_array = null;

				if (@preg_match($regexp, $file, $regs))
				{
					$fType = (int) ($regs[7] == '<DIR>');
					$timestamp = strtotime("$regs[3]-$regs[1]-$regs[2] $regs[4]:$regs[5]$regs[6]");

					// $tmp_array['line'] = $regs[0];
					$tmp_array['type'] = $fType;
					$tmp_array['rights'] = '';

					// $tmp_array['number'] = 0;
					$tmp_array['user'] = '';
					$tmp_array['group'] = '';
					$tmp_array['size'] = (int) $regs[7];
					$tmp_array['date'] = date('m-d', $timestamp);
					$tmp_array['time'] = date('H:i', $timestamp);
					$tmp_array['name'] = $regs[8];
				}

				// If we just want files, do not add a folder
				if ($type == 'files' && $tmp_array['type'] == 1)
				{
					continue;
				}

				// If we just want folders, do not add a file
				if ($type == 'folders' && $tmp_array['type'] == 0)
				{
					continue;
				}

				if (is_array($tmp_array) && $tmp_array['name'] != '.' && $tmp_array['name'] != '..')
				{
					$dir_list[] = $tmp_array;
				}
			}
		}

		return $dir_list;
	}

	/**
	 * Send command to the FTP server and validate an expected response code
	 *
	 * @param   string  $cmd               Command to send to the FTP server
	 * @param   mixed   $expectedResponse  Integer response code or array of integer response codes
	 *
	 * @return  boolean  True if command executed successfully
	 *
	 * @since   1.0
	 */
	protected function _putCmd($cmd, $expectedResponse)
	{
		// Make sure we have a connection to the server
		if (!is_resource($this->conn))
		{
			Log::add(__METHOD__ . ': Not connected to the control port.', Log::WARNING, 'jerror');

			return false;
		}

		// Send the command to the server
		if (!fwrite($this->conn, $cmd . "\r\n"))
		{
			Log::add(sprintf('%1$s: Unable to send command: %2$s', __METHOD__, $cmd), Log::WARNING, 'jerror');
		}

		return $this->_verifyResponse($expectedResponse);
	}

	/**
	 * Verify the response code from the server and log response if flag is set
	 *
	 * @param   mixed  $expected  Integer response code or array of integer response codes
	 *
	 * @return  boolean  True if response code from the server is expected
	 *
	 * @since   1.0
	 */
	protected function _verifyResponse($expected)
	{
		$parts = null;

		// Wait for a response from the server, but timeout after the set time limit
		$endTime = time() + $this->timeout;
		$this->response = '';

		do
		{
			$this->response .= fgets($this->conn, 4096);
		}

		while (!preg_match("/^([0-9]{3})(-(.*" . CRLF . ")+\\1)? [^" . CRLF . "]+" . CRLF . "$/", $this->response, $parts) && time() < $endTime);

		// Catch a timeout or bad response
		if (!isset($parts[1]))
		{
			Log::add(
				sprintf(
					'%1$s: Timeout or unrecognised response while waiting for a response from the server. Server response: %2$s',
					__METHOD__, $this->response
				),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Separate the code from the message
		$this->_responseCode = $parts[1];
		$this->_responseMsg = $parts[0];

		// Did the server respond with the code we wanted?
		if (is_array($expected))
		{
			if (in_array($this->_responseCode, $expected))
			{
				$retval = true;
			}
			else
			{
				$retval = false;
			}
		}
		else
		{
			if ($this->_responseCode == $expected)
			{
				$retval = true;
			}
			else
			{
				$retval = false;
			}
		}

		return $retval;
	}

	/**
	 * Set server to passive mode and open a data port connection
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	protected function _passive()
	{
		$match = array();
		$parts = array();
		$errno = null;
		$err = null;

		// Make sure we have a connection to the server
		if (!is_resource($this->conn))
		{
			Log::add(__METHOD__ . ': Not connected to the control port.', Log::WARNING, 'jerror');

			return false;
		}

		// Request a passive connection - this means, we'll talk to you, you don't talk to us.
		@ fwrite($this->conn, "PASV\r\n");

		// Wait for a response from the server, but timeout after the set time limit
		$endTime = time() + $this->timeout;
		$this->response = '';

		do
		{
			$this->response .= fgets($this->conn, 4096);
		}

		while (!preg_match("/^([0-9]{3})(-(.*" . CRLF . ")+\\1)? [^" . CRLF . "]+" . CRLF . "$/", $this->response, $parts) && time() < $endTime);

		// Catch a timeout or bad response
		if (!isset($parts[1]))
		{
			Log::add(
				sprintf(
					'%1$s: Timeout or unrecognised response while waiting for a response from the server. Server response: %2$s',
					__METHOD__, $this->response
				),
				Log::WARNING, 'jerror');

			return false;
		}

		// Separate the code from the message
		$this->_responseCode = $parts[1];
		$this->_responseMsg = $parts[0];

		// If it's not 227, we weren't given an IP and port, which means it failed.
		if ($this->_responseCode != '227')
		{
			Log::add(
				sprintf('%1$s: Unable to obtain IP and port for data transfer. Server response: %2$s', __METHOD__, $this->_responseMsg),
				Log::WARNING, 'jerror'
			);

			return false;
		}

		// Snatch the IP and port information, or die horribly trying...
		if (preg_match('~\((\d+),\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))\)~', $this->_responseMsg, $match) == 0)
		{
			Log::add(sprintf('%1$s: IP and port for data transfer not valid. Server response: %2$s', __METHOD__, $this->_responseMsg), Log::WARNING, 'jerror');

			return false;
		}

		// This is pretty simple - store it for later use ;).
		$this->pasv = array('ip' => $match[1] . '.' . $match[2] . '.' . $match[3] . '.' . $match[4], 'port' => $match[5] * 256 + $match[6]);

		// Connect, assuming we've got a connection.
		$this->dataconn = @fsockopen($this->pasv['ip'], $this->pasv['port'], $errno, $err, $this->timeout);

		if (!$this->dataconn)
		{
			Log::add(
				sprintf(
					'%1$s: Could not connect to host %2$s on port %3$s. Socket error number: %4$s and error message: %5$s',
					__METHOD__,
					$this->pasv['ip'],
					$this->pasv['port'],
					$errno,
					$err
				),
				Log::WARNING,
				'jerror'
			);

			return false;
		}

		// Set the timeout for this connection
		socket_set_timeout($this->conn, $this->timeout, 0);

		return true;
	}

	/**
	 * Method to find out the correct transfer mode for a specific file
	 *
	 * @param   string  $fileName  Name of the file
	 *
	 * @return  integer Transfer-mode for this filetype [FTP_ASCII|FTP_BINARY]
	 *
	 * @since   1.0
	 */
	protected function _findMode($fileName)
	{
		if ($this->type == FTP_AUTOASCII)
		{
			$dot = strrpos($fileName, '.') + 1;
			$ext = substr($fileName, $dot);

			if (in_array($ext, $this->autoAscii))
			{
				$mode = FTP_ASCII;
			}
			else
			{
				$mode = FTP_BINARY;
			}
		}
		elseif ($this->type == FTP_ASCII)
		{
			$mode = FTP_ASCII;
		}
		else
		{
			$mode = FTP_BINARY;
		}

		return $mode;
	}

	/**
	 * Set transfer mode
	 *
	 * @param   integer  $mode  Integer representation of data transfer mode [1:Binary|0:Ascii]
	 * Defined constants can also be used [FTP_BINARY|FTP_ASCII]
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	protected function _mode($mode)
	{
		if ($mode == FTP_BINARY)
		{
			if (!$this->_putCmd("TYPE I", 200))
			{
				Log::add(
					sprintf('%1$s: Bad response. Server response: %2$s [Expected: 200]. Mode sent: Binary', __METHOD__, $this->response),
					Log::WARNING, 'jerror'
				);

				return false;
			}
		}
		else
		{
			if (!$this->_putCmd("TYPE A", 200))
			{
				Log::add(
					sprintf('%1$s: Bad response. Server response: %2$s [Expected: 200]. Mode sent: ASCII', __METHOD__, $this->response),
					Log::WARNING, 'jerror'
				);

				return false;
			}
		}

		return true;
	}
}
