<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests\Issues;

use Joomla\Github\Package\Issues\Comments;
use Joomla\Registry\Registry;
use Joomla\Date\Date;

/**
 * Test class for the GitHub API package.
 *
 * @since  1.0
 */
class CommentsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Comments  Object under test.
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

		$this->object = new Comments($this->options, $this->client);
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
			->with('/repos/joomla/joomla-platform/issues/1/comments', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform', '1'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getRepositoryList method
	 *
	 * @return  void
	 */
	public function testGetRepositoryList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/comments?sort=created&direction=asc', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getRepositoryList('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getRepositoryListInvalidSort method
	 *
	 * @expectedException \UnexpectedValueException
	 * @return  void
	 */
	public function testGetRepositoryListInvalidSort()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->object->getRepositoryList('joomla', 'joomla-platform', 'invalid');
	}

	/**
	 * Tests the getRepositoryListInvalidDirection method
	 *
	 * @expectedException \UnexpectedValueException
	 * @return  void
	 */
	public function testGetRepositoryListInvalidDirection()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->object->getRepositoryList('joomla', 'joomla-platform', 'created', 'invalid');
	}

	/**
	 * Tests the getRepositoryListSince method
	 *
	 * @return  void
	 */
	public function testGetRepositoryListSince()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$date = new Date('1966-09-15 12:34:56');

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/issues/comments?sort=created&direction=asc&since=1966-09-15T12:34:56+00:00', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getRepositoryList('joomla', 'joomla-platform', 'created', 'asc', $date),
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
			->with('/repos/joomla/joomla-platform/issues/comments/1', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 1),
			$this->equalTo(json_decode($this->response->body))
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
			->with('/repos/joomla/joomla-platform/issues/comments/1', '{"body":"Hello"}', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-platform', 1, 'Hello'),
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
			->with('/repos/joomla/joomla-platform/issues/1/comments', '{"body":"Hello"}', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 1, 'Hello'),
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
		$this->response->body = '';

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/issues/comments/1', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete('joomla', 'joomla-platform', 1, 'Hello'),
			$this->equalTo(true)
		);
	}
}
