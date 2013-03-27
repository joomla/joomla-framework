<?php
/**
 * Part of the Joomla Framework Http Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;

/**
 * HTTP response data object class.
 *
 * @since  1.0
 */
class Response
{
	/**
	 * @var    integer  The server response code.
	 * @since  1.0
	 */
	public $code;

	/**
	 * @var    array  Response headers.
	 * @since  1.0
	 */
	public $headers = array();

	/**
	 * @var    string  Server response body.
	 * @since  1.0
	 */
	public $body;
}
