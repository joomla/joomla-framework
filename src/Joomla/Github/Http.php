<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

use Joomla\Http\Http as BaseHttp;
use Joomla\Http\TransportInterface;

/**
 * HTTP client class for connecting to a GitHub instance.
 *
 * @since  1.0
 */
class Http extends BaseHttp
{
	/**
	 * @const  integer  Use no authentication for HTTP connections.
	 * @since  1.0
	 */
	const AUTHENTICATION_NONE = 0;

	/**
	 * @const  integer  Use basic authentication for HTTP connections.
	 * @since  1.0
	 */
	const AUTHENTICATION_BASIC = 1;

	/**
	 * @const  integer  Use OAuth authentication for HTTP connections.
	 * @since  1.0
	 */
	const AUTHENTICATION_OAUTH = 2;

	/**
	 * Constructor.
	 *
	 * @param   array               $options    Client options array.
	 * @param   TransportInterface  $transport  The HTTP transport object.
	 *
	 * @since   1.0
	 */
	public function __construct($options = array(), TransportInterface $transport = null)
	{
		// Call the JHttp constructor to setup the object.
		parent::__construct($options, $transport);

		// Make sure the user agent string is defined.
		if (!isset($this->options['userAgent']))
		{
			$this->options['userAgent'] = 'JGitHub/2.0';
		}

		// Set the default timeout to 120 seconds.
		if (!isset($this->options['timeout']))
		{
			$this->options['timeout'] = 120;
		}
	}
}
