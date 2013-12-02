<?php
/**
 * Part of the Joomla Framework Crypt Package
 *
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Crypt;

/**
 * Cipher class for Triple DES encryption, decryption and key generation.
 *
 * @since  1.0
 */
class Cipher_3DES extends Cipher_Mcrypt
{
	/**
	 * @var    integer  The mcrypt cipher constant.
	 * @see    http://www.php.net/manual/en/mcrypt.ciphers.php
	 * @since  1.0
	 */
	protected $type = MCRYPT_3DES;

	/**
	 * @var    integer  The mcrypt block cipher mode.
	 * @see    http://www.php.net/manual/en/mcrypt.constants.php
	 * @since  1.0
	 */
	protected $mode = MCRYPT_MODE_CBC;

	/**
	 * @var    string  The JCrypt key type for validation.
	 * @since  1.0
	 */
	protected $keyType = '3des';
}
