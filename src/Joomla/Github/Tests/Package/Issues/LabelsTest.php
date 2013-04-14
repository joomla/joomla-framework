<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Issues\Labels;
use Joomla\Registry\Registry;

/**
 * Test class for the GitHub API package.
 *
 * @since  1.0
 */
class LabelsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Labels  Object under test.
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

		$this->object = new Labels($this->options, $this->client);
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
			->with('/repos/joomla/joomla-platform/labels', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform', '1'),
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
			->with('/repos/joomla/joomla-platform/labels/1', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', '1'),
			$this->equalTo(json_decode($this->response->body))
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
			->with('/repos/joomla/joomla-platform/labels', '{"name":"foobar","color":"red"}', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 'foobar', 'red'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the createFailure method
	 *
	 * @expectedException \DomainException
	 * @return  void
	 */
	public function testCreateFailure()
	{
		$this->response->code = 404;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/labels', '{"name":"foobar","color":"red"}', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 'foobar', 'red'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the update method
	 *
	 * @return  void
	 */
	public function testUpdate()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/labels/foobar', '{"name":"boofaz","color":"red"}', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->update('joomla', 'joomla-platform', 'foobar', 'boofaz', 'red'),
			$this->equalTo(json_decode($this->response->body))
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
			->with('/repos/joomla/joomla-platform/labels/foobar', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete('joomla', 'joomla-platform', 'foobar'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getListByIssue method
	 *
	 * @return  void
	 */
	public function testGetListByIssue()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/1/labels', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListByIssue('joomla', 'joomla-platform', 1),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the add method
	 *
	 * @return  void
	 */
	public function testAdd()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/issues/1/labels', '["A","B"]', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->add('joomla', 'joomla-platform', 1, array('A', 'B')),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the removeFromIssue method
	 *
	 * @return  void
	 */
	public function testRemoveFromIssue()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/issues/1/labels/foobar', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->removeFromIssue('joomla', 'joomla-platform', 1, 'foobar'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the replace method
	 *
	 * @return  void
	 */
	public function testReplace()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put')
			->with('/repos/joomla/joomla-platform/issues/1/labels', '["A","B"]', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->replace('joomla', 'joomla-platform', 1, array('A', 'B')),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the removeAllFromIssue method
	 *
	 * @return  void
	 */
	public function testRemoveAllFromIssue()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/issues/1/labels', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->removeAllFromIssue('joomla', 'joomla-platform', 1),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getListByMilestone method
	 *
	 * @return  void
	 */
	public function testGetListByMilestone()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/milestones/1/labels', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListByMilestone('joomla', 'joomla-platform', 1),
			$this->equalTo(json_decode($this->response->body))
		);
	}
}
