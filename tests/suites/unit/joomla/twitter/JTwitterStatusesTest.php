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

/**
 * Test class for JTwitterStatuses.
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
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"message": "Generic Error"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterStatuses($this->options, $this->client);
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
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

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
		$this->markTestIncomplete('This test currently fails due to checkRateLimit.');

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->at(2))
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
}
