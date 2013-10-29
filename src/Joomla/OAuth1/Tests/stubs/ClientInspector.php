<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OAuth1\Tests;

use Joomla\OAuth1\Client;

/**
 * Inspector for the Client class.
 *
 * @since  1.0
 */
class ClientInspector extends Client
{
	/**
	 * Mimic verifing credentials.
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function verifyCredentials()
	{
		if (!strcmp($this->token['key'], 'valid'))
		{
			return true;
		}

		return false;
	}

	/**
	 * Method to validate a response.
	 *
	 * @param   string    $url       The request URL.
	 * @param   Response  $response  The response to validate.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function validateResponse($url, $response)
	{
		if ($response->code < 200 || $response->code > 399)
		{
				throw new \DomainException($response->body);
		}
	}
}
