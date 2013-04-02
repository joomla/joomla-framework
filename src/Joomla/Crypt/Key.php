<?php
/**
 * Part of the Joomla Framework Crypt Package
 *
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Crypt;

/**
 * Encryption key object for the Joomla Framework.
 *
 * @property-read  string  $type  The key type.
 *
 * @since  1.0
 */
class Key
{
	/**
	 * @var    string  The private key.
	 * @since  1.0
	 */
	public $private;

	/**
	 * @var    string  The public key.
	 * @since  1.0
	 */
	public $public;

	/**
	 * @var    string  The key type.
	 * @since  1.0
	 */
	protected $type;

	/**
	 * Constructor.
	 *
	 * @param   string  $type     The key type.
	 * @param   string  $private  The private key.
	 * @param   string  $public   The public key.
	 *
	 * @since   1.0
	 */
	public function __construct($type, $private = null, $public = null)
	{
		// Set the key type.
		$this->type = (string) $type;

		// Set the optional public/private key strings.
		$this->private = isset($private) ? (string) $private : null;
		$this->public  = isset($public) ? (string) $public : null;
	}

	/**
	 * Magic method to return some protected property values.
	 *
	 * @param   string  $name  The name of the property to return.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		if ($name == 'type')
		{
			return $this->type;
		}
		else
		{
			trigger_error('Cannot access property ' . __CLASS__ . '::' . $name, E_USER_WARNING);
		}
	}
}
