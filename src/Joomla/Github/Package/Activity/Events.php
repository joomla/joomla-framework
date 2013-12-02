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
 * @documentation http://developer.github.com/v3/activity/events/
 *
 * @since  1.0
 */
class Events extends AbstractPackage
{
	/**
	 * List public events.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getPublic()
	{
		// Build the request path.
		$path = '/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List repository events.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getRepository($owner, $repo)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List issue events for a repository.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getIssue($owner, $repo)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List public events for a network of repositories.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getNetwork($owner, $repo)
	{
		// Build the request path.
		$path = '/networks/' . $owner . '/' . $repo . '/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List public events for an organisation.
	 *
	 * @param   string  $org  Organisation.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getOrg($org)
	{
		// Build the request path.
		$path = '/orgs/' . $org . '/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List events that a user has received.
	 *
	 * These are events that you’ve received by watching repos and following users.
	 * If you are authenticated as the given user, you will see private events.
	 * Otherwise, you’ll only see public events.
	 *
	 * @param   string  $user  User name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getUser($user)
	{
		// Build the request path.
		$path = '/users/' . $user . '/received_events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List public events that a user has received.
	 *
	 * @param   string  $user  User name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getUserPublic($user)
	{
		// Build the request path.
		$path = '/users/' . $user . '/received_events/public';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List events performed by a user.
	 *
	 * If you are authenticated as the given user, you will see your private events.
	 * Otherwise, you’ll only see public events.
	 *
	 * @param   string  $user  User name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getByUser($user)
	{
		// Build the request path.
		$path = '/users/' . $user . '/events';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List public events performed by a user.
	 *
	 * @param   string  $user  User name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getByUserPublic($user)
	{
		// Build the request path.
		$path = '/users/' . $user . '/events/public';

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * List events for an organization.
	 *
	 * This is the user’s organization dashboard.
	 * You must be authenticated as the user to view this.
	 *
	 * @param   string  $user  User name.
	 * @param   string  $org   Organisation.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getUserOrg($user, $org)
	{
		// Build the request path.
		$path = '/users/' . $user . '/events/orgs/' . $org;

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}
}
