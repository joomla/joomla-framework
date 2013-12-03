<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Test\TestHelper;
use Joomla\Twitter\Object;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';
require_once __DIR__ . '/stubs/ObjectMock.php';

/**
 * Test class for Twitter Object.
 *
 * @since  1.0
 */
class ObjectTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

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

		$this->object = new ObjectMock($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the checkRateLimit method
	 *
	 * @return void
	 *
	 * @since 1.0
	 * @expectedException \RuntimeException
	 */
	public function testCheckRateLimit()
	{
		$resource = 'statuses';
		$action = 'show';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = '{"resources":{"statuses":{"/statuses/show":{"remaining":0, "reset":"Mon Jun 25 17:20:53 +0000 2012"}}}}';

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array('resources' => $resource));

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->checkRateLimit($resource, $action);
	}

	/**
	 * Tests the fetchUrl method
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function testFetchUrl()
	{
		// Method tested via requesting classes
		$this->markTestSkipped('This method is tested via requesting classes.');
	}

	/**
	 * Tests the getRateLimit method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetRateLimit()
	{
		$resource = 'statuses';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array('resources' => $resource));

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRateLimit($resource),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRateLimit method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRateLimitFailure()
	{
		$resource = 'statuses';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array('resources' => $resource));

		$this->client->expects($this->once())
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getRateLimit($resource);
	}

	/**
	 * Tests the getSendRequest method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSendRequest()
	{
		// Method tested via requesting classes
		$this->markTestSkipped('This method is tested via requesting classes.');
	}

	/**
	 * Tests the setOption method
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$value = TestHelper::getValue($this->object, 'options');

		$this->assertThat(
			$value['api.url'],
			$this->equalTo('https://example.com/settest')
		);
	}

	/**
	 * Tests the getOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOption()
	{
		TestHelper::setValue(
			$this->object,
			'options',
			array(
				'api.url' => 'https://example.com/gettest'
			)
		);

		$this->assertThat(
			$this->object->getOption('api.url'),
			$this->equalTo('https://example.com/gettest')
		);
	}
}
