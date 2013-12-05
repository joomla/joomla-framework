<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Gists class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/gists
 *
 * @since  1.0
 *
 * @property-read  Gists\Comments  $comments  GitHub API object for gist comments.
 */
class Gists extends AbstractPackage
{
	/**
	 * Method to create a gist.
	 *
	 * @param   mixed    $files        Either an array of file paths or a single file path as a string.
	 * @param   boolean  $public       True if the gist should be public.
	 * @param   string   $description  The optional description of the gist.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function create($files, $public = false, $description = null)
	{
		// Build the request path.
		$path = '/gists';

		// Build the request data.
		$data = json_encode(
			array(
				'files'       => $this->buildFileData((array) $files),
				'public'      => (bool) $public,
				'description' => $description
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
	 * Method to delete a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function delete($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId;

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
	 * Method to update a gist.
	 *
	 * @param   integer  $gistId       The gist number.
	 * @param   mixed    $files        Either an array of file paths or a single file path as a string.
	 * @param   boolean  $public       True if the gist should be public.
	 * @param   string   $description  The description of the gist.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function edit($gistId, $files = null, $public = null, $description = null)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId;

		// Craete the data object.
		$data = new \stdClass;

		// If a description is set add it to the data object.
		if (isset($description))
		{
			$data->description = $description;
		}

		// If the public flag is set add it to the data object.
		if (isset($public))
		{
			$data->public = $public;
		}

		// If a state is set add it to the data object.
		if (isset($files))
		{
			$data->files = $this->buildFileData((array) $files);
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
	 * Method to fork a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function fork($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/fork';

		// Send the request.
		// TODO: Verify change
		$response = $this->client->post($this->fetchUrl($path), '');

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
	 * Method to get a single gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function get($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId;

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
	 * Method to list gists.  If a user is authenticated it will return the user's gists, otherwise
	 * it will return all public gists.
	 *
	 * @param   integer  $page   The page number from which to get items.
	 * @param   integer  $limit  The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getList($page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/gists';

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
	 * Method to get a list of gists belonging to a given user.
	 *
	 * @param   string   $user   The name of the GitHub user from which to list gists.
	 * @param   integer  $page   The page number from which to get items.
	 * @param   integer  $limit  The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getListByUser($user, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/users/' . $user . '/gists';

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
	 * Method to get a list of all public gists.
	 *
	 * @param   integer  $page   The page number from which to get items.
	 * @param   integer  $limit  The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getListPublic($page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/gists/public';

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
	 * Method to get a list of the authenticated users' starred gists.
	 *
	 * @param   integer  $page   The page number from which to get items.
	 * @param   integer  $limit  The number of items on a page.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function getListStarred($page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/gists/starred';

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
	 * Method to check if a gist has been starred.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  boolean  True if gist is starred
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function isStarred($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/star';

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path));

		// Validate the response code.
		if ($response->code == 204)
		{
			return true;
		}
		elseif ($response->code == 404)
		{
			return false;
		}
		else
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}
	}

	/**
	 * Method to star a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function star($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/star';

		// Send the request.
		$response = $this->client->put($this->fetchUrl($path), '');

		// Validate the response code.
		if ($response->code != 204)
		{
			// Decode the error response and throw an exception.
			$error = json_decode($response->body);
			throw new \DomainException($error->message, $response->code);
		}
	}

	/**
	 * Method to star a gist.
	 *
	 * @param   integer  $gistId  The gist number.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function unstar($gistId)
	{
		// Build the request path.
		$path = '/gists/' . (int) $gistId . '/star';

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
	 * Method to fetch a data array for transmitting to the GitHub API for a list of files based on
	 * an input array of file paths or filename and content pairs.
	 *
	 * @param   array  $files  The list of file paths or filenames and content.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	protected function buildFileData(array $files)
	{
		$data = array();

		foreach ($files as $key => $file)
		{
			if (!is_numeric($key))
			{
				// If the key isn't numeric, then we are dealing with a file whose content has been supplied

				$data[$key] = array('content' => $file);
			}
			elseif (!file_exists($file))
			{
				// Otherwise, we have been given a path and we have to load the content
				// Verify that the each file exists.

				throw new \InvalidArgumentException('The file ' . $file . ' does not exist.');
			}
			else
			{
				$data[basename($file)] = array('content' => file_get_contents($file));
			}
		}

		return $data;
	}
}
