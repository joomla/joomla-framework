<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Gists;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Gists Comments class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/gists/comments/
 *
 * @since  1.0
 */
class Comments extends AbstractPackage
{
	/**
	 * Method to create a comment on a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 * @param   string   $body    The comment body text.
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return  object
	 */
	public function create($gistId, $body)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/comments';

		// Build the request data.
		$data = json_encode(
			array(
				'body' => $body,
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
	 * Method to delete a comment on a gist.
	 *
	 * @param   integer  $commentId  The id of the comment to delete.
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return  void
	 */
	public function delete($commentId)
	{
		// Build the request path.
		$path = '/gists/comments/' . (int) $commentId;

		// Send the request.
		$response = $this->client->delete($this->fetchUrl($path));

		// Validate the response code.
		if ($response->code != 204)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}
	}

	/**
	 * Method to update a comment on a gist.
	 *
	 * @param   integer  $commentId  The id of the comment to update.
	 * @param   string   $body       The new body text for the comment.
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return  object
	 */
	public function edit($commentId, $body)
	{
		// Build the request path.
		$path = '/gists/comments/' . (int) $commentId;

		// Build the request data.
		$data = json_encode(
			array(
				'body' => $body
			)
		);

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
	 * Method to get a specific comment on a gist.
	 *
	 * @param   integer  $commentId  The comment id to get.
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return  object
	 */
	public function get($commentId)
	{
		// Build the request path.
		$path = '/gists/comments/' . (int) $commentId;

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
	 * Method to get the list of comments on a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 * @param   integer  $page    The page number from which to get items.
	 * @param   integer  $limit   The number of items on a page.
	 *
	 * @throws \DomainException
	 * @since  1.0
	 *
	 * @return  array
	 */
	public function getList($gistId, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/comments';

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
}
