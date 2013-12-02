<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Issues;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Milestones class for the Joomla Framework.
 *
 * @documentation  http://developer.github.com/v3/issues/labels/
 *
 * @since  1.0
 */
class Labels extends AbstractPackage
{
	/**
	 * Method to get the list of labels on a repo.
	 *
	 * @param   string  $owner  The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($owner, $repo)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/labels';

		// Send the request.
		return $this->processResponse(
			$response = $this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Method to get a specific label on a repo.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $name  The label name to get.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function get($user, $repo, $name)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels/' . $name;

		// Send the request.
		return $this->processResponse(
			$response = $this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Method to create a label on a repo.
	 *
	 * @param   string  $owner  The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $name   The label name.
	 * @param   string  $color  The label color.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function create($owner, $repo, $name, $color)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/labels';

		// Build the request data.
		$data = json_encode(
			array(
				'name'  => $name,
				'color' => $color
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
	 * Method to delete a label on a repo.
	 *
	 * @param   string  $owner  The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $name   The label name.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function delete($owner, $repo, $name)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/labels/' . $name;

		// Send the request.
		return $this->processResponse(
			$this->client->delete($this->fetchUrl($path)),
			204
		);
	}

	/**
	 * Method to update a label on a repo.
	 *
	 * @param   string  $user   The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $label  The label name.
	 * @param   string  $name   The new label name.
	 * @param   string  $color  The new label color.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function update($user, $repo, $label, $name, $color)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/labels/' . $label;

		// Build the request data.
		$data = json_encode(
			array(
				'name'  => $name,
				'color' => $color
			)
		);

		// Send the request.
		return $this->processResponse(
			$this->client->patch($this->fetchUrl($path), $data)
		);
	}

	/**
	 * List labels on an issue.
	 *
	 * @param   string   $owner   The name of the owner of the GitHub repository.
	 * @param   string   $repo    The name of the GitHub repository.
	 * @param   integer  $number  The issue number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getListByIssue($owner, $repo, $number)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . $number . '/labels';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Add labels to an issue.
	 *
	 * @param   string  $owner   The name of the owner of the GitHub repository.
	 * @param   string  $repo    The name of the GitHub repository.
	 * @param   string  $number  The issue number.
	 * @param   array   $labels  An array of labels to add.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function add($owner, $repo, $number, array $labels)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . $number . '/labels';

		// Send the request.
		return $this->processResponse(
			$this->client->post($this->fetchUrl($path), json_encode($labels))
		);
	}

	/**
	 * Remove a label from an issue.
	 *
	 * @param   string  $owner   The name of the owner of the GitHub repository.
	 * @param   string  $repo    The name of the GitHub repository.
	 * @param   string  $number  The issue number.
	 * @param   string  $name    The name of the label to remove.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function removeFromIssue($owner, $repo, $number, $name)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . $number . '/labels/' . $name;

		// Send the request.
		return $this->processResponse(
			$this->client->delete($this->fetchUrl($path))
		);
	}

	/**
	 * Replace all labels for an issue.
	 *
	 * Sending an empty array ([]) will remove all Labels from the Issue.
	 *
	 * @param   string  $owner   The name of the owner of the GitHub repository.
	 * @param   string  $repo    The name of the GitHub repository.
	 * @param   string  $number  The issue number.
	 * @param   array   $labels  New labels
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function replace($owner, $repo, $number, array $labels)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . $number . '/labels';

		// Send the request.
		return $this->processResponse(
			$this->client->put($this->fetchUrl($path), json_encode($labels))
		);
	}

	/**
	 * Remove all labels from an issue.
	 *
	 * @param   string  $owner   The name of the owner of the GitHub repository.
	 * @param   string  $repo    The name of the GitHub repository.
	 * @param   string  $number  The issue number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function removeAllFromIssue($owner, $repo, $number)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/issues/' . $number . '/labels';

		// Send the request.
		return $this->processResponse(
			$this->client->delete($this->fetchUrl($path)),
			204
		);
	}

	/**
	 * Get labels for every issue in a milestone.
	 *
	 * @param   string  $owner   The name of the owner of the GitHub repository.
	 * @param   string  $repo    The name of the GitHub repository.
	 * @param   string  $number  The issue number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getListByMilestone($owner, $repo, $number)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/milestones/' . $number . '/labels';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}
}
