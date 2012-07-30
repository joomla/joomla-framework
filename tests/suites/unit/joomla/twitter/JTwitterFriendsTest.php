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
require_once JPATH_PLATFORM . '/joomla/twitter/friends.php';

/**
 * Test class for JTwitterFriends.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.3
 */
class JTwitterFriendsTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.3
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock client object.
	 * @since  12.3
	 */
	protected $client;

	/**
	 * @var    JInput The input object to use in retrieving GET/POST data.
	 * @since  12.3
	 */
	protected $input;

	/**
	 * @var    JTwitterFriends  Object under test.
	 * @since  12.3
	 */
	protected $object;

	/**
	 * @var    JTwitterOauth  Authentication object for the Twitter object.
	 * @since  12.3
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
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
		$this->input = new JInput;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterFriends($this->options, $this->client);

		$this->options->set('consumer_key', $key);
		$this->options->set('consumer_secret', $secret);
		$this->options->set('callback', $my_url);
		$this->options->set('sendheaders', true);
		$this->oauth = new JTwitterOauth($this->options, $this->client, $this->input);
		$this->oauth->setToken(array('key' => $key, 'secret' => $secret));
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
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
	 * Tests the getFriendIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @covers JTwitterFriends::getFriendIds
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   12.3
	 */
	public function testGetFriendIds($user)
	{
		$string_ids = true;

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
			$this->object->getFriendIds($user, $string_ids);
		}

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friends/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendIds($user, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendIds method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @covers JTwitterFriends::getFriendIds
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendIdsFailure($user)
	{
		$string_ids = true;

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
			$this->object->getFriendIds($user, $string_ids);
		}

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friends/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendIds($user, $string_ids);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedFriendshipDetails()
	{
		// User IDs or screen names
		return array(
			array(234654235457, 2334657563),
			array(234654235457, 'userTest'),
			array('testUser', 2334657563),
			array('testUser', 'userTest'),
			array('testUser', null),
			array(null, 'userTest')
			);
	}

	/**
	 * Tests the getFriendshipDetails method
	 *
	 * @param   mixed  $user_a  Either an integer containing the user ID or a string containing the screen name of the first user.
	 * @param   mixed  $user_b  Either an integer containing the user ID or a string containing the screen name of the second user.
	 *
	 * @covers JTwitterFriends::getFriendshipDetails
	 *
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testGetFriendshipDetails($user_a, $user_b)
	{
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
		if (is_numeric($user_a))
		{
			$data['source_id'] = $user_a;
		}
		elseif (is_string($user_a))
		{
			$data['source_screen_name'] = $user_a;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipDetails($user_a, $user_b);
		}

		if (is_numeric($user_b))
		{
			$data['target_id'] = $user_b;
		}
		elseif (is_string($user_b))
		{
			$data['target_screen_name'] = $user_b;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipDetails($user_a, $user_b);
		}

		$path = $this->object->fetchUrl('/1/friendships/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipDetails($user_a, $user_b),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipDetails method - failure
	 *
	 * @param   mixed  $user_a  Either an integer containing the user ID or a string containing the screen name of the first user.
	 * @param   mixed  $user_b  Either an integer containing the user ID or a string containing the screen name of the second user.
	 *
	 * @covers JTwitterFriends::getFriendshipDetails
	 *
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipDetailsFailure($user_a, $user_b)
	{
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
		if (is_numeric($user_a))
		{
			$data['source_id'] = $user_a;
		}
		elseif (is_string($user_a))
		{
			$data['source_screen_name'] = $user_a;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipDetails($user_a, $user_b);
		}

		if (is_numeric($user_b))
		{
			$data['target_id'] = $user_b;
		}
		elseif (is_string($user_b))
		{
			$data['target_screen_name'] = $user_b;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipDetails($user_a, $user_b);
		}

		$path = $this->object->fetchUrl('/1/friendships/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipDetails($user_a, $user_b);
	}

	/**
	 * Tests the getFriendshipExists method
	 *
	 * @param   mixed  $user_a  Either an integer containing the user ID or a string containing the screen name of the first user.
	 * @param   mixed  $user_b  Either an integer containing the user ID or a string containing the screen name of the second user.
	 *
	 * @covers JTwitterFriends::getFriendshipExists
	 *
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testGetFriendshipExists($user_a, $user_b)
	{
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
		if (is_numeric($user_a))
		{
			$data['user_id_a'] = $user_a;
		}
		elseif (is_string($user_a))
		{
			$data['screen_name_a'] = $user_a;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipExists($user_a, $user_b);
		}

		if (is_numeric($user_b))
		{
			$data['user_id_b'] = $user_b;
		}
		elseif (is_string($user_b))
		{
			$data['screen_name_b'] = $user_b;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipExists($user_a, $user_b);
		}

		$path = $this->object->fetchUrl('/1/friendships/exists.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipExists($user_a, $user_b),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipExists method - failure
	 *
	 * @param   mixed  $user_a  Either an integer containing the user ID or a string containing the screen name of the first user.
	 * @param   mixed  $user_b  Either an integer containing the user ID or a string containing the screen name of the second user.
	 *
	 * @covers JTwitterFriends::getFriendshipExists
	 *
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipExistsFailure($user_a, $user_b)
	{
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
		if (is_numeric($user_a))
		{
			$data['user_id_a'] = $user_a;
		}
		elseif (is_string($user_a))
		{
			$data['screen_name_a'] = $user_a;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipExists($user_a, $user_b);
		}

		if (is_numeric($user_b))
		{
			$data['user_id_b'] = $user_b;
		}
		elseif (is_string($user_b))
		{
			$data['screen_name_b'] = $user_b;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipExists($user_a, $user_b);
		}

		$path = $this->object->fetchUrl('/1/friendships/exists.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipExists($user_a, $user_b);
	}

	/**
	 * Tests the getFollowerIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @covers JTwitterFriends::getFollowerIds
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   12.3
	 */
	public function testGetFollowerIds($user)
	{
		$string_ids = true;

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
			$this->object->getFollowerIds($user, $string_ids);
		}

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFollowerIds($user, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFollowerIds method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @covers JTwitterFriends::getFollowerIds
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   12.3
	 * @expectedException  DomainException
	 */
	public function testGetFollowerIdsFailure($user)
	{
		$string_ids = true;

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
			$this->object->getFollowerIds($user, $string_ids);
		}

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFollowerIds($user, $string_ids);
	}

	/**
	 * Tests the getFriendshipsIncoming method
	 *
	 * @covers JTwitterFriends::getFriendshipsIncoming
	 *
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testGetFriendshipsIncoming()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/incoming.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsIncoming($this->oauth, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsIncoming method - failure
	 *
	 * @covers JTwitterFriends::getFriendshipsIncoming
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsIncomingFailure()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/incoming.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsIncoming($this->oauth, $string_ids);
	}

	/**
	 * Tests the getFriendshipsOutgoing method
	 *
	 * @covers JTwitterFriends::getFriendshipsOutgoing
	 *
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testGetFriendshipsOutgoing()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/outgoing.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsOutgoing($this->oauth, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsOutgoing method - failure
	 *
	 * @covers JTwitterFriends::getFriendshipsOutgoing
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsOutgoingFailure()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/outgoing.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsOutgoing($this->oauth, $string_ids);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedFriendship()
	{
		// User ID or screen name
		return array(
			array('234654235457'),
			array('testUser'),
			array(null)
			);
	}

	/**
	 * Tests the follow method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 */
	public function testFollow($user)
	{
		$follow = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
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
			$this->object->follow($this->oauth, $user, $follow);
		}
		$data['follow'] = $follow;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->follow($this->oauth, $user, $follow),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the follow method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testFollowFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
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
			$this->object->follow($this->oauth, $user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->object->follow($this->oauth, $user);
	}

	/**
	 * Tests the unfollow method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 */
	public function testUnfollow($user)
	{
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
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
			$this->object->unfollow($this->oauth, $user, $entities);
		}
		$data['include_entities'] = $entities;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->unfollow($this->oauth, $user, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the unfollow method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testUnfollowFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
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
			$this->object->unfollow($this->oauth, $user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->object->unfollow($this->oauth, $user);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedFriendshipsLookup()
	{
		// User ID and screen name
		return array(
			array(null, '234654235457'),
			array(null, '234654235457,245864573437'),
			array('testUser', null),
			array('testUser', '234654235457'),
			array(null, null)
			);
	}

	/**
	 * Tests the getFriendshipsLookup method
	 *
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   string  $id           A comma separated list of user IDs, up to 100 are allowed in a single request.
	 *
	 * @covers JTwitterFriends::getFriendshipsLookup
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedFriendshipsLookup
	 */
	public function testGetFriendshipsLookup($screen_name, $id)
	{
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

		if ($id)
		{
			$data['user_id'] = $id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipsLookup($this->oauth, $screen_name, $id);
		}

		$path = $this->oauth->toUrl('/1/friendships/lookup.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsLookup($this->oauth, $screen_name, $id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsLookup method - failure
	 *
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   string  $id           A comma separated list of user IDs, up to 100 are allowed in a single request.
	 *
	 * @covers JTwitterFriends::getFriendshipsLookup
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedFriendshipsLookup
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsLookupFailure($screen_name, $id)
	{
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

		if ($id)
		{
			$data['user_id'] = $id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getFriendshipsLookup($this->oauth, $screen_name, $id);
		}

		$path = $this->oauth->toUrl('/1/friendships/lookup.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsLookup($this->oauth, $screen_name, $id);
	}

	/**
	 * Tests the updateFriendship method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 */
	public function testUpdateFriendship($user)
	{
		$device = true;
		$retweets = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
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
			$this->object->updateFriendship($this->oauth, $user, $device, $retweets);
		}
		$data['device'] = $device;
		$data['retweets'] = $retweets;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/update.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateFriendship($this->oauth, $user, $device, $retweets),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateFriendship method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testUpdateFriendshipFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
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
			$this->object->updateFriendship($this->oauth, $user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/update.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateFriendship($this->oauth, $user);
	}

	/**
	 * Tests the getFriendshipNoRetweetIds method
	 *
	 * @covers JTwitterFriends::getFriendshipNoRetweetIds
	 *
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testGetFriendshipNoRetweetIds()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/no_retweet_ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipNoRetweetIds($this->oauth, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipNoRetweetIds method - failure
	 *
	 * @covers JTwitterFriends::getFriendshipNoRetweetIds
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipNoRetweetIdsFailure()
	{
		$string_ids = true;

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

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/friendships/no_retweet_ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipNoRetweetIds($this->oauth, $string_ids);
	}
}
