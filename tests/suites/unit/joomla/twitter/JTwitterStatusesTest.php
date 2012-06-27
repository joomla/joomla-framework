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
* @since       12.1
*/
class JTwitterStatusesTest extends TestCase
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
	 * @var    JTwitterStatuses  Object under test.
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
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $rateLimit = '{"remaining_hits":150, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"error": "Generic Error."}';

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
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	protected function getMethod($name)
	{
		$class = new ReflectionClass('JTwitterStatuses');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * Tests the getRetweetedByUser method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetedByUser()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/retweeted_by_user.json?screen_name=joomla&count=20')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedByUser('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedByUser method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
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
	 * @since   12.1
	 */
	public function testGetTweetById()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/show.json?id=123456789')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTweetById('123456789'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTweetById method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testGetTweetByIdFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/show.json?id=123456789')
		->will($this->returnValue($returnData));

		$this->object->getTweetById('123456789');
	}

	/**
	 * Tests the getUserTimeline method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetUserTimeline()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		/*$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json');
		->will($this->returnValue($returnData));*/

		$this->client->expects($this->at(1))
		->method('get')
		->with('/1/statuses/user_timeline.json?screen_name=joomla&count=20&include_rts=true')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getUserTimeline('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getUserTimeline method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testGetUserTimelineFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/user_timeline.json?screen_name=joomla&count=20&include_rts=true')
		->will($this->returnValue($returnData));

		$this->object->getUserTimeline('joomla');
	}

	/**
	 * Tests the tweet method
	 *
	 * @return  void
	 *
	 * @since   12.1
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
	 * @since   12.1
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
	 * @since   12.1
	 */
	public function testGetMentions()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getMentions method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetMentionsFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetedToUser method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetedToUser()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetedToUser method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedToUserFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetsOfMe method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetsOfMe()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetsOfMe method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetRetweetsOfMeFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetedBy method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetedBy()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');
		$id = '217781292748652545';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/' . $id . '/retweeted_by.json?count=20')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRetweetedBy($id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRetweetedBy method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedByFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');
		$id = '217781292748652545';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/statuses/' . $id . '/retweeted_by.json?count=20')
		->will($this->returnValue($returnData));

		$this->object->getRetweetedBy($id);
	}

	/**
	 * Tests the getRetweetedByIds method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetedByIds()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweetedByIds method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetRetweetedByIdsFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweets method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetRetweetsById()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getRetweets method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetRetweetsByIdFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the deleteTweet method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteTweet()
	{
		$id = '1234329764382109394';
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
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testDeleteTweetFailure()
	{
		$id = '1234329764382109394';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post')
			->with('/1/statuses/destroy/' . $id . '.json', null)
			->will($this->returnValue($returnData));

		$this->object->deleteTweet($this->oauth, $id);
	}

	/**
	 * Tests the retweet method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testRetweet()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the retweets method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testRetweetFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the tweetWithMedia method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testTweetWithMedia()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the tweetWithMedia method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testTweetWithMediaFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getOembed method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetOembed()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}

	/**
	 * Tests the getOembed method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * 
	 * @expectedException  DomainException
	 */
	public function testGetOembedFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test is not implemented.');
	}
}
