<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Link;
use stdClass;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Link.
 *
 * @since  1.0
 */
class LinkTest extends FacebookTestCase
{
	/**
	 * @var    string  Sample URL string.
	 * @since  1.0
	 */
	protected $sampleUrl = '"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash2/372662_10575676585_830678637_q.jpg"';

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

		$this->object = new Link($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getLink method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetLink()
	{
		$token = $this->oauth->getToken();
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLink($link),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLink method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetLinkFailure()
	{
		$token = $this->oauth->getToken();
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getLink($link);
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($link),
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getComments($link);
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
		$link = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($link . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createComment($link, $message),
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
		$link = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($link . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createComment($link, $message);
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($link),
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($link . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getLikes($link);
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($link . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLike($link),
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($link . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->object->createLike($link);
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLike($link),
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
		$link = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteLike($link);
	}
}
