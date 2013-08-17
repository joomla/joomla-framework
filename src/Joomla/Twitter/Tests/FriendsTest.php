<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Friends;
use \DomainException;
use \RuntimeException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Friends.
 *
 * @since  1.0
 */
class FriendsTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $friendsRateLimit = '{"resources": {"friends": {
			"/friends/ids": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $friendshipsRateLimit = '{"resources": {"friendships": {
			"/friendships/show": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/friendships/incoming": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/friendships/outgoing": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/friendships/create": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/friendships/lookup": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/friendships/no_retweets/ids": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $followersRateLimit = '{"resources": {"followers": {
			"/followers/ids": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
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

		$this->object = new Friends($this->options, $this->client, $this->oauth);
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
	 * Tests the getFriendIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testGetFriendIds($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFriendIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/friends/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendIds($user, $cursor, $string_ids, $count),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendIds method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 * @expectedException  DomainException
	 */
	public function testGetFriendIdsFailure($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFriendIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/friends/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendIds($user, $cursor, $string_ids, $count);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
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
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetFriendshipDetails($user_a, $user_b)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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

		$path = $this->object->fetchUrl('/friendships/show.json', $data);

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
	 * @dataProvider seedFriendshipDetails
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipDetailsFailure($user_a, $user_b)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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

		$path = $this->object->fetchUrl('/friendships/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipDetails($user_a, $user_b);
	}

	/**
	 * Tests the getFollowerIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testGetFollowerIds($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFollowerIds($user, $cursor, $string_ids, $count),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFollowerIds method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 * @expectedException  DomainException
	 */
	public function testGetFollowerIdsFailure($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
	}

	/**
	 * Tests the getFriendshipsIncoming method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetFriendshipsIncoming()
	{
		$cursor = 1234;
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/incoming.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsIncoming($cursor, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsIncoming method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsIncomingFailure()
	{
		$cursor = 1243;
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/incoming.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsIncoming($cursor, $string_ids);
	}

	/**
	 * Tests the getFriendshipsOutgoing method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetFriendshipsOutgoing()
	{
		$cursor = 12344;
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/outgoing.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsOutgoing($cursor, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsOutgoing method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsOutgoingFailure()
	{
		$cursor = 1234;
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/outgoing.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsOutgoing($cursor, $string_ids);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
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
	 * @since   1.0
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
			$this->object->follow($user, $follow);
		}
		$data['follow'] = $follow;

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->follow($user, $follow),
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
	 * @since   1.0
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
			$this->object->follow($user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->object->follow($user);
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
	 * @since   1.0
	 */
	public function testUnfollow($user)
	{
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
			$this->object->unfollow($user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->unfollow($user),
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
	 * @since   1.0
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
			$this->object->unfollow($user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->object->unfollow($user);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
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
	 * @return  void
	 *
	 * @since 1.0
	 * @dataProvider seedFriendshipsLookup
	 */
	public function testGetFriendshipsLookup($screen_name, $id)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFriendshipsLookup($screen_name, $id);
		}

		$path = $this->oauth->toUrl('/friendships/lookup.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipsLookup($screen_name, $id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipsLookup method - failure
	 *
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   string  $id           A comma separated list of user IDs, up to 100 are allowed in a single request.
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @dataProvider seedFriendshipsLookup
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipsLookupFailure($screen_name, $id)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
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
			$this->object->getFriendshipsLookup($screen_name, $id);
		}

		$path = $this->oauth->toUrl('/friendships/lookup.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipsLookup($screen_name, $id);
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
	 * @since   1.0
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
			$this->object->updateFriendship($user, $device, $retweets);
		}

		$data['device'] = $device;
		$data['retweets'] = $retweets;

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/update.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateFriendship($user, $device, $retweets),
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
	 * @since   1.0
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
			$this->object->updateFriendship($user);
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/friendships/update.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateFriendship($user);
	}

	/**
	 * Tests the getFriendshipNoRetweetIds method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetFriendshipNoRetweetIds()
	{
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/no_retweets/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendshipNoRetweetIds($string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendshipNoRetweetIds method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetFriendshipNoRetweetIdsFailure()
	{
		$string_ids = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->friendshipsRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "friendships"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/friendships/no_retweets/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFriendshipNoRetweetIds($string_ids);
	}
}
