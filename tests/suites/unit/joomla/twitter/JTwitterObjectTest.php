<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

include_once __DIR__ . '/stubs/JTwitterObjectMock.php';

/**
 * Test class for JTwitterObject.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.3
 */
class JTwitterObjectTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.3
	 */
	protected $options;

	/**
	 * @var    JHttp  Mock client object.
	 * @since  12.3
	 */
	protected $client;

	/**
	 * @var    JTwitterObjectMock  Object under test.
	 * @since  12.3
	 */
	protected $object;

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
		$this->options = new JRegistry;
		$this->client = $this->getMock('JHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterObjectMock($this->options, $this->client);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the checkRateLimit method
	 *
	 * @return void
	 *
	 * @since 12.3
	 * @expectedException RuntimeException
	 */
	public function testCheckRateLimit()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = '{"remaining_hits":0, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

		$this->client->expects($this->once())
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$this->object->checkRateLimit();
	}

	/**
	 * Tests the fetchUrl method
	 *
	 * @return void
	 *
	 * @since 12.3
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
	 * @since   12.3
	 */
	public function testGetRateLimit()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getRateLimit(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRateLimit method - failure
	 *
	 * @return  void
	 *
	 * @since   12.3
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRateLimitFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$this->object->getRateLimit();
	}

	/**
	 * Tests the getSendRequest method
	 *
	 * @return  void
	 *
	 * @since   12.3
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
	 * @since 12.3
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$this->assertThat(
			$this->options->get('api.url'),
			$this->equalTo('https://example.com/settest')
		);
	}
}
