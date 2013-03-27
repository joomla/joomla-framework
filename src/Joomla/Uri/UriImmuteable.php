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
 * This is an immuteable version of the uri class.
 *
 * @since  1.0
 */
final class UriImmuteable extends AbstractUri
{
	/**
	 * @var bool
	 */
	private $constructed = false;

	/**
	 * Prevent setting undeclared properties.
	 *
	 * @return  null  This method always throws an exception.
	 *
	 * @throws  BadMethodCallException
	 *
	 * @since   1.0
	 */
	final public function __set($name, $value)
	{
		throw new \BadMethodCallException('This is an immuteable object');
	}

	/**
	 * This is a special constructor that prevents calling the __construct method again.
	 *
	 * @param   string  $uri  The optional URI string
	 *
	 * @since   1.0
	 */
	public function __construct($uri = null)
	{
		if ($this->constructed === true)
		{
			throw new \BadMethodCallException('This is an immuteable object');
		}

		$this->constructed = true;

		parent::__construct($uri);
	}
}
