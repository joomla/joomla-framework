<?php
/**
 * Part of the Joomla Framework GitHub Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Repositories;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Repositories Contents class for the Joomla Framework.
 *
 * These API methods let you retrieve the contents of files within a repository as Base64 encoded content.
 * See media types for requesting raw or other formats.
 *
 * @documentation http://developer.github.com/v3/repos/contents
 *
 * @since  1.0
 */
class Contents extends AbstractPackage
{
	/**
	 * Get the README.
	 *
	 * This method returns the preferred README for a repository.
	 *
	 * @param   string  $owner  The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $ref    The String name of the Commit/Branch/Tag. Defaults to master.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function getReadme($owner, $repo, $ref = '')
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/readme';

		if ($ref)
		{
			$path .= '?ref=' . $ref;
		}

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Get contents.
	 *
	 * This method returns the contents of any file or directory in a repository.
	 *
	 * @param   string  $owner  The name of the owner of the GitHub repository.
	 * @param   string  $repo   The name of the GitHub repository.
	 * @param   string  $path   The content path.
	 * @param   string  $ref    The String name of the Commit/Branch/Tag. Defaults to master.
	 *
	 * @since  1.0
	 *
	 * @return object
	 */
	public function get($owner, $repo, $path, $ref = '')
	{
		// Build the request path.
		$rPath = '/repos/' . $owner . '/' . $repo . '/contents';

		$rPath .= '?path=' . $path;

		if ($ref)
		{
			$rPath .= '&ref=' . $ref;
		}

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($rPath))
		);
	}

	/**
	 * Get archive link.
	 *
	 * This method will return a 302 to a URL to download a tarball or zipball archive for a repository.
	 * Please make sure your HTTP framework is configured to follow redirects or you will need to use the
	 * Location header to make a second GET request.
	 *
	 * Note: For private repositories, these links are temporary and expire quickly.
	 *
	 * To follow redirects with curl, use the -L switch:
	 * curl -L https://api.github.com/repos/pengwynn/octokit/tarball > octokit.tar.gz
	 *
	 * @param   string  $owner           The name of the owner of the GitHub repository.
	 * @param   string  $repo            The name of the GitHub repository.
	 * @param   string  $archive_format  Either tarball or zipball.
	 * @param   string  $ref             The String name of the Commit/Branch/Tag. Defaults to master.
	 *
	 * @throws \UnexpectedValueException
	 * @since  1.0
	 *
	 * @return object
	 */
	public function getArchiveLink($owner, $repo, $archive_format = 'zipball', $ref = '')
	{
		if (false == in_array($archive_format, array('tarball', 'zipball')))
		{
			throw new \UnexpectedValueException('Archive format must be either "tarball" or "zipball".');
		}

		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/' . $archive_format;

		if ($ref)
		{
			$path .= '?ref=' . $ref;
		}

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path)),
			302
		);
	}

	/**
	 * Create a file.
	 *
	 * This method creates a new file in a repository.
	 *
	 * Optional Parameters
	 * The author section is optional and is filled in with the committer information if omitted.
	 * If the committer information is omitted, the authenticated user’s information is used.
	 *
	 * You must provide values for both name and email, whether you choose to use author or committer.
	 * Otherwise, you’ll receive a 500 status code.
	 *
	 * @param   string  $owner           The owner of the repository.
	 * @param   string  $repo            The repository name.
	 * @param   string  $path            The content path.
	 * @param   string  $message         The commit message.
	 * @param   string  $content         The new file content, Base64 encoded.
	 * @param   string  $branch          The branch name. If not provided, uses the repository’s
	 *                                   default branch (usually master).
	 * @param   string  $authorName      The name of the author of the commit
	 * @param   string  $authorEmail     The email of the author of the commit
	 * @param   string  $committerName   The name of the committer of the commit
	 * @param   string  $committerEmail  The email of the committer of the commit
	 *
	 * @throws \UnexpectedValueException
	 *
	 * @return object
	 */
	public function create($owner, $repo, $path, $message, $content, $branch = 'master',
		$authorName = '', $authorEmail = '', $committerName = '', $committerEmail = '')
	{
		// Build the request path.
		$route = '/repos/' . $owner . '/' . $repo . '/contents/' . $path;

		$data = array(
			'message' => $message,
			'content' => $content,
			'branch'  => $branch
		);

		if ($authorName)
		{
			if (!$authorEmail)
			{
				throw new \UnexpectedValueException('You must provide an author e-mail if you supply an author name');
			}

			$data['author'] = array(
				'name'  => $authorName,
				'email' => $authorEmail
			);
		}

		if ($committerName)
		{
			if (!$committerEmail)
			{
				throw new \UnexpectedValueException('You must provide a committer e-mail if you supply a committer name');
			}

			$data['committer'] = array(
				'name'  => $committerName,
				'email' => $committerEmail
			);
		}

		return $this->processResponse($this->client->put($this->fetchUrl($route), json_encode($data)), 201);
	}

	/**
	 * Update a file.
	 *
	 * This method updates a file in a repository.
	 *
	 * Optional Parameters
	 * The author section is optional and is filled in with the committer information if omitted.
	 * If the committer information is omitted, the authenticated user’s information is used.
	 *
	 * You must provide values for both name and email, whether you choose to use author or committer.
	 * Otherwise, you’ll receive a 500 status code.
	 *
	 * @param   string  $owner           The owner of the repository.
	 * @param   string  $repo            The repository name.
	 * @param   string  $path            The content path.
	 * @param   string  $message         The commit message.
	 * @param   string  $content         The new file content, Base64 encoded.
	 * @param   string  $sha             The blob SHA of the file being replaced.
	 * @param   string  $branch          The branch name. If not provided, uses the repository’s
	 *                                   default branch (usually master).
	 * @param   string  $authorName      The name of the author of the commit
	 * @param   string  $authorEmail     The email of the author of the commit
	 * @param   string  $committerName   The name of the committer of the commit
	 * @param   string  $committerEmail  The email of the committer of the commit
	 *
	 * @throws \UnexpectedValueException
	 *
	 * @return object
	 */
	public function update($owner, $repo, $path, $message, $content, $sha, $branch = 'master',
		$authorName = '', $authorEmail = '', $committerName = '', $committerEmail = '')
	{
		// Build the request path.
		$route = '/repos/' . $owner . '/' . $repo . '/contents/' . $path;

		$data = array(
			'message' => $message,
			'content' => $content,
			'sha'     => $sha,
			'branch'  => $branch
		);

		if ($authorName)
		{
			if (!$authorEmail)
			{
				throw new \UnexpectedValueException('You must provide an author e-mail if you supply an author name');
			}

			$data['author'] = array(
				'name'  => $authorName,
				'email' => $authorEmail
			);
		}

		if ($committerName)
		{
			if (!$committerEmail)
			{
				throw new \UnexpectedValueException('You must provide a committer e-mail if you supply a committer name');
			}

			$data['committer'] = array(
				'name'  => $committerName,
				'email' => $committerEmail
			);
		}

		return $this->processResponse($this->client->put($this->fetchUrl($route), json_encode($data)));
	}

	/**
	 * Delete a file.
	 *
	 * This method deletes a file in a repository.
	 *
	 * @param   string  $owner           The owner of the repository.
	 * @param   string  $repo            The repository name.
	 * @param   string  $path            The content path.
	 * @param   string  $message         The commit message.
	 * @param   string  $sha             The blob SHA of the file being replaced.
	 * @param   string  $branch          The branch name. If not provided, uses the repository’s
	 *                                   default branch (usually master).
	 * @param   string  $authorName      The name of the author of the commit
	 * @param   string  $authorEmail     The email of the author of the commit
	 * @param   string  $committerName   The name of the committer of the commit
	 * @param   string  $committerEmail  The email of the committer of the commit
	 *
	 * @throws \UnexpectedValueException
	 *
	 * @return object
	 */
	public function delete($owner, $repo, $path, $message, $sha, $branch = 'master',
		$authorName = '', $authorEmail = '', $committerName = '', $committerEmail = '')
	{
		// Build the request path.
		$route = '/repos/' . $owner . '/' . $repo . '/contents/' . $path;

		$data = array(
			'message' => $message,
			'sha'     => $sha,
			'branch'  => $branch
		);

		if ($authorName)
		{
			if (!$authorEmail)
			{
				throw new \UnexpectedValueException('You must provide an author e-mail if you supply an author name');
			}

			$data['author'] = array(
				'name'  => $authorName,
				'email' => $authorEmail
			);
		}

		if ($committerName)
		{
			if (!$committerEmail)
			{
				throw new \UnexpectedValueException('You must provide a committer e-mail if you supply a committer name');
			}

			$data['committer'] = array(
				'name'  => $committerName,
				'email' => $committerEmail
			);
		}

		return $this->processResponse(
			$this->client->delete(
				$this->fetchUrl($route),
				array(), null, json_encode($data)
			)
		);
	}
}
