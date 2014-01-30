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
 * GitHub API Search class for the Joomla Framework.
 *
 * @documentation http://developer.github.com/v3/search
 *
 * @since  1.0
 */
class Search extends AbstractPackage
{
	/**
	 * Search issues.
	 *
	 * @param   string  $owner    The name of the owner of the repository.
	 * @param   string  $repo     The name of the repository.
	 * @param   string  $state    The state - open or closed.
	 * @param   string  $keyword  The search term.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function issues($owner, $repo, $state, $keyword)
	{
		if (false == in_array($state, array('open', 'close')))
		{
			throw new \UnexpectedValueException('State must be either "open" or "closed"');
		}

		// Build the request path.
		$path = '/legacy/issues/search/' . $owner . '/' . $repo . '/' . $state . '/' . $keyword;

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Search repositories.
	 *
	 * Find repositories by keyword. Note, this legacy method does not follow
	 * the v3 pagination pattern.
	 * This method returns up to 100 results per page and pages can be fetched
	 * using the start_page parameter.
	 *
	 * @param   string   $keyword     The search term.
	 * @param   string   $language    Filter results by language https://github.com/languages
	 * @param   integer  $start_page  Page number to fetch
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function repositories($keyword, $language = '', $start_page = 0)
	{
		// Build the request path.
		$path = '/legacy/repos/search/' . $keyword . '?';

		$path .= ($language) ? '&language=' . $language : '';
		$path .= ($start_page) ? '&start_page=' . $start_page : '';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Search users.
	 *
	 * Find users by keyword.
	 *
	 * @param   string   $keyword     The search term.
	 * @param   integer  $start_page  Page number to fetch
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function users($keyword, $start_page = 0)
	{
		// Build the request path.
		$path = '/legacy/user/search/' . $keyword . '?';

		$path .= ($start_page) ? '&start_page=' . $start_page : '';

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}

	/**
	 * Email search.
	 *
	 * This API call is added for compatibility reasons only. There’s no guarantee
	 * that full email searches will always be available. The @ character in the
	 * address must be left unencoded. Searches only against public email addresses
	 * (as configured on the user’s GitHub profile).
	 *
	 * @param   string  $email  The email address(es).
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function email($email)
	{
		// Build the request path.
		$path = '/legacy/user/email/' . $email;

		// Send the request.
		return $this->processResponse(
			$this->client->get($this->fetchUrl($path))
		);
	}
}
