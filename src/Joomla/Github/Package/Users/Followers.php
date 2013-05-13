<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Users;

use Joomla\Github\Package;

/**
 * GitHub API References class for the Joomla Platform.
 *
 * @documentation http://developer.github.com/v3/repos/users/followers
 *
 * @since  1.0
 */
class Followers extends Package
{
	/**
	 * List followers of a user.
	 *
	 * @param   string  $user  The name of the user. If not set the current authenticated user will be used.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function getList($user = '')
	{
		// Build the request path.
		$path = ($user)
			? '/users/' . $user . '/followers'
			: '/user/followers';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List users followed by another user.
	 *
	 * @param   string  $user  The name of the user. If not set the current authenticated user will be used.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function getListFollowedBy($user = '')
	{
		// Build the request path.
		$path = ($user)
			? '/users/' . $user . '/following'
			: '/user/following';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Check if you are following a user.
	 *
	 * @param   string  $user  The name of the user.
	 *
	 * @throws \UnexpectedValueException
	 * @since  1.0
	 *
	 * @return boolean
	 */
	public function check($user)
	{
		// Build the request path.
		$path = '/user/following/' . $user;

		$response = $this->client->get($this->fetchUrl($path));

		switch ($response->code)
		{
			case '204' :
				// You are following this user
				return true;
				break;

			case '404' :
				// You are not following this user
				return false;
				break;

			default :
				throw new \UnexpectedValueException('Unexpected response code: ' . $response->code);
				break;
		}
	}

	/**
	 * Follow a user.
	 *
	 * Following a user requires the user to be logged in and authenticated with
	 * basic auth or OAuth with the user:follow scope.
	 *
	 * @param   string  $user  The name of the user.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function follow($user)
	{
		// Build the request path.
		$path = '/user/following/' . $user;

		return $this->processResponse(
			$this->client->put($this->fetchUrl($path), ''),
			204
		);
	}

	/**
	 * Unfollow a user.
	 *
	 * Unfollowing a user requires the user to be logged in and authenticated with
	 * basic auth or OAuth with the user:follow scope.
	 *
	 * @param   string  $user  The name of the user.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function unfollow($user)
	{
		// Build the request path.
		$path = '/user/following/' . $user;

		return $this->processResponse(
			$this->client->delete($this->fetchUrl($path)),
			204
		);
	}
}
