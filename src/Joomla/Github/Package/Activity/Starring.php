<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Activity;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Activity Events class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/activity/starring/
 *
 * @since  1.0
 */
class Starring extends AbstractPackage
{
	/**
	 * List Stargazers.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function getList($owner, $repo)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/stargazers';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List repositories being starred.
	 *
	 * List repositories being starred by a user.
	 *
	 * @param   string  $user  User name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getRepositories($user = '')
	{
		// Build the request path.
		$path = ($user)
			? '/users' . $user . '/starred'
			: '/user/starred';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Check if you are starring a repository.
	 *
	 * Requires for the user to be authenticated.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function check($owner, $repo)
	{
		// Build the request path.
		$path = '/user/starred/' . $owner . '/' . $repo;

		$response = $this->client->get($this->fetchUrl($path));

		switch ($response->code)
		{
			case '204' :
				// This repository is watched by you.
				return true;
				break;

			case '404' :
				// This repository is not watched by you.
				return false;
				break;
		}

		throw new \UnexpectedValueException('Unexpected response code: ' . $response->code);
	}

	/**
	 * Star a repository.
	 *
	 * Requires for the user to be authenticated.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function star($owner, $repo)
	{
		// Build the request path.
		$path = '/user/starred/' . $owner . '/' . $repo;

		return $this->processResponse(
			$this->client->put($this->fetchUrl($path), ''),
			204
		);
	}

	/**
	 * Unstar a repository.
	 *
	 * Requires for the user to be authenticated.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function unstar($owner, $repo)
	{
		// Build the request path.
		$path = '/user/starred/' . $owner . '/' . $repo;

		return $this->processResponse(
			$this->client->delete($this->fetchUrl($path)),
			204
		);
	}
}
