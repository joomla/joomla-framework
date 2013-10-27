<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\OAuth;
use Joomla\Input\Input;
use Joomla\Test\WebInspector;

/**
 * Test case for Facebook.
 *
 * @since  1.0
 */
class FacebookTestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    array  Options for the Facebook object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    OAuth  OAuth client for Facebook.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    object  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    object  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    WebInspector  The application object to send HTTP headers for redirects.
	 */
	protected $application;

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  1.0
	 */
	protected $errorString = '{"error": {"message": "Generic Error."}}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access  protected
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$app_id = "app_id";
		$app_secret = "app_secret";
		$my_url = "http://localhost/facebook_test.php";
		$access_token = array(
				'access_token' => 'token',
				'expires' => '51837673', 'created' => '2443672521');

		$this->options = array();

		$this->options['clientid'] = $app_id;
		$this->options['clientsecret'] = $app_secret;
		$this->options['redirecturi'] = $my_url;
		$this->options['sendheaders'] = true;
		$this->options['authmethod'] = 'get';

		$this->client = $this->getMock('Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));
		$this->input = new Input;
		$this->application = new WebInspector;
		$this->oauth = new OAuth($this->options, $this->client, $this->input, $this->application);
		$this->oauth->setToken($access_token);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
	}
}
