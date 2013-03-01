<?php
/**
 * @package     Joomla\Framework
 * @subpackage  HTTP
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http;


/**
 * HTTP response data object class.
 *
 * @package     Joomla\Framework
 * @subpackage  HTTP
 * @since       11.3
 */
class Response
{
	/**
	 * @var    integer  The server response code.
	 * @since  11.3
	 */
	public $code;

	/**
	 * @var    array  Response headers.
	 * @since  11.3
	 */
	public $headers = array();

	/**
	 * @var    string  Server response body.
	 * @since  11.3
	 */
	public $body;
}
