<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Trends;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for witter Trends.
 *
 * @since  1.0
 */
class TrendsTest extends TwitterTestCase
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
	protected $rateLimit = '{"resources": {"trends": {
			"/trends/place": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/trends/available": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/trends/closest": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
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

		$this->object = new Trends($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getTrends method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetTrends()
	{
		$id = '1a2b3c4d';
		$exclude = 'hashtags';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['id'] = $id;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/trends/place.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTrends($id, $exclude),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTrends method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetTrendsFailure()
	{
		$id = '1a2b3c4d';
		$exclude = 'hashtags';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['id'] = $id;
		$data['exclude'] = $exclude;

		$path = $this->object->fetchUrl('/trends/place.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getTrends($id, $exclude);
	}

	/**
	 * Tests the getLocations method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetLocations()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/trends/available.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLocations(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLocations method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetLocationsFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/trends/available.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getLocations();
	}

	/**
	 * Tests the getClosest method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetClosest()
	{
		$lat = 45;
		$long = 45;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['lat'] = $lat;
		$data['long'] = $long;

		$path = $this->object->fetchUrl('/trends/closest.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
				$this->object->getClosest($lat, $long),
				$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getClosest method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetClosestFailure()
	{
		$lat = 45;
		$long = 45;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "trends"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['lat'] = $lat;
		$data['long'] = $long;

		$path = $this->object->fetchUrl('/trends/closest.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getClosest($lat, $long);
	}
}
