<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Commits;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\GitHub\Commits.
 *
 * @since  1.0
 */
class CommitsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var    Commits  Object under test.
	 * @since  1.0
	 */
	protected $object;

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
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Commits($this->options, $this->client);
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

		$pull = new \stdClass;
		$pull->message = 'My latest commit';
		$pull->tree = 'abc1234';
		$pull->parents = array('def5678');

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/git/commits', json_encode($pull))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 'My latest commit', 'abc1234', array('def5678')),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testCreateFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$pull = new \stdClass;
		$pull->message = 'My latest commit';
		$pull->tree = 'abc1234';
		$pull->parents = array('def5678');

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/git/commits', json_encode($pull))
			->will($this->returnValue($this->response));

		$this->object->create('joomla', 'joomla-platform', 'My latest commit', 'abc1234', array('def5678'));
	}

	/**
	 * Tests the createCommitComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateCommitComment()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		// The comment data
		$comment = new \stdClass;
		$comment->body = 'My Insightful Comment';
		$comment->commit_id = 'abc1234';
		$comment->line = 1;
		$comment->path = 'path/to/file';
		$comment->position = 254;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/commits/abc1234/comments', json_encode($comment))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->createCommitComment('joomla', 'joomla-platform', 'abc1234', 'My Insightful Comment', 1, 'path/to/file', 254),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createCommitComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testCreateCommitCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		// The comment data
		$comment = new \stdClass;
		$comment->body = 'My Insightful Comment';
		$comment->commit_id = 'abc1234';
		$comment->line = 1;
		$comment->path = 'path/to/file';
		$comment->position = 254;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/commits/abc1234/comments', json_encode($comment))
			->will($this->returnValue($this->response));

		$this->object->createCommitComment('joomla', 'joomla-platform', 'abc1234', 'My Insightful Comment', 1, 'path/to/file', 254);
	}

	/**
	 * Tests the deleteCommitComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteCommitComment()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/comments/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->deleteCommitComment('joomla', 'joomla-platform', 42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteCommitComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testDeleteCommitCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/comments/42')
			->will($this->returnValue($this->response));

		$this->object->deleteCommitComment('joomla', 'joomla-platform', 42);
	}

	/**
	 * Tests the editCommitComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditCommitComment()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		// The comment data
		$comment = new \stdClass;
		$comment->body = 'My Insightful Comment';

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/comments/42', json_encode($comment))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->editCommitComment('joomla', 'joomla-platform', 42, 'My Insightful Comment'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editCommitComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testEditCommitCommentFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		// The comment data
		$comment = new \stdClass;
		$comment->body = 'My Insightful Comment';

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/comments/42', json_encode($comment))
			->will($this->returnValue($this->response));

		$this->object->editCommitComment('joomla', 'joomla-platform', 42, 'My Insightful Comment');
	}

	/**
	 * Tests the getCommit method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCommit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/commits/abc1234')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCommit('joomla', 'joomla-platform', 'abc1234'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCommit method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetCommitFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/commits/abc1234')
			->will($this->returnValue($this->response));

		$this->object->getCommit('joomla', 'joomla-platform', 'abc1234');
	}

	/**
	 * Tests the getCommitComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCommitComment()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/comments/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCommitComment('joomla', 'joomla-platform', 42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCommitComment method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetCommitCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/comments/42')
			->will($this->returnValue($this->response));

		$this->object->getCommitComment('joomla', 'joomla-platform', 42);
	}

	/**
	 * Tests the getCommitComments method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCommitComments()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/commits/abc1234/comments')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCommitComments('joomla', 'joomla-platform', 'abc1234'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCommitComments method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetCommitCommentsFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/commits/abc1234/comments')
			->will($this->returnValue($this->response));

		$this->object->getCommitComments('joomla', 'joomla-platform', 'abc1234');
	}

	/**
	 * Tests the getDiff method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDiff()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/compare/master...staging')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getDiff('joomla', 'joomla-platform', 'master', 'staging'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDiff method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetDiffFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/compare/master...staging')
			->will($this->returnValue($this->response));

		$this->object->getDiff('joomla', 'joomla-platform', 'master', 'staging');
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
			->with('/repos/joomla/joomla-platform/commits')
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
			->with('/repos/joomla/joomla-platform/commits')
			->will($this->returnValue($this->response));

		$this->object->getList('joomla', 'joomla-platform');
	}

	/**
	 * Tests the getListComments method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetListComments()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/comments')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListComments('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListComments method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetListCommentsFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/comments')
			->will($this->returnValue($this->response));

		$this->object->getListComments('joomla', 'joomla-platform');
	}
}
