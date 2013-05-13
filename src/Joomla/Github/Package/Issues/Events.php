<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Issues;

use Joomla\Github\Package;

/**
 * GitHub API Issues Events class for the Joomla Platform.
 *
 * Records various events that occur around an Issue or Pull Request.
 * This is useful both for display on issue/pull request information pages and also
 * to determine who should be notified of comments.
 *
 * @documentation http://developer.github.com/v3/issues/events/
 *
 * @since  1.0
 */
class Events extends Package
{
	/**
	 * List events for an issue.
	 *
	 * @param   string   $owner         The name of the owner of the GitHub repository.
	 * @param   string   $repo          The name of the GitHub repository.
	 * @param   integer  $issue_number  The issue number.
	 * @param   integer  $page          The page number from which to get items.
	 * @param   integer  $limit         The number of items on a page.
	 *
	 * @return object
	 */
	public function getList($owner, $repo, $issue_number, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . (int) $issue_number . '/events';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path, $page, $limit))
		);
	}

	/**
	 * List events for a repository.
	 *
	 * @param   string   $owner    The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 * @param   integer  $page     The page number from which to get items.
	 * @param   integer  $limit    The number of items on a page.
	 *
	 * @return object
	 */
	public function getListRepository($owner, $repo, $issueId, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . (int) $issueId . '/comments';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path, $page, $limit))
		);
	}

	/**
	 * Get a single event.
	 *
	 * @param   string   $owner  The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   integer  $id     The event number.
	 *
	 * @return object
	 */
	public function get($owner, $repo, $id)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/events/' . (int) $id;

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}
}
