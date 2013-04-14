<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests\Issues;

use Joomla\Github\Package\Issues\Events;
use Joomla\Registry\Registry;

/**
 * Test class for the GitHub API package.
 *
 * @since  1.0
 */
class EventsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Events  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"message": "Generic Error"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @since   1.0
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options  = new Registry;
		$this->client   = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Events($this->options, $this->client);
	}

	/**
	 * Tests the getList method
	 *
	 * @return  void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/1/events', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform', '1'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getListRepository method
	 *
	 * @return  void
	 */
	public function testGetListRepository()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/1/comments', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListRepository('joomla', 'joomla-platform', '1'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the get method
	 *
	 * @return  void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/events/1', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', '1'),
			$this->equalTo(json_decode($this->response->body))
		);
	}
}
