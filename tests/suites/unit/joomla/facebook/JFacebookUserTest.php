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
require_once JPATH_PLATFORM . '/joomla/facebook/user.php';

/**
 * Test class for JFacebook.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Facebook
 *
 * @since       12.1
 */
class JFacebookUserTest extends TestCase
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
	 * @var    JFacebookUser  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * @var    string  Sample URL string.
	 * @since  12.1
	 */
	protected $sampleUrl = '"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash2/372662_10575676585_830678637_q.jpg"';

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
<<<<<<< HEAD
	 * @access protected
	 *
	 * @return   void
=======
	 * @access  protected
	 *
	 * @return  void
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JFacebookHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JFacebookUser($this->options, $this->client);
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
	 * Tests the getUser method
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetUser()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getUser('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getUser method - failure
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  DomainException
	 */
	public function testGetUserFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getUser('me', $access_token);
	}

	/**
	 * Tests the getFriends method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetFriends()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friends?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriends('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriends method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  DomainException
	 */
	public function testGetFriendsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friends?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getFriends('me', $access_token);
	}

	/**
	 * Tests the getFriendRequests method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetFriendRequests()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friendrequests?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendRequests('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendRequests method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetFriendRequestsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friendrequests?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getFriendRequests('me', $access_token);
	}

	/**
	 * Tests the getFriendLists method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetFriendLists()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friendlists?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFriendLists('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFriendLists method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetFriendListsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friendlists?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getFriendLists('me', $access_token);
	}

	/**
	 * Tests the getFeed method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetFeed()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/feed?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFeed('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFeed method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  DomainException
	 */
	public function testGetFeedFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/feed?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getFeed('me', $access_token);
	}

	/**
	 * Tests the hasFriend method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testHasFriend()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friends/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->hasFriend('me', 2341245353, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the hasFriend method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  DomainException
	 */
	public function testHasFriendFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/friends/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->hasFriend('me', 2341245353, $access_token);
	}

	/**
	 * Tests the getMutualFriends method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetMutualFriends()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/mutualfriends/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMutualFriends('me', 2341245353, $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMutualFriends method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  DomainException
	 */
	public function testGetMutualFriendsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/mutualfriends/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getMutualFriends('me', 2341245353, $access_token);
	}

	/**
	 * Tests the getPicture method.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetPicture()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$type = 'large';

		$returnData = new JHttpResponse;
		$returnData->headers['Location'] = $this->sampleUrl;

		$this->client->expects($this->once())
		->method('get')
		->with('me/picture?access_token=' . $access_token . '&type=' . $type)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPicture('me', $access_token, $type),
			$this->equalTo($this->sampleUrl)
		);
	}

	/**
	 * Tests the getPicture method - failure.
	 *
	 * @return  void
<<<<<<< HEAD
	 *
=======
	 *
	 * @since   12.1
>>>>>>> 8f014d8... Fix Code Style errors.
	 * @expectedException  PHPUnit_Framework_Error
	 */
	public function testGetPictureFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$type = 'large';

		$returnData = new JText($this->errorString);

		$this->client->expects($this->once())
		->method('get')
		->with('me/picture?access_token=' . $access_token . '&type=' . $type)
		->will($this->returnValue($returnData));

		$this->object->getPicture('me', $access_token, $type);
	}

	/**
	 * Tests the getFamily method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetFamily()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/family?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFamily('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFamily method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetFamilyFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/family?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getFamily('me', $access_token);
	}

	/**
	 * Tests the getNotifications method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetNotifications()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/notifications?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getNotifications('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getNotifications method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetNotificationsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/notifications?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getNotifications('me', $access_token);
	}

	/**
	 * Tests the updateNotification method.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testUpdateNotification()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$notification = 'notif_343543656';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('post')
		->with($notification . '?unread=0&access_token=' . $access_token, '')
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateNotification($notification, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the updateNotification method - failure.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testUpdateNotificationFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$notification = 'notif_343543656';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($notification . '?unread=0&access_token=' . $access_token, '')
		->will($this->returnValue($returnData));

		try
		{
			$this->object->updateNotification($notification, $access_token);
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
	 * Tests the getPermissions method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetPermissions()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/permissions?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPermissions('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPermissions method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetPermissionsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/permissions?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getPermissions('me', $access_token);
	}

	/**
	 * Tests the deletePermission method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeletePermission()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$permission = 'some_permission';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with('me/permissions?permission=' . $permission . '&access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deletePermission('me', $access_token, $permission),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deletePermission method - failure.
	 *
	 *@covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeletePermissionFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$permission = 'some_permission';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with('me/permissions?permission=' . $permission . '&access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deletePermission('me', $access_token, $permission);
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
	 * Tests the getAlbums method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetAlbums()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/albums?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getAlbums('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getAlbums method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetAlbumsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/albums?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getAlbums('me', $access_token);
	}

	/**
	 * Tests the createAlbum method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateAlbum()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$name = 'test';
		$description = 'This is a test';
		$privacy = '{"value": "SELF"}';

		// Set POST request parameters.
		$data = array();
		$data['name'] = $name;
		$data['description'] = $description;
		$data['privacy'] = $privacy;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/albums' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createAlbum('me', $access_token, $name, $description, $privacy),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createAlbum method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateAlbumFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$name = 'test';
		$description = 'This is a test';
		$privacy = '{"value": "SELF"}';

		// Set POST request parameters.
		$data = array();
		$data['name'] = $name;
		$data['description'] = $description;
		$data['privacy'] = $privacy;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/albums' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createAlbum('me', $access_token, $name, $description, $privacy);
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
	 * Tests the getCheckins method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetCheckins()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/checkins?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getCheckins('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCheckins method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetCheckinsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/checkins?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getCheckins('me', $access_token);
	}

	/**
	 * Tests the createCheckin method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateCheckin()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$place = '241967239209655';
		$coordinates = '{"latitude":"44.42863444299","longitude":"26.133339107061"}';
		$tags = 'me';
		$message = 'message';
		$link = 'www.test.com';
		$picture = 'some_picture_url';

		// Set POST request parameters.
		$data = array();
		$data['place'] = $place;
		$data['coordinates'] = $coordinates;
		$data['tags'] = $tags;
		$data['message'] = $message;
		$data['link'] = $link;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/checkins' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createCheckin('me', $access_token, $place, $coordinates, $tags, $message, $link, $picture),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createCheckin method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateCheckinFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$place = '241967239209655';
		$coordinates = '{"latitude":"44.42863444299","longitude":"26.133339107061"}';
		$tags = 'me';
		$message = 'message';
		$link = 'www.test.com';
		$picture = 'some_picture_url';

		// Set POST request parameters.
		$data = array();
		$data['place'] = $place;
		$data['coordinates'] = $coordinates;
		$data['tags'] = $tags;
		$data['message'] = $message;
		$data['link'] = $link;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/checkins' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createCheckin('me', $access_token, $place, $coordinates, $tags, $message, $link, $picture);
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
	 * Tests the getLikes method.
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
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLikes method - failure.
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
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/likes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getLikes('me', $access_token);
	}

	/**
	 * Tests the likesPage method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testLikesPage()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/likes/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->likesPage('me', $access_token, 2341245353),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the likesPage method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testLikesPageFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/likes/2341245353?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->likesPage('me', $access_token, 2341245353);
	}

	/**
	 * Tests the getEvents method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetEvents()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/events?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getEvents('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getEvents method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetEventsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/events?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getEvents('me', $access_token);
	}

	/**
	 * Tests the createEvent method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateEvent()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$name = 'test';
		$start_time = 1590962400;
		$end_time = 1590966000;
		$description = 'description';
		$location = 'location';
		$location_id = '23132156';
		$privacy_type = 'SECRET';

		// Set POST request parameters.
		$data = array();
		$data['start_time'] = $start_time;
		$data['name'] = $name;
		$data['end_time'] = $end_time;
		$data['description'] = $description;
		$data['location'] = $location;
		$data['location_id'] = $location_id;
		$data['privacy_type'] = $privacy_type;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/events' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createEvent('me', $access_token, $name, $start_time, $end_time, $description, $location, $location_id, $privacy_type),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createCheckin method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateEventFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$name = 'test';
		$start_time = 1590962400;
		$end_time = 1590966000;
		$description = 'description';
		$location = 'location';
		$location_id = '23132156';
		$privacy_type = 'SECRET';

		// Set POST request parameters.
		$data = array();
		$data['start_time'] = $start_time;
		$data['name'] = $name;
		$data['end_time'] = $end_time;
		$data['description'] = $description;
		$data['location'] = $location;
		$data['location_id'] = $location_id;
		$data['privacy_type'] = $privacy_type;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/events' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createEvent('me', $access_token, $name, $start_time, $end_time, $description, $location, $location_id, $privacy_type);
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
	 * Tests the editEvent method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testEditEvent()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$event = '345345345435';
		$name = 'test';
		$start_time = 1590962400;
		$end_time = 1590966000;
		$description = 'description';
		$location = 'location';
		$location_id = '23132156';
		$privacy_type = 'SECRET';

		// Set POST request parameters.
		$data = array();
		$data['start_time'] = $start_time;
		$data['name'] = $name;
		$data['end_time'] = $end_time;
		$data['description'] = $description;
		$data['location'] = $location;
		$data['location_id'] = $location_id;
		$data['privacy_type'] = $privacy_type;

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('post')
		->with($event . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->editEvent($event, $access_token, $name, $start_time, $end_time, $description, $location, $location_id, $privacy_type),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the editCheckin method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testEditEventFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$event = '345345345435';
		$name = 'test';
		$start_time = 1590962400;
		$end_time = 1590966000;
		$description = 'description';
		$location = 'location';
		$location_id = '23132156';
		$privacy_type = 'SECRET';

		// Set POST request parameters.
		$data = array();
		$data['start_time'] = $start_time;
		$data['name'] = $name;
		$data['end_time'] = $end_time;
		$data['description'] = $description;
		$data['location'] = $location;
		$data['location_id'] = $location_id;
		$data['privacy_type'] = $privacy_type;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($event . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->editEvent($event, $access_token, $name, $start_time, $end_time, $description, $location, $location_id, $privacy_type);
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
	 * Tests the deleteEvent method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteEvent()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$event = '5148941614';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($event . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteEvent($event, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteEvent method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteEventFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$event = '5148941614';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($event . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deleteEvent($event, $access_token);
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
	 * Tests the getGroups method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetGroups()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/groups?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getGroups('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getGroups method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetGroupsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/groups?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getGroups('me', $access_token);
	}

	/**
	 * Tests the getLinks method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetLinks()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/links?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLinks('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLinks method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetLinksFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/links?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getLinks('me', $access_token);
	}

	/**
	 * Tests the createLink method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateLink()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$link = 'www.example.com';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['link'] = $link;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createLink('me', $access_token, $link, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createLink method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateLinkFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$link = 'www.example.com';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['link'] = $link;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createLink('me', $access_token, $link, $message);
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
	 * Tests the deleteLink method.
	 * 
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteLink()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$link = '156174391080008_235345346';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteLink($link, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteLink method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 * 
	 * @since   12.1
	 */
	public function testDeleteLinkFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$link = '156174391080008_235345346';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($link . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deleteLink($link, $access_token);
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
	 * Tests the getNotes method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetNotes()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/notes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getNotes('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getNotes method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetNotesFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/notes?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getNotes('me', $access_token);
	}

	/**
	 * Tests the createNote method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateNote()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$subject = 'subject';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['subject'] = $subject;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/notes' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createNote('me', $access_token, $subject, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createNote method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateNoteFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$subject = 'subject';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['subject'] = $subject;
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/notes' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createNote('me', $access_token, $subject, $message);
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
	 * Tests the getPhotos method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetPhotos()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/photos?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPhotos('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPhotos method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetPhotosFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/photos?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getPhotos('me', $access_token);
	}

	/**
	 * Tests the createPhoto method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreatePhoto()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$source = 'path/to/source';
		$message = 'message';
		$place = '23432421234';
		$no_story = true;

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['place'] = $place;
		$data['no_story'] = $no_story;
		$data[basename($source)] = '@' . realpath($source);

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/photos' . '?access_token=' . $access_token, $data,
			array('Content-type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createPhoto('me', $access_token, $source, $message, $place, $no_story),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createPhoto method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreatePhotoFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$source = '/path/to/source';
		$message = 'message';
		$place = '23432421234';
		$no_story = true;

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['place'] = $place;
		$data['no_story'] = $no_story;
		$data[basename($source)] = '@' . realpath($source);

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with(
			'me/photos' . '?access_token=' . $access_token, $data,
			array('Content-type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createPhoto('me', $access_token, $source, $message, $place, $no_story);
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
	 * Tests the getPosts method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetPosts()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/posts?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPosts('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPosts method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetPostsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/posts?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getPosts('me', $access_token);
	}

	/**
	 * Tests the createPost method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreatePost()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$user = '134534252';
		$message = 'message';
		$link = 'www.example.com';
		$picture = 'thumbnail.example.com';
		$name = 'name';
		$caption = 'caption';
		$description = 'description';
		$place = '1244576532';
		$tags = '1207059,701732';
		$privacy = 'SELF';
		$object_attachment = '32413534634345';
		$actions = array('{"name":"Share","link":"http://networkedblogs.com/hGWk3?a=share"}');

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['link'] = $link;
		$data['name'] = $name;
		$data['caption'] = $caption;
		$data['description'] = $description;
		$data['actions'] = $actions;
		$data['place'] = $place;
		$data['tags'] = $tags;
		$data['privacy'] = $privacy;
		$data['object_attachment'] = $object_attachment;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($user . '/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createPost(
				$user, $access_token, $message, $link, $picture, $name,
				$caption, $description, $actions, $place, $tags, $privacy, $object_attachment
				),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createPost method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreatePostFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$user = '134534252';
		$message = 'message';
		$link = 'www.example.com';
		$picture = 'thumbnail.example.com';
		$name = 'name';
		$caption = 'caption';
		$description = 'description';
		$place = '1244576532';
		$tags = '1207059,701732';
		$privacy = 'SELF';
		$object_attachment = '32413534634345';
		$actions = array('{"name":"Share","link":"http://networkedblogs.com/hGWk3?a=share"}');

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['link'] = $link;
		$data['name'] = $name;
		$data['caption'] = $caption;
		$data['description'] = $description;
		$data['actions'] = $actions;
		$data['place'] = $place;
		$data['tags'] = $tags;
		$data['privacy'] = $privacy;
		$data['object_attachment'] = $object_attachment;
		$data['picture'] = $picture;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($user . '/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createPost(
				$user, $access_token, $message, $link, $picture, $name,
				$caption, $description, $actions, $place, $tags, $privacy, $object_attachment
				);
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
	 * Tests the deletePost method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeletePost()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$post = '5148941614';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($post . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deletePost($post, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deletePost method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeletePostFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$post = '5148941614';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($post . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deletePost($post, $access_token);
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
	 * Tests the getStatuses method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetStatuses()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/statuses?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getStatuses('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getStatuses method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetStatusesFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/statuses?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getStatuses('me', $access_token);
	}

	/**
	 * Tests the createStatus method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateStatus()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$user = '134534252';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with($user . '/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createStatus($user, $access_token, $message),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createStatus method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateStatusFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$user = '134534252';
		$message = 'message';

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with($user . '/feed' . '?access_token=' . $access_token, $data)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createStatus($user, $access_token, $message);
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
	 * Tests the deleteStatus method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteStatus()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '5148941614';

		$returnData = new stdClass;
		$returnData->body = true;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteStatus($status, $access_token),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the deleteStatus method - failure.
	 *
	 *@covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteStatusFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$status = '5148941614';

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with($status . '?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->deleteStatus($status, $access_token);
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
	 * Tests the getVideos method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetVideos()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/videos?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getVideos('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getVideos method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetVideosFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/videos?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getVideos('me', $access_token);
	}

	/**
	 * Tests the createVideo method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateVideo()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$source = '/path/to/source';
		$title = 'title';
		$description = 'Description example';

		// Set POST request parameters.
		$data = array();
		$data['title'] = $title;
		$data['description'] = $description;
		$data[basename($source)] = '@' . realpath($source);

		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/videos' . '?access_token=' . $access_token, $data,
			array('Content-type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createVideo('me', $access_token, $source, $title, $description),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createVideo method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateVideoFailure()
	{
		$exception = false;
		$access_token = '235twegsdgsdhtry3tgwgf';
		$source = '/path/to/source';
		$title = 'title';
		$description = 'Description example';

		// Set POST request parameters.
		$data = array();
		$data['title'] = $title;
		$data['description'] = $description;
		$data[basename($source)] = '@' . realpath($source);

		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('post')
		->with('me/videos' . '?access_token=' . $access_token, $data,
			array('Content-type' => 'multipart/form-data')
			)
		->will($this->returnValue($returnData));

		try
		{
			$this->object->createVideo('me', $access_token, $source, $title, $description);
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
	 * Tests the getTagged method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetTagged()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/tagged?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTagged('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTagged method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetTaggedFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/tagged?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getTagged('me', $access_token);
	}

	/**
	 * Tests the getActivities method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetActivities()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/activities?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getActivities('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getActivities method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetActivitiesFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/activities?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getActivities('me', $access_token);
	}

	/**
	 * Tests the getBooks method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetBooks()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/books?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getBooks('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getBooks method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetBooksFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/books?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getBooks('me', $access_token);
	}

	/**
	 * Tests the getInterests method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetInterests()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/interests?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getInterests('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getInterests method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetInterestsFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/interests?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getInterests('me', $access_token);
	}

	/**
	 * Tests the getMovies method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetMovies()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/movies?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMovies('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMovies method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetMoviesFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/movies?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getMovies('me', $access_token);
	}

	/**
	 * Tests the getTelevision method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetTelevision()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/television?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getTelevision('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getTelevision method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetTelevisionFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/television?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getTelevision('me', $access_token);
	}

	/**
	 * Tests the getMusic method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetMusic()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/music?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMusic('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMusic method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetMusicFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/music?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getMusic('me', $access_token);
	}

	/**
	 * Tests the getSubscribers method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetSubscribers()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/subscribers?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSubscribers('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSubscribers method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetSubscribersFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/subscribers?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getSubscribers('me', $access_token);
	}

	/**
	 * Tests the getSubscribedTo method.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetSubscribedTo()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/subscribedto?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSubscribedTo('me', $access_token),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSubscribedTo method - failure.
	 *
	 * @covers JFacebookObject::sendRequest
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException  DomainException
	 */
	public function testGetSubscribedToFailure()
	{
		$access_token = '235twegsdgsdhtry3tgwgf';
		$returnData = new stdClass;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('me/subscribedto?access_token=' . $access_token)
		->will($this->returnValue($returnData));

		$this->object->getSubscribedTo('me', $access_token);
	}
}