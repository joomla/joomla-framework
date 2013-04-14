<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

/**
 * GitHub API Meta class for the Joomla Framework.
 *
 * @since  1.0
 */
class Meta extends GithubObject
{
	/**
	 * Method to get the authorized IP addresses for services
	 *
	 * @return  array  Authorized IP addresses in CIDR format
	 *
	 * @since   1.0
	 */
	public function getMeta()
	{
		// Build the request path.
		$path = '/meta';

		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}
}
