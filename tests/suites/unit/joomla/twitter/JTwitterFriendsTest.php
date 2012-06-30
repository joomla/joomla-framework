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
 * @since       12.1
 */
class JTwitterFriendsTest extends TestCase
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
	 * @var    JTwitterFriends  Object under test.
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
	protected $errorString = '{"error": "Generic Error"}';

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

		$this->object = new JTwitterFriends($this->options, $this->client);
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	protected function getMethod($name)
	{
		$class = new ReflectionClass('JTwitterFriends');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * Tests the getFriendIds method
	 * 
	 * @covers JTwitterFriends::getFriendIds
	 * 
	 * @todo   Implement testGetFriendIds().
	 * 
	 * @return  void
	 */
	public function testGetFriendIds()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getFriendshipDetails method
	 * 
	 * @covers JTwitterFriends::getFriendshipDetails
	 * 
	 * @todo   Implement testGetFriendshipDetails().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipDetails()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getFriendshipExists method
	 * 
	 * @covers JTwitterFriends::getFriendshipExists
	 * 
	 * @todo   Implement testGetFriendshipExists().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipExists()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getFollowerIds method
	 * 
	 * @covers JTwitterFriends::getFollowerIds
	 * 
	 * @todo   Implement testGetFollowerIds().
	 * 
	 * @return  void
	 */
	public function testGetFollowerIds()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getFriendshipsIncoming method
	 * 
	 * @covers JTwitterFriends::getFriendshipsIncoming
	 * 
	 * @todo   Implement testGetFriendshipsIncoming().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipsIncoming()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getFriendshipsOutgoing method
	 * 
	 * @covers JTwitterFriends::getFriendshipsOutgoing
	 * 
	 * @todo   Implement testGetFriendshipsOutgoing().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipsOutgoing()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.1
	*/
	public function seedFriendship()
	{
		// User ID or screen name
		return array(
			array('234654235457'),
			array('testUser')
			);
	}

	/**
	 * Tests the createFriendship method
	 * 
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 * 
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.1
	 */
	public function testCreateFriendship($user)
	{
		$follow = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
		}
		$data['follow'] = $follow;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createFriendship($this->oauth, $user, $follow),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createFriendship method - failure
	 * 
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 * 
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testCreateFriendshipFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/create.json', $data)
			->will($this->returnValue($returnData));

		$this->object->createFriendship($this->oauth, $user);
	}

	/**
	 * Tests the deleteFriendship method
	 * 
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 * 
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.1
	 */
	public function testDeleteFriendship($user)
	{
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
		}
		$data['include_entities'] = $entities;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteFriendship($this->oauth, $user, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteFriendship method - failure
	 * 
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 * 
	 * @dataProvider  seedFriendship
	 *
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testDeleteFriendshipFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
		}

		$this->client->expects($this->once())
			->method('post')
			->with('/1/friendships/destroy.json', $data)
			->will($this->returnValue($returnData));

		$this->object->deleteFriendship($this->oauth, $user);
	}

	/**
	 * Tests the getFriendshipsLookup method
	 * 
	 * @covers JTwitterFriends::getFriendshipsLookup
	 * 
	 * @todo   Implement testGetFriendshipsLookup().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipsLookup()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
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
	 * @since   12.1
	 */
	public function testUpdateFriendship($user)
	{
		$device = true;
		$retweets = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
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
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testUpdateFriendshipFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data = array();
		if (is_integer($user))
		{
			$data['user_id'] = $user;
		}
		else
		{
			$data['screen_name'] = $user;
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
	 * @todo   Implement testGetFriendshipNoRetweetIds().
	 * 
	 * @return  void
	 */
	public function testGetFriendshipNoRetweetIds()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
