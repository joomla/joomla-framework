<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Checkin;
use stdClass;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Checkin.
 *
 * @since  1.0
 */
class CheckinTest extends FacebookTestCase
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

		$this->object = new Checkin($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getCheckin method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetCheckin()
	{
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getCheckin($checkin),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCheckin method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetCheckinFailure()
	{
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getCheckin($checkin);
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($checkin),
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getComments($checkin);
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
		$checkin = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($checkin . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createComment($checkin, $message),
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
		$checkin = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($checkin . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createComment($checkin, $message);
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
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$token = $this->oauth->getToken();

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
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($checkin),
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($checkin . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getLikes($checkin);
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($checkin . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLike($checkin),
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($checkin . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->object->createLike($checkin);
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('delete')
		->with($checkin . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLike($checkin),
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
		$checkin = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('delete')
		->with($checkin . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteLike($checkin);
	}
}
