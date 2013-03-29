<?php
/**
 * Part of the Joomla Framework Session Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Session;

/**
 * XCache session storage handler
 *
 * @since  1.0
 */
class XcacheSessionHandler implements \SessionHandlerInterface
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Optional parameters.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct()
	{
		if (!self::isSupported())
		{
			throw new \RuntimeException('XCache Extension is not available', 404);
		}

		parent::__construct($options);
	}

	/**
	 * Read the data for a particular session identifier from the SessionHandler backend.
	 *
	 * @param   string  $id  The session identifier.
	 *
	 * @return  string  The session data.
	 *
	 * @since   1.0
	 */
	public function read($id)
	{
		$sess_id = 'sess_' . $id;

		// Check if id exists
		if (!xcache_isset($sess_id))
		{
			return;
		}

		return (string) xcache_get($sess_id);
	}

	/**
	 * Write session data to the SessionHandler backend.
	 *
	 * @param   string  $id            The session identifier.
	 * @param   string  $session_data  The session data.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	public function write($id, $session_data)
	{
		$sess_id = 'sess_' . $id;

		return xcache_set($sess_id, $session_data, ini_get("session.gc_maxlifetime"));
	}

	/**
	 * Destroy the data for a particular session identifier in the SessionHandler backend.
	 *
	 * @param   string  $id  The session identifier.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	public function destroy($id)
	{
		$sess_id = 'sess_' . $id;

		if (!xcache_isset($sess_id))
		{
			return true;
		}

		return xcache_unset($sess_id);
	}

	/**
	 * Cleans up expired sessions.
	 * Called by session_start(), based on session.gc_divisor, session.gc_probability and session.gc_lifetime settings.
	 *
	 * @param   string  $maxlifetime  Sessions that have not updated for the last maxlifetime seconds will be removed.
	 *
	 * @return  bool
	 */
	public function gc($maxlifetime)
	{
		return true;
	}

	/**
	 * Re-initialize existing session, or creates a new one.
	 * Called when a session starts or when session_start() is invoked.
	 *
	 * @param   string  $save_path  The path where to store/retrieve the session.
	 * @param   string  $name       The session name.
	 *
	 * @return  bool
	 */
	public function open($save_path, $name)
	{
		return true;
	}

	/*
	 * Closes the current session.
	 * This function is automatically executed when closing the session, or explicitly via session_write_close().
	 *
	 * @return  bool
	 */
	public function close()
	{
		return true;
	}

	/**
	 * Test to see if the SessionHandler is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	static public function isSupported()
	{
		return (extension_loaded('xcache'));
	}
}
