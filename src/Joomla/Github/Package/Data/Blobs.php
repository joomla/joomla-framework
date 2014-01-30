<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Package\Data;

use Joomla\Github\AbstractPackage;

/**
 * GitHub API Data Blobs class for the Joomla Framework.
 *
 * Since blobs can be any arbitrary binary data, the input and responses for the blob API
 * takes an encoding parameter that can be either utf-8 or base64. If your data cannot be
 * losslessly sent as a UTF-8 string, you can base64 encode it.
 *
 * @documentation http://developer.github.com/v3/git/blobs/
 *
 * @since  1.0
 */
class Blobs extends AbstractPackage
{
	/**
	 * Get a Blob.
	 *
	 * @param   string  $owner  Repository owner.
	 * @param   string  $repo   Repository name.
	 * @param   string  $sha    The commit SHA.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function get($owner, $repo, $sha)
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/git/blobs/' . $sha;

		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Create a Blob.
	 *
	 * @param   string  $owner     Repository owner.
	 * @param   string  $repo      Repository name.
	 * @param   string  $content   The content of the blob.
	 * @param   string  $encoding  The encoding to use.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function create($owner, $repo, $content, $encoding = 'utf-8')
	{
		// Build the request path.
		$path = '/repos/' . $owner . '/' . $repo . '/git/blobs';

		$data = array(
			'content'  => $content,
			'encoding' => $encoding
		);

		return $this->processResponse(
			$this->client->post($this->fetchUrl($path), json_encode($data)),
			201
		);
	}
}
