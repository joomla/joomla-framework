<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Registry\Registry;
use Joomla\Github\Refs;

/**
 * Test class for Joomla\Github\Refs.
 *
 * @since  1.0
 */
class RefsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \Joomla\Github\Http  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Refs  Object under test.
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
	 * @since  1.0
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

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
		$this->client = $this->getMock('Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Refs($this->options, $this->client);
	}

	/**
	 * Tests the get method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/git/refs/heads/master')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 'heads/master'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the get method
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/git/refs/heads/master')
			->will($this->returnValue($this->response));

		$this->object->get('joomla', 'joomla-platform', 'heads/master');
	}

	/**
	 * Tests the create method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		// Build the request data.
		$data = json_encode(
			array(
				'ref' => '/ref/heads/myhead',
				'sha' => 'This is the sha'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/git/refs', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', '/ref/heads/myhead', 'This is the sha'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testCreateFailure()
	{
		$this->response->code = 501;
		$this->response->body = $this->errorString;

		// Build the request data.
		$data = json_encode(
			array(
				'ref' => '/ref/heads/myhead',
				'sha' => 'This is the sha'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/git/refs', $data)
			->will($this->returnValue($this->response));

		$this->object->create('joomla', 'joomla-platform', '/ref/heads/myhead', 'This is the sha');
	}

	/**
	 * Tests the edit method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		// Build the request data.
		$data = json_encode(
			array(
				'force' => true,
				'sha' => 'This is the sha'
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/git/refs/heads/master', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-platform', 'heads/master', 'This is the sha', true),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the edit method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testEditFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		// Build the request data.
		$data = json_encode(
			array(
				'sha' => 'This is the sha'
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/git/refs/heads/master', $data)
			->will($this->returnValue($this->response));

		$this->object->edit('joomla', 'joomla-platform', 'heads/master', 'This is the sha');
	}

	/**
	 * Tests the getList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/git/refs')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getList method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetListFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/git/refs')
			->will($this->returnValue($this->response));

		$this->object->getList('joomla', 'joomla-platform');
	}
}
