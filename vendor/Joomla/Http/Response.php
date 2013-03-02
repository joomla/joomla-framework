<?php
/**
 * @package     Joomla\Framework
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;

/**
 * HTTP response data object class.
 *
 * @package  Joomla\Framework
 * @since    1.0
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
