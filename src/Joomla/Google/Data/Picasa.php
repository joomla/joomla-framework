<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Data;

use Joomla\Google\Data;
use Joomla\Google\Auth;
use Joomla\Registry\Registry;

/**
 * Google Picasa data class for the Joomla Framework.
 *
 * @since  1.0
 */
class Picasa extends Data
{
	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Google options object
	 * @param   Auth      $auth     Google data http client object
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options = null, Auth $auth = null)
	{
		parent::__construct($options, $auth);

		if (isset($this->auth) && !$this->auth->getOption('scope'))
		{
			$this->auth->setOption('scope', 'https://picasaweb.google.com/data/');
		}
	}

	/**
	 * Method to retrieve a list of Picasa Albums
	 *
	 * @param   string  $userID  ID of user
	 *
	 * @return  mixed  Data from Google
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function listAlbums($userID = 'default')
	{
		if ($this->isAuthenticated())
		{
			$url = 'https://picasaweb.google.com/data/feed/api/user/' . urlencode($userID);
			$jdata = $this->query($url, null, array('GData-Version' => 2));
			$xml = $this->safeXML($jdata->body);

			if (isset($xml->children()->entry))
			{
				$items = array();

				foreach ($xml->children()->entry as $item)
				{
					$items[] = new Picasa\Album($item, $this->options, $this->auth);
				}

				return $items;
			}
			else
			{
				throw new \UnexpectedValueException("Unexpected data received from Google: `{$jdata->body}`.");
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to create a Picasa Album
	 *
	 * @param   string   $userID    ID of user
	 * @param   string   $title     New album title
	 * @param   string   $access    New album access settings
	 * @param   string   $summary   New album summary
	 * @param   string   $location  New album location
	 * @param   integer  $time      New album timestamp
	 * @param   array    $keywords  New album keywords
	 *
	 * @return  mixed  Data from Google.
	 *
	 * @since   1.0
	 */
	public function createAlbum($userID = 'default', $title = '', $access = 'private', $summary = '', $location = '', $time = false, $keywords = array())
	{
		if ($this->isAuthenticated())
		{
			$time = $time ? $time : time();
			$title = $title != '' ? $title : date('F j, Y');
			$xml = new \SimpleXMLElement('<entry></entry>');
			$xml->addAttribute('xmlns', 'http://www.w3.org/2005/Atom');
			$xml->addChild('title', $title);
			$xml->addChild('summary', $summary);
			$xml->addChild('gphoto:location', $location, 'http://schemas.google.com/photos/2007');
			$xml->addChild('gphoto:access', $access);
			$xml->addChild('gphoto:timestamp', $time);
			$media = $xml->addChild('media:group', '', 'http://search.yahoo.com/mrss/');
			$media->addChild('media:keywords', implode($keywords, ', '));
			$cat = $xml->addChild('category', '');
			$cat->addAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
			$cat->addAttribute('term', 'http://schemas.google.com/photos/2007#album');

			$url = 'https://picasaweb.google.com/data/feed/api/user/' . urlencode($userID);
			$jdata = $this->query($url, $xml->asXML(), array('GData-Version' => 2, 'Content-type' => 'application/atom+xml'), 'post');

			$xml = $this->safeXML($jdata->body);

			return new Picasa\Album($xml, $this->options, $this->auth);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get Picasa Album
	 *
	 * @param   string  $url  URL of album to get
	 *
	 * @return  mixed  Data from Google
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getAlbum($url)
	{
		if ($this->isAuthenticated())
		{
			$jdata = $this->query($url, null, array('GData-Version' => 2));
			$xml = $this->safeXML($jdata->body);

			return new Picasa\Album($xml, $this->options, $this->auth);
		}
		else
		{
			return false;
		}
	}
}
