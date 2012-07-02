<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/object.php';
require_once JPATH_PLATFORM . '/joomla/twitter/http.php';
require_once __DIR__ . '/stubs/JTwitterObjectMock.php';

/**
 * Test class for JTwitterObject.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.1
 */
class JTwitterObjectTest extends TestCase
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
	 * @var    JTwitterObjectMock  Object under test.
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
	protected $errorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

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

		$this->object = new JTwitterObjectMock($this->options, $this->client);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
	}

	/**
	 * @covers JTwitterObject::checkRateLimit
	 * @todo   Implement testCheckRateLimit().
	 */
	public function testCheckRateLimit()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @covers JTwitterObject::fetchUrl
	 * @todo   Implement testFetchUrl().
	 */
	public function testFetchUrl()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the getRateLimit method
	 *
	 * @return  void
	 *
	 * @since   12.1
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
	 * @since   12.1
	 *
	 * @expectedException  DomainException
	 */
	public function testGetRateLimitFailure()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test currently fails.');

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
	 * @covers JTwitterObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testSendRequest()
	{
		// Method tested via requesting classes
		$this->markTestSkipped('This method is tested via requesting classes.');
	}
}
