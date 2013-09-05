<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\Twitter\Object;

/**
 * Twitter API Trends class for the Joomla Framework.
 *
 * @since  1.0
 */
class Trends extends Object
{
	/**
	 * Method to get the top 10 trending topics for a specific WOEID, if trending information is available for it.
	 *
	 * @param   integer  $id       The Yahoo! Where On Earth ID of the location to return trending information for.
	 * 							   Global information is available by using 1 as the WOEID.
	 * @param   string   $exclude  Setting this equal to hashtags will remove all hashtags from the trends list.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getTrends($id, $exclude = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('trends', 'place');

		// Set the API path
		$path = '/trends/place.json';

		$data['id'] = $id;

		// Check if exclude is specified
		if ($exclude)
		{
			$data['exclude'] = $exclude;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to get the locations that Twitter has trending topic information for.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getLocations()
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('trends', 'available');

		// Set the API path
		$path = '/trends/available.json';

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the locations that Twitter has trending topic information for, closest to a specified location.
	 *
	 * @param   float  $lat   The latitude to search around.
	 * @param   float  $long  The longitude to search around.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getClosest($lat = null, $long = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('trends', 'closest');

		// Set the API path
		$path = '/trends/closest.json';

		$data = array();

		// Check if lat is specified
		if ($lat)
		{
			$data['lat'] = $lat;
		}

		// Check if long is specified
		if ($long)
		{
			$data['long'] = $long;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}
}
