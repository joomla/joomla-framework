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
require_once JPATH_PLATFORM . '/joomla/twitter/trends.php';

/**
 * Test class for JTwitterTrends.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.3
 */
class JTwitterTrendsTest extends TestCase
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
	 * @var    JTwitterPlaces  Object under test.
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
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterTrends($this->options, $this->client);

		$this->options->set('consumer_key', $key);
		$this->options->set('consumer_secret', $secret);
		$this->options->set('callback', $my_url);
		$this->options->set('sendheaders', true);
		$this->oauth = new JTwitterOAuth($this->options, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	/**
	 * Tests the getTrends method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetTrends()
	{
		$woeid = '1a2b3c4d';
		$exclude = 'hashtags';

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

		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/' . $woeid . '.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTrends($woeid, $exclude),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTrends method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @expectedException DomainException
	 */
	public function testGetTrendsFailure()
	{
		$woeid = '1a2b3c4d';
		$exclude = 'hashtags';

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

		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/' . $woeid . '.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getTrends($woeid, $exclude);
	}

	/**
	 * Tests the getLocations method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetLocations()
	{
		$lat = 45;
		$long = 45;

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

		$data['lat'] = $lat;
		$data['long'] = $long;

		$path = $this->object->fetchUrl('/1/trends/available.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLocations($lat, $long),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLocations method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @expectedException DomainException
	 */
	public function testGetLocationsFailure()
	{
		$lat = 45;
		$long = 45;

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

		$data['lat'] = $lat;
		$data['long'] = $long;

		$path = $this->object->fetchUrl('/1/trends/available.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getLocations($lat, $long);
	}

	/**
	 * Tests the getDailyTrends method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetDailyTrends()
	{
		$date = '2010-06-20';
		$exclude = 'hashtags';

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

		$data['date'] = $date;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/daily.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getDailyTrends($date, $exclude),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDailyTrends method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @expectedException DomainException
	 */
	public function testGetDailyTrendsFailure()
	{
		$date = '2010-06-20';
		$exclude = 'hashtags';

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

		$data['date'] = $date;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/daily.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getDailyTrends($date, $exclude);
	}

	/**
	 * Tests the getWeeklyTrends method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetWeeklyTrends()
	{
		$date = '2010-06-20';
		$exclude = 'hashtags';

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

		$data['date'] = $date;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/weekly.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getWeeklyTrends($date, $exclude),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getWeeklyTrends method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @expectedException DomainException
	 */
	public function testGetWeeklyTrendsFailure()
	{
		$date = '2010-06-20';
		$exclude = 'hashtags';

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

		$data['date'] = $date;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/1/trends/weekly.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getWeeklyTrends($date, $exclude);
	}
}
