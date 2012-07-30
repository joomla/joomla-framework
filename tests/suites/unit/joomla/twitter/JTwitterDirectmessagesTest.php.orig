<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/twitter.php';
require_once JPATH_PLATFORM . '/joomla/twitter/http.php';
require_once JPATH_PLATFORM . '/joomla/twitter/directmessages.php';

/**
 * Test class for JTwitterFriends.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.1
 */
class JTwitterDirectmessagesTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.1
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock client object.
	 * @since  12.1
	 */
	protected $client;

	/**
	 * @var    JTwitterDirectMessages  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * @var    JTwitterOAuth  Authentication object for the Twitter object.
	 * @since  12.1
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $rateLimit = '{"remaining_hits":150, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$key = "lIio7RcLe5IASG5jpnZrA";
		$secret = "dl3BrWij7LT04NUpy37BRJxGXpWgjNvMrneuQ11EveE";
		$my_url = "http://127.0.0.1/gsoc/joomla-platform/twitter_test.php";

		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterDirectmessages($this->options, $this->client);
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	protected function getMethod($name)
	{
		$class = new ReflectionClass('JTwitterDirectmessages');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * Tests the getDirectMessages method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDirectMessages()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['page'] = $page;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/direct_messages.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getDirectMessages($this->oauth, $since_id, $max_id, $count, $page, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDirectMessages method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   12.1
	 */
	public function testDirectMessagesFailure()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['page'] = $page;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/direct_messages.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getDirectMessages($this->oauth, $since_id, $max_id, $count, $page, $entities);
	}

	/**
	 * Tests the getSentDirectMessages method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testSentDirectMessages()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['page'] = $page;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/direct_messages/sent.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSentDirectMessages($this->oauth, $since_id, $max_id, $count, $page, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSentDirectMessages method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   12.1
	 */
	public function testSentDirectMessagesFailure()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['page'] = $page;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/direct_messages/sent.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSentDirectMessages($this->oauth, $since_id, $max_id, $count, $page, $entities);
	}
}
