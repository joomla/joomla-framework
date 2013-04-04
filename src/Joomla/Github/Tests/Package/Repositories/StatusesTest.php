<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories\Statuses;
use Joomla\Registry\Registry;

/**
 * Test class for Statuses.
 *
 * @since  1.0
 */
class StatusesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Registry\Registry  Options for the GitHub object.
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
	 * @var    Statuses  Object under test.
	 * @since  11.4
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  11.4
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  11.4
	 */
	protected $errorString = '{"message": "Generic Error"}';

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

		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Statuses($this->options, $this->client);
	}

	/**
	 * Tests the create method
	 *
	 * @return void
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		// Build the request data.
		$data = json_encode(
			array(
				'state' => 'success',
				'target_url' => 'http://example.com/my_url',
				'description' => 'Success is the only option - failure is not.'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/statuses/6dcb09b5b57875f334f61aebed695e2e4193db5e', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create(
				'joomla',
				'joomla-platform',
				'6dcb09b5b57875f334f61aebed695e2e4193db5e',
				'success',
				'http://example.com/my_url',
				'Success is the only option - failure is not.'
			),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 */
	public function testCreateFailure()
	{
		$this->response->code = 501;
		$this->response->body = $this->errorString;

		// Build the request data.
		$data = json_encode(
			array(
				'state' => 'pending'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/statuses/6dcb09b5b57875f334f61aebed695e2e4193db5e', $data)
			->will($this->returnValue($this->response));

		$this->object->create('joomla', 'joomla-platform', '6dcb09b5b57875f334f61aebed695e2e4193db5e', 'pending');
	}

	/**
	 * Tests the create method - failure
	 *
	 * @expectedException  \InvalidArgumentException
	 *
	 * @return void
	 */
	public function testCreateInvalidState()
	{
		$this->response->code = 501;
		$this->response->body = $this->errorString;

		$this->object->create('joomla', 'joomla-platform', '6dcb09b5b57875f334f61aebed695e2e4193db5e', 'INVALID');
	}

	/**
	 * Tests the getList method
	 *
	 * @return void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/statuses/6dcb09b5b57875f334f61aebed695e2e4193db5e')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform', '6dcb09b5b57875f334f61aebed695e2e4193db5e'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getList method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 */
	public function testGetListFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/statuses/6dcb09b5b57875f334f61aebed695e2e4193db5e')
			->will($this->returnValue($this->response));

		$this->object->getList('joomla', 'joomla-platform', '6dcb09b5b57875f334f61aebed695e2e4193db5e');
	}
}
