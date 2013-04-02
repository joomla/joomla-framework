<?php
/**
 * Part of the Joomla Framework Crypt Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Crypt;

/**
 * Joomla Framework Password Hashing Interface
 *
 * @since  1.0
 */
interface PasswordInterface
{
	const BLOWFISH = '$2y$';

	const JOOMLA = 'Joomla';

	const PBKDF = '$pbkdf$';

	const MD5 = '$1$';

	/**
	 * Creates a password hash
	 *
	 * @param   string  $password  The password to hash.
	 * @param   string  $type      The type of hash. This determines the prefix of the hashing function.
	 *
	 * @return  string  The hashed password.
	 *
	 * @since   1.0
	 */
	public function create($password, $type = null);

	/**
	 * Verifies a password hash
	 *
	 * @param   string  $password  The password to verify.
	 * @param   string  $hash      The password hash to check.
	 *
	 * @return  boolean  True if the password is valid, false otherwise.
	 *
	 * @since   1.0
	 */
	public function verify($password, $hash);

	/**
	 * Sets a default prefix
	 *
	 * @param   string  $type  The prefix to set as default
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setDefaultType($type);

	/**
	 * Gets the default type
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function getDefaultType();
}
