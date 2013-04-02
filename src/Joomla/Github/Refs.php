<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

/**
 * GitHub API References class for the Joomla Framework.
 *
 * @since  1.0
 */
class Refs extends Object
{
	/**
	 * Method to create an issue.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $ref   The name of the fully qualified reference.
	 * @param   string  $sha   The SHA1 value to set this reference to.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function create($user, $repo, $ref, $sha)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/git/refs';

		// Build the request data.
		$data = json_encode(
			array(
				'ref' => $ref,
				'sha' => $sha
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to update a reference.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   string   $ref    The reference to update.
	 * @param   string   $sha    The SHA1 value to set the reference to.
	 * @param   boolean  $force  Whether the update should be forced. Default to false.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function edit($user, $repo, $ref, $sha, $force = false)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/git/refs/' . $ref;

		// Craete the data object.
		$data = new \stdClass;

		// If instructed, force the update.
		if ($force)
		{
			$data->force = true;
		}

		$data->sha = $sha;

		// Encode the request data.
		$data = json_encode($data);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data), 200);
	}

	/**
	 * Method to get a reference.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $ref   The reference to get.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function get($user, $repo, $ref)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/git/refs/' . $ref;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to list references for a repository.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   string   $namespace  Optional sub-namespace to limit the returned references.
	 * @param   integer  $page       Page to request
	 * @param   integer  $limit      Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($user, $repo, $namespace = '', $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/git/refs' . $namespace;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}
}
