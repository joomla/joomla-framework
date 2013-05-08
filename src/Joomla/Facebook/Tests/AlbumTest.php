<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Album;
use stdClass;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Album.
 *
 * @since  1.0
 */
class AlbumTest extends FacebookTestCase
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

		$this->object = new Album($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getAlbum method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetAlbum()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getAlbum($album),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getAlbum method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetAlbumFailure()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getAlbum($album);
	}

	/**
	 * Tests the getPhotos method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPhotos()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/photos?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPhotos($album),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPhotos method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetPhotosFailure()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/photos?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getPhotos($album);
	}

	/**
	 * Tests the createPhoto method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreatePhoto()
	{
		$album = '124346363456';
		$source = 'path/to/source';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data[basename($source)] = '@' . realpath($source);
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with(
			$album . '/photos?access_token=' . $token['access_token'], $data,
			array('Content-Type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createPhoto($album, $source, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createPhoto method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreatePhotoFailure()
	{
		$album = '124346363456';
		$source = '/path/to/source';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data[basename($source)] = '@' . realpath($source);
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with(
			$album . '/photos?access_token=' . $token['access_token'], $data,
			array('Content-Type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		$this->object->createPhoto($album, $source, $message);
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($album),
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/comments?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getComments($album);
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
		$album = '124346363456';
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
		->with($album . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createComment($album, $message),
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
		$album = '124346363456';
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
		->with($album . '/comments?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createComment($album, $message);
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($album),
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getLikes($album);
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($album . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLike($album),
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('post')
		->with($album . '/likes?access_token=' . $token['access_token'], '')
		->will($this->returnValue($returnData));

		$this->object->createLike($album);
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('delete')
		->with($album . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLike($album),
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
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('delete')
		->with($album . '/likes?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteLike($album);
	}

	/**
	 * Tests the getPicture method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPicture()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/picture?redirect=false&access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPicture($album, false),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPicture method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetPictureFailure()
	{
		$album = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$token = $this->oauth->getToken();

		$this->client->expects($this->once())
		->method('get')
		->with($album . '/picture?redirect=false&access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getPicture($album, false);
	}
}
