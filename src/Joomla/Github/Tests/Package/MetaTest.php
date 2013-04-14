<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Meta;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\Github\Meta.
 *
 * @since  1.0
 */
class MetaTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \PHPUnit_Framework_MockObject_MockObject  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var    Meta  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $sampleString = '{"hooks":["127.0.0.1/32","192.168.1.1/32","10.10.1.1/27"],"git":["127.0.0.1/32"]}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  1.0
	 */
	protected $errorString = '{"message": "Generic Error"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Meta($this->options, $this->client);
	}

	/**
	 * Tests the getMeta method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetMeta()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$decodedResponse = new \stdClass;
		$decodedResponse->hooks = array('127.0.0.1/32', '192.168.1.1/32', '10.10.1.1/27');
		$decodedResponse->git   = array('127.0.0.1/32');

		$this->client->expects($this->once())
			->method('get')
			->with('/meta')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getMeta(),
			$this->equalTo($decodedResponse)
		);
	}

	/**
	 * Tests the getMeta method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetMetaFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/meta')
			->will($this->returnValue($this->response));

		$this->object->getMeta();
	}
}
