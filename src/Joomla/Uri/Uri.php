<?php
/**
 * Part of the Joomla Framework Uri Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Uri;

/**
 * Uri Class
 *
 * This class parses a URI and provides a common interface for the Joomla Framework
 * to access and manipulate a URI.
 *
 * @since  1.0
 */
class Uri extends AbstractUri
{
	/**
	 * Adds a query variable and value, replacing the value if it
	 * already exists and returning the old value.
	 *
	 * @param   string  $name   Name of the query variable to set.
	 * @param   string  $value  Value of the query variable.
	 *
	 * @return  string  Previous value for the query variable.
	 *
	 * @since   1.0
	 */
	public function setVar($name, $value)
	{
		$tmp = isset($this->vars[$name]) ? $this->vars[$name] : null;

		$this->vars[$name] = $value;

		// Empty the query
		$this->query = null;

		return $tmp;
	}

	/**
	 * Removes an item from the query string variables if it exists.
	 *
	 * @param   string  $name  Name of variable to remove.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function delVar($name)
	{
		if (array_key_exists($name, $this->vars))
		{
			unset($this->vars[$name]);

			// Empty the query
			$this->query = null;
		}
	}

	/**
	 * Sets the query to a supplied string in format:
	 * foo=bar&x=y
	 *
	 * @param   mixed  $query  The query string or array.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setQuery($query)
	{
		if (is_array($query))
		{
			$this->vars = $query;
		}
		else
		{
			if (strpos($query, '&amp;') !== false)
			{
				$query = str_replace('&amp;', '&', $query);
			}

			parse_str($query, $this->vars);
		}

		// Empty the query
		$this->query = null;
	}

	/**
	 * Set URI scheme (protocol)
	 * ie. http, https, ftp, etc...
	 *
	 * @param   string  $scheme  The URI scheme.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setScheme($scheme)
	{
		$this->scheme = $scheme;
	}

	/**
	 * Set URI username.
	 *
	 * @param   string  $user  The URI username.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * Set URI password.
	 *
	 * @param   string  $pass  The URI password.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setPass($pass)
	{
		$this->pass = $pass;
	}

	/**
	 * Set URI host.
	 *
	 * @param   string  $host  The URI host.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setHost($host)
	{
		$this->host = $host;
	}

	/**
	 * Set URI port.
	 *
	 * @param   integer  $port  The URI port number.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setPort($port)
	{
		$this->port = $port;
	}

	/**
	 * Set the URI path string.
	 *
	 * @param   string  $path  The URI path string.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setPath($path)
	{
		$this->path = $this->cleanPath($path);
	}

	/**
	 * Set the URI anchor string
	 * everything after the "#".
	 *
	 * @param   string  $anchor  The URI anchor string.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setFragment($anchor)
	{
		$this->fragment = $anchor;
	}
}
