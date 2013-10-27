<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\Twitter\OAuth;
use Joomla\Test\WebInspector;

/**
 * Test case for Twitter.
 *
 * @since  1.0
 */
class TwitterTestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    array  Options for the object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  Mock http object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Input The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $input;

	/**
	 * @var    WebInspector The application object to send HTTP headers for redirects.
	 * @since  1.0
	 */
	protected $application;

	/**
	 * @var    OAuth  Authentication object for the Twitter object.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    object  Object under test (companies, groups, jobs ..).
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$key = "app_key";
		$secret = "app_secret";
		$my_url = "http://127.0.0.1/twitter_test.php";

		$access_token = array('key' => 'token_key', 'secret' => 'token_secret');

		$this->options = array();

		$this->options['consumer_key'] = $key;
		$this->options['consumer_secret'] = $secret;
		$this->options['callback'] = $my_url;
		$this->options['sendheaders'] = true;

		$this->input = new Input;
		$this->client = $this->getMock('Joomla\\Http\\Http', array('get', 'post', 'delete', 'put'));
		$this->application = new WebInspector;
		$this->oauth = new OAuth($this->options, $this->client, $this->input, $this->application);
		$this->oauth->setToken($access_token);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}
}
