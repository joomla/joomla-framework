<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

/**
 * GitHub API Forks class for the Joomla Framework.
 *
 * @since  1.0
 */
class Forks extends Object
{
	/**
	 * Method to fork a repository.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $org   The organization to fork the repo into. By default it is forked to the current user.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function create($user, $repo, $org = '')
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/forks';

		if (strlen($org) > 0)
		{
			$data = json_encode(
				array('org' => $org)
			);
		}
		else
		{
			$data = json_encode(array());
		}

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 202);
	}

	/**
	 * Method to list forks for a repository.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($user, $repo, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/forks';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}
}
