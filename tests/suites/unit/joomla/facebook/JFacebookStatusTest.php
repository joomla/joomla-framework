<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Facebook
 * 
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/facebook/http.php';
require_once JPATH_PLATFORM . '/joomla/facebook/facebook.php';
require_once JPATH_PLATFORM . '/joomla/facebook/status.php';

/**
 * Test class for JFacebook.
 * 
 * @package     Joomla.UnitTest
 * @subpackage  Facebook
 * 
 * @since       12.1
 */
class JFacebookStatusTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Facebook object.
	 * @since  12.1
	 */
	protected $options;

	/**
	 * @var    JFacebookHttp  Mock client object.
	 * @since  12.1
	 */
	protected $client;

	/**
	 * @var    JFacebookStatus  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"error": {"message": "Generic Error."}}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access  protected
	 * 
	 * @return  void
	 * 
	 * @since   12.1
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JFacebookHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JFacebookStatus($this->options, $this->client);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 * 
	 * @return   void
	 * 
	 * @since   12.1
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the getStatus method
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testGetStatus()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getStatus($status, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getStatus method - failure
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetStatusFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getStatus($status, $access_token);
	}

	/**
	 * Tests the getComments method
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testGetComments()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '/comments?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($status, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getComments method - failure
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetCommentsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '/comments?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getComments($status, $access_token);
	}

	/**
	 * Tests the createComment method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testCreateComment()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($status . '/comments?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createComment($status, $access_token, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createComment method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testCreateCommentFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';
		$message = 'test message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($status . '/comments?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createComment($status, $access_token, $message);
		}
		catch (DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->error->message)
			);
		}
	}

	/**
	 * Tests the deleteComment method.
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteComment()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($comment . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteComment($comment, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteComment method - failure.
	 *
	 *@covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteCommentFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$comment = '5148941614_12343468';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($comment . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deleteComment($comment, $access_token);
		}
		catch (DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->error->message)
			);
		}
	}

	/**
	 * Tests the getLikes method
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testGetLikes()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($status, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLikes method - failure
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetLikesFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($status . '/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getLikes($status, $access_token);
	}

	/**
	 * Tests the createLike method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testCreateLike()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($status . '/likes?access_token=' . $access_token, '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLike($status, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createLike method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testCreateLikeFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($status . '/likes?access_token=' . $access_token, '')
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createLike($status, $access_token);
		}
		catch (DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->error->message)
			);
		}
	}

	/**
	 * Tests the deleteLike method.
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteLike()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLike($status, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteLike method - failure.
	 *
	 *@covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteLikeFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '124346363456';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deleteLike($status, $access_token);
		}
		catch (DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->error->message)
			);
		}
	}
}