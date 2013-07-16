<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Note;
use stdClass;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Note.
 *
 * @since  1.0
 */
class NoteTest extends FacebookTestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access  protected
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Note($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the getNote method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNote()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getNote($note),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getNote method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetNoteFailure()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getNote($note);
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
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($note),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getComments method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetCommentsFailure()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getComments($note);
	}

	/**
	 * Tests the createComment method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateComment()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($note . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createComment($note, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createComment method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreateCommentFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$note = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($note . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createComment($note, $message);
	}

	/**
	 * Tests the deleteComment method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteComment()
	{
		$token = $this->oauth->getToken();
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($comment . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteComment($comment),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteComment method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testDeleteCommentFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($comment . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteComment($comment);
	}

	/**
	 * Tests the getLikes method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetLikes()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($note),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLikes method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetLikesFailure()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($note . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getLikes($note);
	}

	/**
	 * Tests the createLike method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateLike()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($note . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLike($note),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createLike method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreateLikeFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($note . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->object->createLike($note);
	}

	/**
	 * Tests the deleteLike method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteLike()
	{
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($note . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLike($note),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteLike method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testDeleteLikeFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$note = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($note . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteLike($note);
	}
}
