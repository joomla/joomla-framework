<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Repositories;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API References class for the Joomla Platform.
 *
 * @documentation http://developer.github.com/v3/repos/releases
 *
 * @since  1.1.0
 */
class Releases extends AbstractPackage
{
	/**
	 * Method to create a release.
	 *
	 * @param   string   $user             The name of the owner of the GitHub repository.
	 * @param   string   $repo             The name of the GitHub repository.
	 * @param   string   $tagName          The name of the tag.
	 * @param   string   $targetCommitish  The commitish value that determines where the Git tag is created from.
	 * @param   string   $name             The branch (or git ref) you want your changes pulled into. This
	 *                                     should be an existing branch on the current repository. You cannot
	 *                                     submit a pull request to one repo that requests a merge to a base
	 *                                     of another repo.
	 * @param   string   $body             Text describing the contents of the tag.
	 * @param   boolean  $draft            True to create a draft (unpublished) release, false to create a published one.
	 * @param   boolean  $preRelease       True to identify the release as a prerelease. false to identify the release as a full release.
	 *
	 * @return  object
	 *
	 * @link    http://developer.github.com/v3/repos/releases/#create-a-release
	 * @since   1.1.0
	 */
	public function create($user, $repo, $tagName, $targetCommitish = '', $name = '', $body = '', $draft = false, $preRelease = false)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/releases';

		// Build the request data.
		$data = json_encode(
			array(
				'tag_name' => $tagName,
				'target_commitish' => $targetCommitish,
				'name' => $name,
				'body' => $body,
				'draft' => (boolean) $draft,
				'prerelease' => (boolean) $preRelease,
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to update a release.
	 *
	 * @param   string   $user             The name of the owner of the GitHub repository.
	 * @param   string   $repo             The name of the GitHub repository.
	 * @param   integer  $releaseId        The release id.
	 * @param   string   $tagName          The name of the tag.
	 * @param   string   $targetCommitish  The commitish value that determines where the Git tag is created from.
	 * @param   string   $name             The branch (or git ref) you want your changes pulled into. This
	 *                                     should be an existing branch on the current repository. You cannot
	 *                                     submit a pull request to one repo that requests a merge to a base
	 *                                     of another repo.
	 * @param   boolean  $body             The body text for the new pull request.
	 * @param   boolean  $draft            The branch (or git ref) where your changes are implemented.
	 * @param   string   $preRelease       The branch (or git ref) where your changes are implemented.
	 *
	 * @return  object
	 *
	 * @link    http://developer.github.com/v3/repos/releases/#edit-a-release
	 * @since   1.1.0
	 * @throws  \DomainException
	 */
	public function edit($user, $repo, $releaseId, $tagName,
		$targetCommitish = null, $name = null, $body = null, $draft = null, $preRelease = null)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/releases/' . (int) $releaseId;

		// Create the data object.
		$data = new \stdClass;
		$data->tag_name = $tagName;

		// Check if input values are set and add them to the data object.
		if (isset($targetCommitish))
		{
			$data->target_commitish = $targetCommitish;
		}

		if (isset($name))
		{
			$data->name = $name;
		}

		if (isset($body))
		{
			$data->body = $body;
		}

		if (isset($draft))
		{
			$data->draft = $draft;
		}

		if (isset($preRelease))
		{
			$data->prerelease = $preRelease;
		}

		// Encode the request data.
		$data = json_encode($data);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data));
	}

	/**
	 * Method to get a single release.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $ref   The reference to get.
	 *
	 * @return  object
	 *
	 * @since   1.1.0
	 * @throws  \DomainException
	 */
	public function get($user, $repo, $ref)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/releases/' . $ref;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)));
	}

	/**
	 * Method to list all releases.
	 *
	 * @param   string   $user       The name of the owner of the GitHub repository.
	 * @param   string   $repo       The name of the GitHub repository.
	 * @param   integer  $page       The page number from which to get items.
	 * @param   integer  $limit      The number of items on a page.
	 *
	 * @return  array  An associative array of releases keyed by the tag name.
	 *
	 * @since   1.1.0
	 * @throws  \DomainException
	 */
	public function getList($user, $repo, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/releases';

		// Send the request.
		$response = $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)));

		$releases = array();

		if (is_array($response))
		{
			foreach ($response as $release)
			{
				$releases[$release->tag_name] = $release;
			}
		}

		return $releases;
	}
}
