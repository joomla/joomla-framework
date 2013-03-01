<?php
/**
 * @package    Joomla\Framework\Tests
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Oauth2\Client;
use Joomla\Google\Auth\Oauth2;
use Joomla\Registry\Registry;
use Joomla\Http\Http;
use Joomla\Input\Input;

/**
 * Test case for JGoogle.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Google
 * @since       12.3
 */
class GoogleTestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the Client object.
	 */
	protected $options;

	/**
	 * @var    Http  Mock client object.
	 */
	protected $http;

	/**
	 * @var    Input  The input object to use in retrieving GET/POST data.
	 */
	protected $input;

	/**
	 * @var    Client  The OAuth client for sending requests to Google.
	 */
	protected $oauth;

	/**
	 * @var    Oauth2  The Google OAuth client for sending requests.
	 */
	protected $auth;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$_SERVER['HTTP_HOST'] = 'mydomain.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$this->options = new Registry;
		$this->http = $this->getMock('Joomla\\Http\\Http', array('head', 'get', 'delete', 'trace', 'post', 'put', 'patch'), array($this->options));
		$this->input = new Input;
		$this->application = new JApplicationWebInspector;
		$this->oauth = new Client($this->options, $this->http, $this->input, $this->application);
		$this->auth = new Oauth2($this->options, $this->oauth);

		$token['access_token'] = 'accessvalue';
		$token['refresh_token'] = 'refreshvalue';
		$token['created'] = time() - 1800;
		$token['expires_in'] = 3600;
		$this->oauth->setToken($token);
	}
}
