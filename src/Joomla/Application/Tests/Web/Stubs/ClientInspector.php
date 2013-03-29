<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests\Web\Stubs;

use Joomla\Application\Web\Client;

/**
 * ClientInspector
 *
 * @since  1.0
 */
class ClientInspector extends Client
{
	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $userAgent  The user-agent string to parse.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function detectBrowser($userAgent)
	{
		parent::detectBrowser($userAgent);
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $userAgent  The user-agent string to parse.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function detectEngine($userAgent)
	{
		parent::detectEngine($userAgent);
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $userAgent  The user-agent string to parse.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function detectPlatform($userAgent)
	{
		parent::detectPlatform($userAgent);
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $acceptEncoding  The accept encoding string to parse.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function detectEncoding($acceptEncoding)
	{
		parent::detectEncoding($acceptEncoding);
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $acceptLanguage  The accept language string to parse.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function detectLanguage($acceptLanguage)
	{
		parent::detectLanguage($acceptLanguage);
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string  $userAgent  The user-agent string to parse.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function detectRobot($userAgent)
	{
		parent::detectRobot($userAgent);
	}
}
