<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Gists;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\Github\Gists.
 *
 * @since  1.0
 */
class GistsTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Gists  Object under test.
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

		$this->object = new Gists($this->options, $this->client);
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
				'files' => array(
					'file2.txt' => array('content' => 'This is the second file')
				),
				'public' => true,
				'description' => 'This is a gist'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/gists', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create(
				array(
					'file2.txt' => 'This is the second file'
				),
				true,
				'This is a gist'
			),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method loading file content from a file
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateGistFromFile()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		// Build the request data.
		$data = json_encode(
			array(
				'files' => array(
					'gittest' => array('content' => 'GistContent' . PHP_EOL)
				),
				'public' => true,
				'description' => 'This is a gist'
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/gists', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create(
				array(
					JPATH_BASE . '/gittest'
				),
				true,
				'This is a gist'
			),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method loading file content from a file - file does not exist
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  InvalidArgumentException
	 */
	public function testCreateGistFromFileNotFound()
	{
		$this->response->code = 501;
		$this->response->body = $this->sampleString;

		$this->object->create(
			array(
				JPATH_BASE . '/gittest_notfound'
			),
			true,
			'This is a gist'
		);
	}

	/**
	 * Tests the create method
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

		// Build the request data.
		$data = json_encode(
			array('files' => array(), 'public' => true, 'description' => 'This is a gist')
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/gists', $data)
			->will($this->returnValue($this->response));

		$this->object->create(array(), true, 'This is a gist');
	}

	/**
	 * Tests the createComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateComment()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$gist = new \stdClass;
		$gist->body = 'My Insightful Comment';

		$this->client->expects($this->once())
			->method('post')
			->with('/gists/523/comments', json_encode($gist))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->createComment(523, 'My Insightful Comment'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testCreateCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$gist = new \stdClass;
		$gist->body = 'My Insightful Comment';

		$this->client->expects($this->once())
			->method('post')
			->with('/gists/523/comments', json_encode($gist))
			->will($this->returnValue($this->response));

		$this->object->createComment(523, 'My Insightful Comment');
	}

	/**
	 * Tests the delete method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDelete()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/254')
			->will($this->returnValue($this->response));

		$this->object->delete(254);
	}

	/**
	 * Tests the delete method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testDeleteFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/254')
			->will($this->returnValue($this->response));

		$this->object->delete(254);
	}

	/**
	 * Tests the deleteComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteComment()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/comments/254')
			->will($this->returnValue($this->response));

		$this->object->deleteComment(254);
	}

	/**
	 * Tests the deleteComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testDeleteCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/comments/254')
			->will($this->returnValue($this->response));

		$this->object->deleteComment(254);
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
				'description' => 'This is a gist',
				'public' => true,
				'files' => array(
					'file1.txt' => array('content' => 'This is the first file'),
					'file2.txt' => array('content' => 'This is the second file')
				)
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/gists/512', $data)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(
				512,
				array(
					'file1.txt' => 'This is the first file',
					'file2.txt' => 'This is the second file'
				),
				true,
				'This is a gist'
			),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the edit method - simulated failure
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
				'description' => 'This is a gist',
				'public' => true,
				'files' => array(
					'file1.txt' => array('content' => 'This is the first file'),
					'file2.txt' => array('content' => 'This is the second file')
				)
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/gists/512', $data)
			->will($this->returnValue($this->response));

		$this->object->edit(
			512,
			array(
				'file1.txt' => 'This is the first file',
				'file2.txt' => 'This is the second file'
			),
			true,
			'This is a gist'
		);
	}

	/**
	 * Tests the editComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditComment()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$gist = new \stdClass;
		$gist->body = 'This comment is now even more insightful';

		$this->client->expects($this->once())
			->method('patch')
			->with('/gists/comments/523', json_encode($gist))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->editComment(523, 'This comment is now even more insightful'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testEditCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$gist = new \stdClass;
		$gist->body = 'This comment is now even more insightful';

		$this->client->expects($this->once())
			->method('patch')
			->with('/gists/comments/523', json_encode($gist))
			->will($this->returnValue($this->response));

		$this->object->editComment(523, 'This comment is now even more insightful');
	}

	/**
	 * Tests the fork method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFork()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/gists/523/fork')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->fork(523),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the fork method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testForkFailure()
	{
		$this->response->code = 501;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post')
			->with('/gists/523/fork')
			->will($this->returnValue($this->response));

		$this->object->fork(523);
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
			->with('/gists/523')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(523),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the get method - simulated failure
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
			->with('/gists/523')
			->will($this->returnValue($this->response));

		$this->object->get(523);
	}

	/**
	 * Tests the getComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetComment()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/comments/523')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getComment(523),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getComment method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetCommentFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/comments/523')
			->will($this->returnValue($this->response));

		$this->object->getComment(523);
	}

	/**
	 * Tests the getComments method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetComments()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/523/comments')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getComments(523),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getComments method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetCommentsFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/523/comments')
			->will($this->returnValue($this->response));

		$this->object->getComments(523);
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
			->with('/gists')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getList method - simulated failure
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
			->with('/gists')
			->will($this->returnValue($this->response));

		$this->object->getList();
	}

	/**
	 * Tests the getListByUser method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetListByUser()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/users/joomla/gists')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListByUser('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListByUser method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetListByUserFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/users/joomla/gists')
			->will($this->returnValue($this->response));

		$this->object->getListByUser('joomla');
	}

	/**
	 * Tests the getListPublic method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetListPublic()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/public')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListPublic(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListPublic method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetListPublicFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/public')
			->will($this->returnValue($this->response));

		$this->object->getListPublic();
	}

	/**
	 * Tests the getListStarred method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetListStarred()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/starred')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListStarred(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListStarred method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testGetListStarredFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/starred')
			->will($this->returnValue($this->response));

		$this->object->getListStarred();
	}

	/**
	 * Tests the isStarred method when the gist has been starred
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testIsStarredTrue()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/523/star')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->isStarred(523),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the isStarred method when the gist has not been starred
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testIsStarredFalse()
	{
		$this->response->code = 404;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/523/star')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->isStarred(523),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the isStarred method expecting a failure response
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testIsStarredFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/gists/523/star')
			->will($this->returnValue($this->response));

		$this->object->isStarred(523);
	}

	/**
	 * Tests the star method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testStar()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put')
			->with('/gists/523/star', '')
			->will($this->returnValue($this->response));

		$this->object->star(523);
	}

	/**
	 * Tests the star method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testStarFailure()
	{
		$this->response->code = 504;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('put')
			->with('/gists/523/star', '')
			->will($this->returnValue($this->response));

		$this->object->star(523);
	}

	/**
	 * Tests the unstar method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUnstar()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/523/star')
			->will($this->returnValue($this->response));

		$this->object->unstar(523);
	}

	/**
	 * Tests the unstar method - simulated failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  DomainException
	 */
	public function testUnstarFailure()
	{
		$this->response->code = 504;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/gists/523/star')
			->will($this->returnValue($this->response));

		$this->object->unstar(523);
	}
}
