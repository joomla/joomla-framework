<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

/**
 * GitHub API Commits class for the Joomla Framework.
 *
 * @since  1.0
 */
class Commits extends Object
{
	/**
	 * Method to create a commit.
	 *
	 * @param   string  $user     The name of the owner of the GitHub repository.
	 * @param   string  $repo     The name of the GitHub repository.
	 * @param   string  $message  The commit message.
	 * @param   string  $tree     SHA of the tree object this commit points to.
	 * @param   array   $parents  Array of the SHAs of the commits that were the parents of this commit.
	 *                            If omitted or empty, the commit will be written as a root commit.
	 *                            For a single parent, an array of one SHA should be provided.
	 *                            For a merge commit, an array of more than one should be provided.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function create($user, $repo, $message, $tree, array $parents = array())
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/git/commits';

		$data = json_encode(
			array('message' => $message, 'tree' => $tree, 'parents' => $parents)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to create a comment on a commit.
	 *
	 * @param   string   $user      The name of the owner of the GitHub repository.
	 * @param   string   $repo      The name of the GitHub repository.
	 * @param   string   $sha       The SHA of the commit to comment on.
	 * @param   string   $comment   The text of the comment.
	 * @param   integer  $line      The line number of the commit to comment on.
	 * @param   string   $filepath  A relative path to the file to comment on within the commit.
	 * @param   integer  $position  Line index in the diff to comment on.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createCommitComment($user, $repo, $sha, $comment, $line, $filepath, $position)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/commits/' . $sha . '/comments';

		$data = json_encode(
			array(
				'body' => $comment,
				'commit_id' => $sha,
				'line' => (int) $line,
				'path' => $filepath,
				'position' => (int) $position
			)
		);

		// Send the request.
		return $this->processResponse($this->client->post($this->fetchUrl($path), $data), 201);
	}

	/**
	 * Method to delete a comment on a commit.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $id    The ID of the comment to edit.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function deleteCommitComment($user, $repo, $id)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/comments/' . $id;

		// Send the request.
		return $this->processResponse($this->client->delete($this->fetchUrl($path)), 204);
	}

	/**
	 * Method to edit a comment on a commit.
	 *
	 * @param   string  $user     The name of the owner of the GitHub repository.
	 * @param   string  $repo     The name of the GitHub repository.
	 * @param   string  $id       The ID of the comment to edit.
	 * @param   string  $comment  The text of the comment.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function editCommitComment($user, $repo, $id, $comment)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/comments/' . $id;

		$data = json_encode(
			array(
				'body' => $comment
			)
		);

		// Send the request.
		return $this->processResponse($this->client->patch($this->fetchUrl($path), $data), 200);
	}

	/**
	 * Method to get a single commit for a repository.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   string   $sha    The SHA of the commit to retrieve.
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getCommit($user, $repo, $sha, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/commits/' . $sha;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}

	/**
	 * Method to get a single comment on a commit.
	 *
	 * @param   string   $user  The name of the owner of the GitHub repository.
	 * @param   string   $repo  The name of the GitHub repository.
	 * @param   integer  $id    ID of the comment to retrieve
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getCommitComment($user, $repo, $id)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/comments/' . $id;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to get a list of comments for a single commit for a repository.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   string   $sha    The SHA of the commit to retrieve.
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getCommitComments($user, $repo, $sha, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/commits/' . $sha . '/comments';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}

	/**
	 * Method to get a diff for two commits.
	 *
	 * @param   string  $user  The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   string  $base  The base of the diff, either a commit SHA or branch.
	 * @param   string  $head  The head of the diff, either a commit SHA or branch.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getDiff($user, $repo, $base, $head)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/compare/' . $base . '...' . $head;

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path)), 200);
	}

	/**
	 * Method to list commits for a repository.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getList($user, $repo, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/commits';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}

	/**
	 * Method to get a list of commit comments for a repository.
	 *
	 * @param   string   $user   The name of the owner of the GitHub repository.
	 * @param   string   $repo   The name of the GitHub repository.
	 * @param   integer  $page   Page to request
	 * @param   integer  $limit  Number of results to return per page
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getListComments($user, $repo, $page = 0, $limit = 0)
	{
		// Build the request path.
		$path = '/repos/' . $user . '/' . $repo . '/comments';

		// Send the request.
		return $this->processResponse($this->client->get($this->fetchUrl($path, $page, $limit)), 200);
	}
}
