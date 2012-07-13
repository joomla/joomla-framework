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
require_once JPATH_PLATFORM . '/joomla/twitter/statuses.php';
require_once JPATH_PLATFORM . '/joomla/twitter/oauth.php';

/**
* Test class for JTwitterStatuses.
*
* @package     Joomla.UnitTest
* @subpackage  Twitter
*
* @since       12.3
*/
class JTwitterStatusesTest extends TestCase
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
	 * @var    JTwitterStatuses  Object under test.
	 * @since  12.3
	 */
	protected $object;

	/**
	 * @var    JTwitterOAuth  Authentication object for the Twitter object.
	 * @since  12.3
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $rateLimit = '{"remaining_hits":150, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON Twitter error message.
	 * @since  12.3
	 */
	protected $twitterErrorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

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

		$this->object = new JTwitterStatuses($this->options, $this->client);

		$this->options->set('consumer_key', $key);
		$this->options->set('consumer_secret', $secret);
		$this->options->set('callback', $my_url);
		$this->options->set('sendheaders', true);
		$this->oauth = new JTwitterOAuth($this->options, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	/**
	 * Tests the getRetweetedByUser method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetRetweetedByUser()
	{
		$user = 'testUser';
		$since_id = 1000;
		$count = 10;
		$entities = true;
		$max_id = 345354;
		$page = 1;
		$trim_user = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['screen_name'] = $user;
		$data['since_id'] = $since_id;
		$data['count'] = $count;
		$data['max_id'] = $max_id;
		$data['page'] = $page;
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/statuses/retweeted_by_user.json', $data);

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedByUser($user, $since_id, $count, $entities, $max_id, $page, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedByUser method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedByUserFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/retweeted_by_user.json?screen_name=joomla&count=20')
		->will($this->returnValue($returnData));

		$this->object->getRetweetedByUser('joomla');
	}

	/**
	 * Tests the getTweetById method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetTweetById()
	{
		$id = 12324354;
		$trim_user = true;
		$entities = true;
		$my_retweet = true;

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
		$data = array();
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;
		$data['include_my_retweet'] = $my_retweet;

		$path = $this->object->fetchUrl('/1/statuses/show/' . $id . '.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTweetById($id, $trim_user, $entities, $my_retweet),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTweetById method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetTweetByIdFailure()
	{
		$id = 12324354;

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

		$path = $this->object->fetchUrl('/1/statuses/show/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getTweetById($id);
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
	 * Tests the getUserTimeline method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 *
	 * @since   12.3
	 */
	public function testGetUserTimeline($user)
	{
		$count = 10;
		$include_rts = true;
		$entities = true;
		$no_replies = true;
		$since_id = 10;
		$max_id = 10;
		$page = 10;
		$trim_user = true;

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
			$this->object->getUserTimeline($user, $count, $include_rts, $entities, $no_replies, $since_id, $max_id, $page, $trim_user);
		}

		$data['count'] = $count;
		$data['include_rts'] = $include_rts;
		$data['include_entities'] = $entities;
		$data['exclude_replies'] = $no_replies;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['page'] = $page;
		$data['trim_user'] = $trim_user;

		$path = $this->object->fetchUrl('/1/statuses/user_timeline.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getUserTimeline($user, $count, $include_rts, $entities, $no_replies, $since_id, $max_id, $page, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getUserTimeline method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @dataProvider  seedUser
	 * @expectedException  DomainException
	 */
	public function testGetUserTimelineFailure($user)
	{
		$count = 10;
		$include_rts = true;

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
			$this->object->getUserTimeline($user, $count, $include_rts);
		}

		$data['count'] = $count;
		$data['include_rts'] = $include_rts;

		$path = $this->object->fetchUrl('/1/statuses/user_timeline.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getUserTimeline($user, $count, $include_rts);
	}

	/**
	 * Tests the tweet method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testTweet()
	{
		$status = 'This is a status';
		$in_reply_to_status_id = 1336421235;
		$lat = 42.53;
		$long = 45.21;
		$place_id = '23455ER235V';
		$display_coordinates = true;
		$trim_user = true;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data = array();
		$data['status'] = utf8_encode($status);
		$data['in_reply_to_status_id'] = $in_reply_to_status_id;
		$data['lat'] = $lat;
		$data['long'] = $long;
		$data['place_id'] = $place_id;
		$data['display_coordinates'] = $display_coordinates;
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/statuses/update.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
		$this->object->tweet($this->oauth, $status, $in_reply_to_status_id, $lat, $long, $place_id, $display_coordinates, $trim_user, $entities),
		$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the tweet method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testTweetFailure()
	{
		$status = 'This is a status';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data = array();
		$data['status'] = utf8_encode($status);

		$this->client->expects($this->once())
			->method('post')
			->with('/1/statuses/update.json', $data)
			->will($this->returnValue($returnData));

		$this->object->tweet($this->oauth, $status);
	}

	/**
	 * Tests the getMentions method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetMentions()
	{
		$count = 10;
		$include_rts = true;
		$entities = true;
		$since_id = 10;
		$max_id = 10;
		$page = 10;
		$trim_user = true;
		$contributor = true;

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
		$data = array();
		$data['count'] = $count;
		$data['include_rts'] = $include_rts;
		$data['include_entities'] = $entities;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['page'] = $page;
		$data['trim_user'] = $trim_user;
		$data['contributor_details'] = $contributor;

		$path = $this->object->fetchUrl('/1/statuses/mentions.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMentions($this->oauth, $count, $include_rts, $entities, $since_id, $max_id, $page, $trim_user, $contributor),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMentions method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetMentionsFailure()
	{
		$count = 10;
		$include_rts = true;

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
		$data = array();
		$data['count'] = $count;
		$data['include_rts'] = $include_rts;

		$path = $this->object->fetchUrl('/1/statuses/mentions.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getMentions($this->oauth, $count, $include_rts);
	}

	/**
	 * Tests the getRetweetedToUser method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   12.3
	 */
	public function testGetRetweetedToUser($user)
	{
		$since_id = 10;
		$count = 10;
		$entities = true;
		$max_id = 10;
		$page = 10;
		$trim_user = true;

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
			$this->object->getRetweetedToUser($user, $count, $since_id, $entities, $max_id, $page, $trim_user);
		}

		$data['count'] = $count;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['page'] = $page;
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/statuses/retweeted_to_user.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedToUser($user, $count, $since_id, $entities, $max_id, $page, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedToUser method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @dataProvider  seedUser
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedToUserFailure($user)
	{
		$count = 10;

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
			$this->object->getRetweetedToUser($user, $count);
		}

		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/statuses/retweeted_to_user.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRetweetedToUser($user, $count);
	}

	/**
	 * Tests the getRetweetsOfMe method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetRetweetsOfMe()
	{
		$since_id = 10;
		$count = 10;
		$entities = true;
		$max_id = 10;
		$page = 10;
		$trim_user = true;

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
		$data['count'] = $count;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['page'] = $page;
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/1/statuses/retweets_of_me.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetsOfMe($this->oauth, $count, $since_id, $entities, $max_id, $page, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetsOfMe method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetsOfMeFailure()
	{
		$count = 10;

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
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/statuses/retweets_of_me.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRetweetsOfMe($this->oauth, $count);
	}

	/**
	 * Tests the getRetweetedBy method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetRetweetedBy()
	{
		$id = 217781292748652545;
		$count = 5;
		$page = 2;

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
		$data['count'] = $count;
		$data['page'] = $page;

		$path = $this->object->fetchUrl('/1/statuses/' . $id . '/retweeted_by.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedBy($id, $count, $page),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedBy method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedByFailure()
	{
		$id = 217781292748652545;
		$count = 5;
		$page = 2;

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
		$data['count'] = $count;
		$data['page'] = $page;

		$path = $this->object->fetchUrl('/1/statuses/' . $id . '/retweeted_by.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRetweetedBy($id, $count, $page);
	}

	/**
	 * Tests the getRetweetedByIds method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetRetweetedByIds()
	{
		$id = 217781292748652545;
		$count = 5;
		$page = 2;
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
		$data['count'] = $count;
		$data['page'] = $page;
		$data['stringify_ids'] = $string_ids;

		$path = $this->object->fetchUrl('/1/statuses/' . $id . '/retweeted_by.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedByIds($this->oauth, $id, $count, $page, $string_ids),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedByIds method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedByIdsFailure()
	{
		$id = 217781292748652545;
		$count = 5;

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
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/statuses/' . $id . '/retweeted_by.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRetweetedByIds($this->oauth, $id, $count);
	}

	/**
	 * Tests the getRetweets method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetRetweetsById()
	{
		$id = 217781292748652545;
		$count = 5;
		$entities = true;
		$trim_user = true;

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
		$data['count'] = $count;
		$data['include_entities'] = $entities;
		$data['trim_user'] = $trim_user;

		$path = $this->object->fetchUrl('/1/statuses/retweets/' . $id . '.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetsById($this->oauth, $id, $count, $entities, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweets method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetsByIdFailure()
	{
		$id = 217781292748652545;
		$count = 5;

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
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/statuses/retweets/' . $id . '.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRetweetsById($this->oauth, $id, $count);
	}

	/**
	 * Tests the deleteTweet method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testDeleteTweet()
	{
		$id = 1234329764382109394;
		$trim_user = true;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data = array();
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/statuses/destroy/' . $id . '.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
		$this->object->deleteTweet($this->oauth, $id, $trim_user, $entities),
		$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteTweet method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testDeleteTweetFailure()
	{
		$id = 1234329764389394;
		$trim_user = true;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data = array();
		$data['trim_user'] = $trim_user;
		$data['include_entities'] = $entities;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/statuses/destroy/' . $id . '.json', $data)
			->will($this->returnValue($returnData));

		$this->object->deleteTweet($this->oauth, $id, $trim_user, $entities);
	}

	/**
	 * Tests the retweet method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testRetweet()
	{
		$id = 217781292748652545;
		$entities = true;
		$trim_user = true;

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
		$data['include_entities'] = $entities;
		$data['trim_user'] = $trim_user;

		$path = $this->object->fetchUrl('/1/statuses/retweet/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->retweet($this->oauth, $id, $entities, $trim_user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the retweets method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testRetweetFailure()
	{
		$id = 217781292748652545;
		$entities = true;
		$trim_user = true;

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
		$data['include_entities'] = $entities;
		$data['trim_user'] = $trim_user;

		$path = $this->object->fetchUrl('/1/statuses/retweet/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->retweet($this->oauth, $id, $entities, $trim_user);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedTweetWithMedia()
	{
		// User ID or screen name
		return array(
			array(array("X-MediaRateLimit-Remaining" => 10)),
			array(array("X-MediaRateLimit-Remaining" => 0, "X-MediaRateLimit-Reset" => 1243245654))
			);
	}

	/**
	 * Tests the tweetWithMedia method
	 *
	 * @param   string  $header  The JSON encoded header.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @dataProvider seedTweetWithMedia
	 */
	public function testTweetWithMedia($header)
	{
		$status = 'This is a status';
		$media = 'path/to/source';
		$in_reply_to_status_id = 1336421235;
		$lat = 42.53;
		$long = 45.21;
		$place_id = '23455ER235V';
		$display_coordinates = true;
		$sensitive = true;

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
		$returnData->headers = $header;

		// Set POST request parameters.
		$data = array();
		$data['media[]'] = "@{$media}";
		$data['status'] = utf8_encode($status);
		$data['in_reply_to_status_id'] = $in_reply_to_status_id;
		$data['lat'] = $lat;
		$data['long'] = $long;
		$data['place_id'] = $place_id;
		$data['display_coordinates'] = $display_coordinates;
		$data['possibly_sensitive'] = $sensitive;

		$this->client->expects($this->at(1))
			->method('post')
			->with('https://upload.twitter.com/1/statuses/update_with_media.json', $data)
			->will($this->returnValue($returnData));

		$headers_array = $returnData->headers;
		if ($headers_array['X-MediaRateLimit-Remaining'] == 0)
		{
			$this->setExpectedException('RuntimeException');
		}

		$this->assertThat(
			$this->object->tweetWithMedia($this->oauth, $status, $media, $in_reply_to_status_id, $lat, $long, $place_id, $display_coordinates, $sensitive),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the tweetWithMedia method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testTweetWithMediaFailure()
	{
		$status = 'This is a status';
		$media = 'path/to/source';

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

		// Set POST request parameters.
		$data = array();
		$data['media[]'] = "@{$media}";
		$data['status'] = utf8_encode($status);

		$this->client->expects($this->at(1))
			->method('post')
			->with('https://upload.twitter.com/1/statuses/update_with_media.json', $data)
			->will($this->returnValue($returnData));

		$this->object->tweetWithMedia($this->oauth, $status, $media);
	}

	/**
	 * Tests the getOembed method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetOembed()
	{
		$id = 217781292748652545;
		$maxwidth = 300;
		$hide_media = true;
		$hide_thread = true;
		$omit_script = true;
		$align = 'center';
		$related = 'twitter';
		$lang = 'fr';

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
		$data['id'] = $id;
		$data['maxwidth'] = $maxwidth;
		$data['hide_media'] = $hide_media;
		$data['hide_thread'] = $hide_thread;
		$data['omit_script'] = $omit_script;
		$data['align'] = $align;
		$data['related'] = $related;
		$data['lang'] = $lang;

		$path = $this->object->fetchUrl('/1/statuses/oembed.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getOembed($id, null, $maxwidth, $hide_media, $hide_thread, $omit_script, $align, $related, $lang),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedGetoembed()
	{
		// URL
		return array(
			array('https://twitter.com/twitter/status/99530515043983360'),
			array(null)
			);
	}

	/**
	 * Tests the getOembed method - failure
	 *
	 * @param   mixed  $url  The URL string or null.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @dataProvider seedGetOembed
	 */
	public function testGetOembedFailure($url)
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

		if ($url)
		{
			// Set request parameters.
			$data['url'] = rawurlencode($url);
			$this->setExpectedException('DomainException');

			$path = $this->object->fetchUrl('/1/statuses/oembed.json', $data);

			$this->client->expects($this->at(1))
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

			$this->object->getOembed(null, $url);
		}
		else
		{
			$data = array();
			$this->setExpectedException('RuntimeException');

			$this->object->getOembed(null, null);
		}
	}
}
