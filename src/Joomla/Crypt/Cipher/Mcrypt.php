<?php
/**
 * Part of the Joomla Framework Crypt Package
 *
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Crypt;

/**
 * Cipher class for mcrypt algorithm encryption, decryption and key generation.
 *
 * @since  1.0
 */
abstract class Cipher_Mcrypt implements CipherInterface
{
	/**
	 * @var    integer  The mcrypt cipher constant.
	 * @see    http://www.php.net/manual/en/mcrypt.ciphers.php
	 * @since  1.0
	 */
	protected $type;

	/**
	 * @var    integer  The mcrypt block cipher mode.
	 * @see    http://www.php.net/manual/en/mcrypt.constants.php
	 * @since  1.0
	 */
	protected $mode;

	/**
	 * @var    string  The JCrypt key type for validation.
	 * @since  1.0
	 */
	protected $keyType;

	/**
	 * Constructor.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct()
	{
		if (!is_callable('mcrypt_encrypt'))
		{
			throw new \RuntimeException('The mcrypt extension is not available.');
		}
	}

	/**
	 * Method to decrypt a data string.
	 *
	 * @param   string  $data  The encrypted string to decrypt.
	 * @param   Key     $key   The key object to use for decryption.
	 *
	 * @return  string  The decrypted data string.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function decrypt($data, Key $key)
	{
		// Validate key.
		if ($key->type != $this->keyType)
		{
			throw new \InvalidArgumentException('Invalid key of type: ' . $key->type . '.  Expected ' . $this->keyType . '.');
		}

		// Decrypt the data.
		$decrypted = trim(mcrypt_decrypt($this->type, $key->private, $data, $this->mode, $key->public));

		return $decrypted;
	}

	/**
	 * Method to encrypt a data string.
	 *
	 * @param   string  $data  The data string to encrypt.
	 * @param   Key     $key   The key object to use for encryption.
	 *
	 * @return  string  The encrypted data string.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function encrypt($data, Key $key)
	{
		// Validate key.
		if ($key->type != $this->keyType)
		{
			throw new \InvalidArgumentException('Invalid key of type: ' . $key->type . '.  Expected ' . $this->keyType . '.');
		}

		// Encrypt the data.
		$encrypted = mcrypt_encrypt($this->type, $key->private, $data, $this->mode, $key->public);

		return $encrypted;
	}

	/**
	 * Method to generate a new encryption key object.
	 *
	 * @param   array  $options  Key generation options.
	 *
	 * @return  Key
	 *
	 * @since   1.0
	 */
	public function generateKey(array $options = array())
	{
		// Create the new encryption key object.
		$key = new Key($this->keyType);

		// Generate an initialisation vector based on the algorithm.
		$key->public = mcrypt_create_iv(mcrypt_get_iv_size($this->type, $this->mode));

		// Get the salt and password setup.
		$salt = (isset($options['salt'])) ? $options['salt'] : substr(pack("h*", md5(mt_rand())), 0, 16);
		$password = (isset($options['password'])) ? $options['password'] : 'J00ml4R0ck$!';

		// Generate the derived key.
		$key->private = $this->pbkdf2($password, $salt, mcrypt_get_key_size($this->type, $this->mode));

		return $key;
	}

	/**
	 * PBKDF2 Implementation for deriving keys.
	 *
	 * @param   string   $p   Password
	 * @param   string   $s   Salt
	 * @param   integer  $kl  Key length
	 * @param   integer  $c   Iteration count
	 * @param   string   $a   Hash algorithm
	 *
	 * @return  string  The derived key.
	 *
	 * @see     http://en.wikipedia.org/wiki/PBKDF2
	 * @see     http://www.ietf.org/rfc/rfc2898.txt
	 * @since   1.0
	 */
	public function pbkdf2($p, $s, $kl, $c = 10000, $a = 'sha256')
	{
		// Hash length.
		$hl = strlen(hash($a, null, true));

		// Key blocks to compute.
		$kb = ceil($kl / $hl);

		// Derived key.
		$dk = '';

		// Create the key.
		for ($block = 1; $block <= $kb; $block++)
		{
			// Initial hash for this block.
			$ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

			// Perform block iterations.
			for ($i = 1; $i < $c; $i++)
			{
				$ib ^= ($b = hash_hmac($a, $b, $p, true));
			}

			// Append the iterated block.
			$dk .= $ib;
		}

		// Return derived key of correct length.
		return substr($dk, 0, $kl);
	}
}
