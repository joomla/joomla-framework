<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests\Users;

use Joomla\Github\Package\Users\Keys;
use Joomla\Registry\Registry;

/**
 * Test class for Keys.
 *
 * @since  1.0
 */
class KeysTest extends \PHPUnit_Framework_TestCase
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
	 * @var Keys
	 */
	protected $object;

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

		$this->object = new Keys($this->options, $this->client);
	}

	/**
	 * Tests the getListUser method
	 *
	 * @return  void
	 */
	public function testGetListUser()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/users/joomla/keys')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListUser('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
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
			->with('/users/keys')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList(),
			$this->equalTo(json_decode($this->sampleString))
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
			->with('/users/keys/1')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(1),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method
	 *
	 * @return  void
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/users/keys')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('email@example.com', '12345'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the edit method
	 *
	 * @return  void
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('patch')
			->with('/users/keys/1')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(1, 'email@example.com', '12345'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the delete method
	 *
	 * @return  void
	 */
	public function testDelete()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/users/keys/1')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete(1),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
