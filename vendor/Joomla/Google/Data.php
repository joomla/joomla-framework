<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google;

use Joomla\Registry\Registry;
use UnexpectedValueException;
use SimpleXMLElement;
use Exception;

/**
 * Google API data class for the Joomla Platform.
 *
 * @since  1.0
 */
abstract class Data
{
	/**
	 * @var    Registry  Options for the Google data object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Auth  Authentication client for the Google data object.
	 * @since  1.0
	 */
	protected $auth;

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Google options object.
	 * @param   Auth      $auth     Google data http client object.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options = null, Auth $auth = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->auth = isset($auth) ? $auth : new Auth\Oauth2($this->options);
	}

	/**
	 * Method to authenticate to Google
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 */
	public function authenticate()
	{
		return $this->auth->authenticate();
	}

	/**
	 * Check authentication
	 *
	 * @return  boolean  True if authenticated.
	 *
	 * @since   1.0
	 */
	public function isAuthenticated()
	{
		return $this->auth->isAuthenticated();
	}

	/**
	 * Method to validate XML
	 *
	 * @param   string  $data  XML data to be parsed
	 *
	 * @return  SimpleXMLElement  XMLElement of parsed data
	 *
	 * @since   1.0
	 * @throws  UnexpectedValueException
	 */
	protected static function safeXML($data)
	{
		try
		{
			return new SimpleXMLElement($data, LIBXML_NOWARNING | LIBXML_NOERROR);
		}
		catch (Exception $e)
		{
			throw new UnexpectedValueException("Unexpected data received from Google: `$data`.");
		}
	}

	/**
	 * Method to retrieve a list of data
	 *
	 * @param   array    $url       URL to GET
	 * @param   integer  $maxpages  Maximum number of pages to return
	 * @param   string   $token     Next page token
	 *
	 * @return  mixed  Data from Google
	 *
	 * @since   1.0
	 * @throws  UnexpectedValueException
	 */
	protected function listGetData($url, $maxpages = 1, $token = null)
	{
		$qurl = $url;

		if (strpos($url, '&') && isset($token))
		{
			$qurl .= '&pageToken=' . $token;
		}
		elseif (isset($token))
		{
			$qurl .= 'pageToken=' . $token;
		}

		$jdata = $this->query($qurl);
		$data = json_decode($jdata->body, true);

		if ($data && array_key_exists('items', $data))
		{
			if ($maxpages != 1 && array_key_exists('nextPageToken', $data))
			{
				$data['items'] = array_merge($data['items'], $this->listGetData($url, $maxpages - 1, $data['nextPageToken']));
			}

			return $data['items'];
		}
		elseif ($data)
		{
			return array();
		}
		else
		{
			throw new UnexpectedValueException("Unexpected data received from Google: `{$jdata->body}`.");
		}
	}

	/**
	 * Method to retrieve data from Google
	 *
	 * @param   string  $url      The URL for the request.
	 * @param   mixed   $data     The data to include in the request.
	 * @param   array   $headers  The headers to send with the request.
	 * @param   string  $method   The type of http request to send.
	 *
	 * @return  mixed  Data from Google.
	 *
	 * @since   1.0
	 */
	protected function query($url, $data = null, $headers = null, $method = 'get')
	{
		return $this->auth->query($url, $data, $headers, $method);
	}

	/**
	 * Get an option from the Data instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the Data instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Data  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
