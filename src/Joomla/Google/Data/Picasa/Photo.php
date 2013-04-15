<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Data\Picasa;

use Joomla\Google\Data;
use Joomla\Google\Auth;
use Joomla\Registry\Registry;

/**
 * Google Picasa data class for the Joomla Framework.
 *
 * @since  1.0
 */
class Photo extends Data
{
	/**
	 * @var    \SimpleXMLElement  The photo's XML
	 * @since  1.0
	 */
	protected $xml;

	/**
	 * Constructor.
	 *
	 * @param   \SimpleXMLElement  $xml      XML from Google
	 * @param   Registry           $options  Google options object
	 * @param   Auth               $auth     Google data http client object
	 *
	 * @since   1.0
	 */
	public function __construct(\SimpleXMLElement $xml, Registry $options = null, Auth $auth = null)
	{
		$this->xml = $xml;

		parent::__construct($options, $auth);

		if (isset($this->auth) && !$this->auth->getOption('scope'))
		{
			$this->auth->setOption('scope', 'https://picasaweb.google.com/data/');
		}
	}

	/**
	 * Method to delete a Picasa photo
	 *
	 * @param   mixed  $match  Check for most up to date photo
	 *
	 * @return  boolean  Success or failure.
	 *
	 * @since   1.0
	 * @throws  \Exception
	 * @throws  \RuntimeException
	 * @throws  \UnexpectedValueException
	 */
	public function delete($match = '*')
	{
		if ($this->isAuthenticated())
		{
			$url = $this->getLink();

			if ($match === true)
			{
				$match = $this->xml->xpath('./@gd:etag');
				$match = $match[0];
			}

			try
			{
				$jdata = $this->query($url, null, array('GData-Version' => 2, 'If-Match' => $match), 'delete');
			}
			catch (\Exception $e)
			{
				if (strpos($e->getMessage(), 'Error code 412 received requesting data: Mismatch: etags') === 0)
				{
					throw new \RuntimeException("Etag match failed: `$match`.");
				}

				throw $e;
			}

			if ($jdata->body != '')
			{
				throw new \UnexpectedValueException("Unexpected data received from Google: `{$jdata->body}`.");
			}

			$this->xml = null;

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to get the photo link
	 *
	 * @param   string  $type  Type of link to return
	 *
	 * @return  string  Link or false on failure
	 *
	 * @since   1.0
	 */
	public function getLink($type = 'edit')
	{
		$links = $this->xml->link;

		foreach ($links as $link)
		{
			if ($link->attributes()->rel == $type)
			{
				return (string) $link->attributes()->href;
			}
		}

		return false;
	}

	/**
	 * Method to get the photo's URL
	 *
	 * @return  string  Link
	 *
	 * @since   1.0
	 */
	public function getURL()
	{
		return (string) $this->xml->children()->content->attributes()->src;
	}

	/**
	 * Method to get the photo's thumbnails
	 *
	 * @return  array  An array of thumbnails
	 *
	 * @since   1.0
	 */
	public function getThumbnails()
	{
		$thumbs = array();

		foreach ($this->xml->children('media', true)->group->thumbnail as $item)
		{
			$url = (string) $item->attributes()->url;
			$width = (int) $item->attributes()->width;
			$height = (int) $item->attributes()->height;
			$thumbs[$width] = array('url' => $url, 'w' => $width, 'h' => $height);
		}

		return $thumbs;
	}

	/**
	 * Method to get the title of the photo
	 *
	 * @return  string  Photo title
	 *
	 * @since   1.0
	 */
	public function getTitle()
	{
		return (string) $this->xml->children()->title;
	}

	/**
	 * Method to get the summary of the photo
	 *
	 * @return  string  Photo description
	 *
	 * @since   1.0
	 */
	public function getSummary()
	{
		return (string) $this->xml->children()->summary;
	}

	/**
	 * Method to get the access level of the photo
	 *
	 * @return  string  Photo access level
	 *
	 * @since   1.0
	 */
	public function getAccess()
	{
		return (string) $this->xml->children('gphoto', true)->access;
	}

	/**
	 * Method to get the time of the photo
	 *
	 * @return  double  Photo time
	 *
	 * @since   1.0
	 */
	public function getTime()
	{
		return (double) $this->xml->children('gphoto', true)->timestamp / 1000;
	}

	/**
	 * Method to get the size of the photo
	 *
	 * @return  int  Photo size
	 *
	 * @since   1.0
	 */
	public function getSize()
	{
		return (int) $this->xml->children('gphoto', true)->size;
	}

	/**
	 * Method to get the height of the photo
	 *
	 * @return  int  Photo height
	 *
	 * @since   1.0
	 */
	public function getHeight()
	{
		return (int) $this->xml->children('gphoto', true)->height;
	}

	/**
	 * Method to get the width of the photo
	 *
	 * @return  int  Photo width
	 *
	 * @since   1.0
	 */
	public function getWidth()
	{
		return (int) $this->xml->children('gphoto', true)->width;
	}

	/**
	 * Method to set the title of the photo
	 *
	 * @param   string  $title  New photo title
	 *
	 * @return  Photo  The object for method chaining
	 *
	 * @since   1.0
	 */
	public function setTitle($title)
	{
		$this->xml->children()->title = $title;

		return $this;
	}

	/**
	 * Method to set the summary of the photo
	 *
	 * @param   string  $summary  New photo description
	 *
	 * @return  Photo  The object for method chaining
	 *
	 * @since   1.0
	 */
	public function setSummary($summary)
	{
		$this->xml->children()->summary = $summary;

		return $this;
	}

	/**
	 * Method to set the access level of the photo
	 *
	 * @param   string  $access  New photo access level
	 *
	 * @return  Photo  The object for method chaining
	 *
	 * @since   1.0
	 */
	public function setAccess($access)
	{
		$this->xml->children('gphoto', true)->access = $access;

		return $this;
	}

	/**
	 * Method to set the time of the photo
	 *
	 * @param   int  $time  New photo time
	 *
	 * @return  Photo  The object for method chaining
	 *
	 * @since   1.0
	 */
	public function setTime($time)
	{
		$this->xml->children('gphoto', true)->timestamp = $time * 1000;

		return $this;
	}

	/**
	 * Method to modify a Picasa Photo
	 *
	 * @param   string  $match  Optional eTag matching parameter
	 *
	 * @return  mixed  Data from Google.
	 *
	 * @since   1.0
	 * @throws  \Exception
	 * @throws  \RuntimeException
	 */
	public function save($match = '*')
	{
		if ($this->isAuthenticated())
		{
			$url = $this->getLink();

			if ($match === true)
			{
				$match = $this->xml->xpath('./@gd:etag');
				$match = $match[0];
			}

			try
			{
				$headers = array('GData-Version' => 2, 'Content-type' => 'application/atom+xml', 'If-Match' => $match);
				$jdata = $this->query($url, $this->xml->asXML(), $headers, 'put');
			}
			catch (\Exception $e)
			{
				if (strpos($e->getMessage(), 'Error code 412 received requesting data: Mismatch: etags') === 0)
				{
					throw new \RuntimeException("Etag match failed: `$match`.");
				}

				throw $e;
			}

			$this->xml = $this->safeXML($jdata->body);

			return $this;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Refresh photo data
	 *
	 * @return  mixed  Data from Google
	 *
	 * @since   1.0
	 */
	public function refresh()
	{
		if ($this->isAuthenticated())
		{
			$url = $this->getLink();
			$jdata = $this->query($url, null, array('GData-Version' => 2));
			$this->xml = $this->safeXML($jdata->body);

			return $this;
		}
		else
		{
			return false;
		}
	}
}
