<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Zen;
use Joomla\Registry\Registry;

/**
 * Test class for the Zen package.
 *
 * @since  1.0
 */
class ZenTest extends \PHPUnit_Framework_TestCase
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
	 * @var Zen
	 */
	protected $object;

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

		$this->object = new Zen($this->options, $this->client);
	}

	/**
	 * Tests the Get method.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = 'My Zen';

		$this->client->expects($this->once())
			->method('get')
			->with('/zen', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the Get method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetFailure()
	{
		$this->response->code = 400;
		$this->response->body = 'My Zen';

		$this->client->expects($this->once())
			->method('get')
			->with('/zen', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(),
			$this->equalTo($this->response->body)
		);
	}
}
