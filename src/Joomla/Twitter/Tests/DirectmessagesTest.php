<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Directmessages;
use \DomainException;
use \RuntimeException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Friends.
 *
 * @since  1.0
 */
class DirectmessagesTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON Twitter error message.
	 * @since  1.0
	 */
	protected $twitterErrorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $rateLimit = '{"resources": {"direct_messages": {
			"/direct_messages": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/direct_messages/sent": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/direct_messages/show": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

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
		parent::setUp();

		$this->object = new Directmessages($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getDirectMessages method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDirectMessages()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/direct_messages.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getDirectMessages($since_id, $max_id, $count, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDirectMessages method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetDirectMessagesFailure()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['count'] = $count;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/direct_messages.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getDirectMessages($since_id, $max_id, $count, $entities);
	}

	/**
	 * Tests the getGetSentDirectMessages method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetSentDirectMessages()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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

		$path = $this->object->fetchUrl('/direct_messages/sent.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSentDirectMessages($since_id, $max_id, $count, $page, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getGetSentDirectMessages method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetSentDirectMessagesFailure()
	{
		$since_id = 12345;
		$max_id = 54321;
		$count = 10;
		$page = 1;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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

		$path = $this->object->fetchUrl('/direct_messages/sent.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSentDirectMessages($since_id, $max_id, $count, $page, $entities);
	}

	/**
	 * Provides test data for request format detection.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function seedUser()
	{
		// User ID or screen name
		return array(
			array(234654235457),
			array('testUser'),
			array(null)
			);
	}

	/**
	 * Tests the sendDirectMessages method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testSendDirectMessages($user)
	{
		$text = 'This is a test.';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->sendDirectMessages($user, $text);
		}

		$data['text'] = $text;

		$path = $this->object->fetchUrl('/direct_messages/new.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->sendDirectMessages($user, $text),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the sendDirectMessages method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testSendDirectMessagesFailure($user)
	{
		$text = 'This is a test.';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->sendDirectMessages($user, $text);
		}

		$data['text'] = $text;

		$path = $this->object->fetchUrl('/direct_messages/new.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->sendDirectMessages($user, $text);
	}

	/**
	 * Tests the getDirectMessagesById method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDirectMessagesById()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['id'] = $id;

		$path = $this->object->fetchUrl('/direct_messages/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getDirectMessagesById($id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDirectMessagesById method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetDirectMessagesByIdFailure()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "direct_messages"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->twitterErrorString;

		$data['id'] = $id;

		$path = $this->object->fetchUrl('/direct_messages/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getDirectMessagesById($id);
	}

	/**
	 * Tests the deleteDirectMessages method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteDirectMessages()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/direct_messages/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteDirectMessages($id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteDirectMessages method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testDeleteDirectMessagesFailure()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/direct_messages/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->deleteDirectMessages($id, $entities);
	}
}
