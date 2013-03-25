<?php
/**
 * Part of the Joomla Framework Language Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Language;

use RuntimeException;

/**
 * Stemmer base class.
 *
 * @since  1.0
 */
abstract class Stemmer
{
	/**
	 * An internal cache of stemmed tokens.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $cache = array();

	/**
	 * JLanguageStemmer instances.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * Method to get a stemmer, creating it if necessary.
	 *
	 * @param   string  $adapter  The type of stemmer to load.
	 *
	 * @return  Stemmer  A JLanguageStemmer instance.
	 *
	 * @since   1.0
	 * @throws  RuntimeException on invalid stemmer.
	 */
	public static function getInstance($adapter)
	{
		// Only create one stemmer for each adapter.
		if (isset(self::$instances[$adapter]))
		{
			return self::$instances[$adapter];
		}

		// Setup the adapter for the stemmer.
		$class = '\\Joomla\\Language\\Stemmer\\' . ucfirst(trim($adapter));

		// Check if a stemmer exists for the adapter.
		if (!class_exists($class))
		{
			// Throw invalid adapter exception.
			throw new RuntimeException(sprintf('Invalid stemmer type %s', $adapter));
		}

		self::$instances[$adapter] = new $class;

		return self::$instances[$adapter];
	}

	/**
	 * Method to stem a token and return the root.
	 *
	 * @param   string  $token  The token to stem.
	 * @param   string  $lang   The language of the token.
	 *
	 * @return  string  The root token.
	 *
	 * @since   1.0
	 */
	abstract public function stem($token, $lang);
}
