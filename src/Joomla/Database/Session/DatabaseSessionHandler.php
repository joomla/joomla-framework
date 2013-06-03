<?php
/**
 * Part of the Joomla Framework Session Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Session;

use Joomla\Database\Driver;

/**
 * Database session storage handler for PHP
 *
 * @since  1.0
 */
class DatabaseSessionHandler implements \SessionHandlerInterface
{
	/**
	 * Database connection object
	 *
	 * @var    Joomla\Database\Driver
	 * @since  1.0
	 */
	private $db;

	/**
	 * Constructor
	 * 
	 * @param   \Joomla\Database\Driver $db
	 * 
	 * @since   1.0
	 */
	public function __construct(Driver $db)
	{
		$this->db = $db;
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
		try
		{
			// Get the session data from the database table.
			$query = $this->db->getQuery(true);
			$query->select($this->db->quoteName('data'))
			->from($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($id));

			$this->db->setQuery($query);

			return (string) $this->db->loadResult();
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Write session data to the SessionHandler backend.
	 *
	 * @param   string  $id    The session identifier.
	 * @param   string  $data  The session data.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	public function write($id, $data)
	{
		try
		{
			$query = $this->db->getQuery(true);
			$query->update($this->db->quoteName('#__session'))
			->set($this->db->quoteName('data') . ' = ' . $this->db->quote($data))
			->set($this->db->quoteName('time') . ' = ' . $this->db->quote((int) time()))
			->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($id));

			// Try to update the session data in the database table.
			$this->db->setQuery($query);

			if (!$this->db->execute())
			{
				return false;
			}

			// Since $db->execute did not throw an exception the query was successful.
			// Either the data changed, or the data was identical. In either case we are done.

			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
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
		try
		{
			$query = $this->db->getQuery(true);
			$query->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($id));

			// Remove a session from the database.
			$this->db->setQuery($query);

			return (boolean) $this->db->execute();
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Garbage collect stale sessions from the SessionHandler backend.
	 *
	 * @param   integer  $lifetime  The maximum age of a session.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	public function gc($lifetime = 1440)
	{
		// Determine the timestamp threshold with which to purge old sessions.
		$past = time() - $lifetime;

		try
		{
			$query = $this->db->getQuery(true);
			$query->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('time') . ' < ' . $this->db->quote((int) $past));

			// Remove expired sessions from the database.
			$this->db->setQuery($query);

			return (boolean) $db->execute();
		}
		catch (\Exception $e)
		{
			return false;
		}
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
}
