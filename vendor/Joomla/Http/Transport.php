<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;

use Joomla\Registry\Registry;
use Joomla\Uri\Uri;

/**
 * HTTP transport class interface.
 *
 * @since  1.0
 */
interface Transport
{
	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Client options object.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options);

	/**
	 * Send a request to the server and return a JHttpResponse object with the response.
	 *
	 * @param   string   $method     The HTTP method for sending the request.
	 * @param   Uri      $uri        The URI to the resource to request.
	 * @param   mixed    $data       Either an associative array or a string to be sent with the request.
	 * @param   array    $headers    An array of request headers to send with the request.
	 * @param   integer  $timeout    Read timeout in seconds.
	 * @param   string   $userAgent  The optional user agent string to send with the request.
	 *
	 * @return  Response
	 *
	 * @since   1.0
	 */
	public function request($method, Uri $uri, $data = null, array $headers = null, $timeout = null, $userAgent = null);

	/**
	 * Method to check if http transport layer available for using
	 *
	 * @return  boolean  True if available else false
	 *
	 * @since   1.0
	 */
	public static function isSupported();
}
