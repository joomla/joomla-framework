<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Repositories;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API References class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/repos/statuses
 *
 * @since  1.0
 */
class Statuses extends AbstractPackage
{
	/**
	 * Method to create a status.
	 *
	 * @param   string  $user         The name of the owner of the GitHub repository.
	 * @param   string  $repo         The name of the GitHub repository.
	 * @param   string  $sha          The SHA1 value for which to set the status.
	 * @param   string  $state        The state (pending, success, error or failure).
	 * @param   string  $targetUrl    Optional target URL.
	 * @param   string  $description  Optional description for the status.
	 *
	 * @throws \InvalidArgumentException
	 * @throws \DomainException
	 *
	 * @since   1.0
	 *
	 * @return  object
	 */
	public function create($user, $repo, $sha, $state, $targetUrl = null, $description = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/statuses/' . $sha;

		if (!in_array($state, array('pending', 'success', 'error', 'failure')))
		{
			throw new \InvalidArgumentException('State must be one of pending, success, error or failure.');
		}

		// Build the request data.
		$data = array(
			'state' => $state
		);

		if (!is_null($targetUrl))
		{
			$data['target_url'] = $targetUrl;
		}

		if (!is_null($description))
		{
			$data['description'] = $description;
		}

		// Send the request.
		return $this->processResponse(
			$this->client->post($this->fetchUrl($path), json_encode($data)),
			201
		);
	}

	/**
	 * Method to list statuses for an SHA.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $sha   SHA1 for which to get the statuses.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($user, $repo, $sha)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/statuses/' . $sha;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)));
	}
}
