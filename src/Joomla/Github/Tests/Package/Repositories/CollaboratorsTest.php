<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories\Collaborators;
use Joomla\Registry\Registry;

/**
 * Test class for Collaborators.
 *
 * @since  1.0
 */
class CollaboratorsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  11.4
	 */
	protected $options;

	/**
	 * @var    \PHPUnit_Framework_MockObject_MockObject  Mock client object.
	 * @since  11.4
	 */
	protected $client;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  12.3
	 */
	protected $response;

	/**
	 * @var Collaborators
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

		$this->object = new Collaborators($this->options, $this->client);
	}

	/**
	 * Tests the GetList method.
	 *
	 * @return  void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/collaborators')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the Get method.
	 *
	 * @return  void
	 */
	public function testGet()
	{
		$this->response->code = 204;
		$this->response->body = true;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/collaborators/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-framework', 'elkuku'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the GetNegative method.
	 *
	 * @return  void
	 */
	public function testGetNegative()
	{
		$this->response->code = 404;
		$this->response->body = false;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/collaborators/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-framework', 'elkuku'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the GetUnexpected method.
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testGetUnexpected()
	{
		$this->response->code = 666;
		$this->response->body = null;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/collaborators/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-framework', 'elkuku'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the Add method.
	 *
	 * @return  void
	 */
	public function testAdd()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put')
			->with('/repos/joomla/joomla-framework/collaborators/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->add('joomla', 'joomla-framework', 'elkuku'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the Remove method.
	 *
	 * @return  void
	 */
	public function testRemove()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-framework/collaborators/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->remove('joomla', 'joomla-framework', 'elkuku'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
