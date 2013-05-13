<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Group;
use Joomla\Http\Response;
use stdClass;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Group.
 *
 * @since  1.0
 */
class GroupTest extends FacebookTestCase
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

		$this->object = new Group($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the getGroup method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetGroup()
	{
		$token = $this->oauth->getToken();
		$group = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getGroup($group),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getGroup method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetGroupFailure()
	{
		$token = $this->oauth->getToken();
		$group = '124346363456';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getGroup($group);
	}

	/**
	 * Tests the getFeed method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetFeed()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/feed?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFeed($group),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFeed method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetFeedFailure()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/feed?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getFeed($group);
	}

	/**
	 * Tests the getMembers method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetMembers()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/members?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMembers($group),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMembers method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetMembersFailure()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/members?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getMembers($group);
	}

	/**
	 * Tests the getDocs method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetDocs()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/docs?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getDocs($group),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getDocs method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetDocsFailure()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/docs?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getDocs($group);
	}

	/**
	 * Tests the getPicture method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPicture()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';
		$type = 'large';

		$returnData = new Response;
		$returnData->code = 302;
		$returnData->headers['Location'] = $this->sampleUrl;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/picture?type=' . $type . '&access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPicture($group, $type),
			$this->equalTo($this->sampleUrl)
		);
	}

	/**
	 * Tests the getPicture method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testGetPictureFailure()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';
		$type = 'large';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with($group . '/picture?type=' . $type . '&access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->getPicture($group, $type);
	}

	/**
	 * Tests the createLink method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateLink()
	{
		$token = $this->oauth->getToken();
		$group = '156174391080008';
		$link = 'www.example.com';
		$message = 'This is a message';

		// Set POST request parameters.
		$data = array();
		$data['link'] = $link;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLink($group, $link, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createLink method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreateLinkFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$group = '156174391080008';
		$link = 'www.example.com';
		$message = 'This is a message';

		// Set POST request parameters.
		$data = array();
		$data['link'] = $link;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createLink($group, $link, $message);
	}

	/**
	 * Tests the deleteLink method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteLink()
	{
		$token = $this->oauth->getToken();
		$link = '156174391080008_235345346';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLink($link),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteLink method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testDeleteLinkFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$link = '156174391080008_235345346';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteLink($link);
	}

	/**
	 * Tests the createPost method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreatePost()
	{
		$token = $this->oauth->getToken();
		$group = '134534252';
		$message = 'message';
		$link = 'www.example.com';
		$picture = 'thumbnail.example.com';
		$name = 'name';
		$caption = 'caption';
		$description = 'description';
		$actions = array('{"name":"Share","link":"http://networkedblogs.com/hGWk3?a=share"}');

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['link'] = $link;
		$data['name'] = $name;
		$data['caption'] = $caption;
		$data['description'] = $description;
		$data['actions'] = $actions;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createPost(
				$group, $message, $link, $picture, $name,
				$caption, $description, $actions
				),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createPost method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreatePostFailure()
	{
		$token = $this->oauth->getToken();
		$group = '134534252';
		$message = 'message';
		$link = 'www.example.com';
		$picture = 'thumbnail.example.com';
		$name = 'name';
		$caption = 'caption';
		$description = 'description';
		$actions = array('{"name":"Share","link":"http://networkedblogs.com/hGWk3?a=share"}');

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['link'] = $link;
		$data['name'] = $name;
		$data['caption'] = $caption;
		$data['description'] = $description;
		$data['actions'] = $actions;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createPost(
			$group, $message, $link, $picture, $name,
			$caption, $description, $actions
			);
	}

	/**
	 * Tests the deletePost method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeletePost()
	{
		$token = $this->oauth->getToken();
		$post = '5148941614_234324';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($post . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deletePost($post),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deletePost method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testDeletePostFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$post = '5148941614_234324';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($post . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deletePost($post);
	}

	/**
	 * Tests the createStatus method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateStatus()
	{
		$token = $this->oauth->getToken();
		$group = '134534252457';
		$message = 'This is a message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createStatus($group, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createStatus method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testCreateStatusFailure()
	{
		$token = $this->oauth->getToken();
		$group = '134534252457';
		$message = 'This is a message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($group . '/feed' . '?access_token=' . $token['access_token'], $data)
		->will($this->returnValue($returnData));

		$this->object->createStatus($group, $message);
	}

	/**
	 * Tests the deleteStatus method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteStatus()
	{
		$token = $this->oauth->getToken();
		$status = '2457344632_5148941614';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteStatus($status),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteStatus method - failure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException  RuntimeException
	 */
	public function testDeleteStatusFailure()
	{
		$exception = false;
		$token = $this->oauth->getToken();
		$status = '2457344632_5148941614';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '?access_token=' . $token['access_token'])
		->will($this->returnValue($returnData));

		$this->object->deleteStatus($status);
	}
}
