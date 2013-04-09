<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package;

use Joomla\Github\Package;

/**
 * GitHub API Zen class for the Joomla Framework.
 *
 * @since  ¿
 *
 * @documentation  http://developer.github.com/guides/getting-started/
 */
class Zen extends Package
{
	/**
	 * Get a random response about one of our design philosophies.
	 *
	 * @throws \RuntimeException
	 *
	 * @return string
	 */
	public function get()
	{
		$response = $this->client->get($this->fetchUrl('/zen'));

		if (200 != $response->code)
		{
			throw new \RuntimeException('Can\'t get a Zen');
		}

		return $response->body;
	}
}
