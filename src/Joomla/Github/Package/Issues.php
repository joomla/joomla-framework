<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package;

use Joomla\Github\AbstractPackage;
use Joomla\Date\Date;
use Joomla\Uri\Uri;

/**
 * GitHub API Issues class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/issues
 *
 * @since  1.0
 *
 * @property-read  Issues\Assignees   $assignees   GitHub API object for assignees.
 * @property-read  Issues\Comments    $comments    GitHub API object for comments.
 * @property-read  Issues\Events      $events      GitHub API object for events.
 * @property-read  Issues\Labels      $labels      GitHub API object for labels.
 * @property-read  Issues\Milestones  $milestones  GitHub API object for milestones.
 */
class Issues extends AbstractPackage
{
	/**
	 * Method to create an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   string   $title      The title of the new issue.
	 * @param   string   $body       The body text for the new issue.
	 * @param   string   $assignee   The login for the GitHub user that this issue should be assigned to.
	 * @param   integer  $milestone  The milestone to associate this issue with.
	 * @param   array    $labels     The labels to associate with this issue.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function create($user, $repo, $title, $body = null, $assignee = null, $milestone = null, array $labels = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues';

		// Ensure that we have a non-associative array.
		if (isset($labels))
		{
			$labels = array_values($labels);
		}

		// Build the request data.
		$data = json_encode(
			array(
				'title'     => $title,
				'assignee'  => $assignee,
				'milestone' => $milestone,
				'labels'    => $labels,
				'body'      => $body
			)
		);

		// Send the request.
		$response = $this->client->post($this->fetchUrl($path), $data);

		// Validate the response code.
		if ($response->code != 201)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}

		return json_decode($response->body);
	}

	/**
	 * Method to update an issue.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $issueId    The issue number.
	 * @param   string   $state      The optional new state for the issue. [open, closed]
	 * @param   string   $title      The title of the new issue.
	 * @param   string   $body       The body text for the new issue.
	 * @param   string   $assignee   The login for the GitHub user that this issue should be assigned to.
	 * @param   integer  $milestone  The milestone to associate this issue with.
	 * @param   array    $labels     The labels to associate with this issue.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function edit($user, $repo, $issueId, $state = null, $title = null, $body = null, $assignee = null, $milestone = null, array $labels = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId;

		// Craete the data object.
		$data = new \stdClass;

		// If a title is set add it to the data object.
		if (isset($title))
		{
			$data->title = $title;
		}

		// If a body is set add it to the data object.
		if (isset($body))
		{
			$data->body = $body;
		}

		// If a state is set add it to the data object.
		if (isset($state))
		{
			$data->state = $state;
		}

		// If an assignee is set add it to the data object.
		if (isset($assignee))
		{
			$data->assignee = $assignee;
		}

		// If a milestone is set add it to the data object.
		if (isset($milestone))
		{
			$data->milestone = $milestone;
		}

		// If labels are set add them to the data object.
		if (isset($labels))
		{
			// Ensure that we have a non-associative array.
			if (isset($labels))
			{
				$labels = array_values($labels);
			}

			$data->labels = $labels;
		}

		// Encode the request data.
		$data = json_encode($data);

		// Send the request.
		$response = $this->client->patch($this->fetchUrl($path), $data);

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}

		return json_decode($response->body);
	}

	/**
	 * Method to get a single issue.
	 *
	 * @param   string   $user     The name of the owner of the GitHub repository.
	 * @param   string   $repo     The name of the GitHub repository.
	 * @param   integer  $issueId  The issue number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function get($user, $repo, $issueId)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues/' . (int) $issueId;

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path));

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}

		return json_decode($response->body);
	}

	/**
	 * Method to list an authenticated user's issues.
	 *
	 * @param   string   $filter     The filter type: assigned, created, mentioned, subscribed.
	 * @param   string   $state      The optional state to filter requests by. [open, closed]
	 * @param   string   $labels     The list of comma separated Label names. Example: bug,ui,@high.
	 * @param   string   $sort       The sort order: created, updated, comments, default: created.
	 * @param   string   $direction  The list direction: asc or desc, default: desc.
	 * @param   Date     $since      The date/time since when issues should be returned.
	 * @param   integer  $page       The page number from which to get items.
	 * @param   integer  $limit      The number of items on a page.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getList($filter = null, $state = null, $labels = null, $sort = null,
		$direction = null, Date $since = null, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/issues';

		// TODO Implement the filtering options.

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path, $page, $limit));

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}

		return json_decode($response->body);
	}

	/**
	 * Method to list issues.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   string   $milestone  The milestone number, 'none', or *.
	 * @param   string   $state      The optional state to filter requests by. [open, closed]
	 * @param   string   $assignee   The assignee name, 'none', or *.
	 * @param   string   $mentioned  The GitHub user name.
	 * @param   string   $labels     The list of comma separated Label names. Example: bug,ui,@high.
	 * @param   string   $sort       The sort order: created, updated, comments, default: created.
	 * @param   string   $direction  The list direction: asc or desc, default: desc.
	 * @param   Date     $since      The date/time since when issues should be returned.
	 * @param   integer  $page       The page number from which to get items.
	 * @param   integer  $limit      The number of items on a page.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getListByRepository($user, $repo, $milestone = null, $state = null, $assignee = null, $mentioned = null, $labels = null,
		$sort = null, $direction = null, Date $since = null, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/issues';

		$uri = new Uri($this->fetchUrl($path, $page, $limit));

		if ($milestone)
		{
			$uri->setVar('milestone', $milestone);
		}

		if ($state)
		{
			$uri->setVar('state', $state);
		}

		if ($assignee)
		{
			$uri->setVar('assignee', $assignee);
		}

		if ($mentioned)
		{
			$uri->setVar('mentioned', $mentioned);
		}

		if ($labels)
		{
			$uri->setVar('labels', $labels);
		}

		if ($sort)
		{
			$uri->setVar('sort', $sort);
		}

		if ($direction)
		{
			$uri->setVar('direction', $direction);
		}

		if ($since)
		{
			$uri->setVar('since', $since->toISO8601());
		}

		// Send the request.
		$response = $this->client->get((string) $uri);

		// Validate the response code.
		if ($response->code != 200)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}

		return json_decode($response->body);
	}
}
