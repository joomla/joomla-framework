<?php
/**
 * Part of the Joomla Framework Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook;

use Joomla\Application\AbstractWebApplication;
use Joomla\OAuth2\Client;
use Joomla\Http\Http;
use Joomla\Input\Input;

/**
 * Joomla Framework class for generating Facebook API access token.
 *
 * @since  1.0
 */
class OAuth extends Client
{
	/**
	 * @var   array  Options for the OAuth object.
	 * @since 1.0
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   array                   $options      OAuth options array.
	 * @param   Http                    $client       The HTTP client object.
	 * @param   Input                   $input        The input object.
	 * @param   AbstractWebApplication  $application  The application object.
	 *
	 * @since   1.0
	 */
	public function __construct($options, Http $client, Input $input, AbstractWebApplication $application)
	{
		$this->options = $options;

		// Setup the authentication and token urls if not already set.
		if (!isset($this->options['authurl']))
		{
			$this->options['authurl'] = 'http://www.facebook.com/dialog/oauth';
		}

		if (!isset($this->options['tokenurl']))
		{
			$this->options['tokenurl'] = 'https://graph.facebook.com/oauth/access_token';
		}

		// Call the \Joomla\OAuth2\Client constructor to setup the object.
		parent::__construct($this->options, $client, $input, $application);
	}

	/**
	 * Method used to set permissions.
	 *
	 * @param   string  $scope  Comma separated list of permissions.
	 *
	 * @return  OAuth  This object for method chaining
	 *
	 * @since   1.0
	 */
	public function setScope($scope)
	{
		$this->setOption('scope', $scope);

		return $this;
	}

	/**
	 * Method to get the current scope
	 *
	 * @return  string Comma separated list of permissions.
	 *
	 * @since   1.0
	 */
	public function getScope()
	{
		return $this->getOption('scope');
	}
}
