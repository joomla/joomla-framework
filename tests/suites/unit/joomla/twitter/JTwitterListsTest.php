<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/twitter.php';
require_once JPATH_PLATFORM . '/joomla/twitter/http.php';
require_once JPATH_PLATFORM . '/joomla/twitter/lists.php';

/**
 * Test class for JTwitterLists.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.3
 */
class JTwitterListsTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.3
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock client object.
	 * @since  12.3
	 */
	protected $client;

	/**
	 * @var    JTwitterLists  Object under test.
	 * @since  12.3
	 */
	protected $object;

	/**
	 * @var    JTwitterOAuth  Authentication object for the Twitter object.
	 * @since  12.3
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON Twitter error message.
	 * @since  12.3
	 */
	protected $twitterErrorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $rateLimit = '{"remaining_hits":150, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$key = "lIio7RcLe5IASG5jpnZrA";
		$secret = "dl3BrWij7LT04NUpy37BRJxGXpWgjNvMrneuQ11EveE";
		$my_url = "http://127.0.0.1/gsoc/joomla-platform/twitter_test.php";

		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterLists($this->options, $this->client);
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedUser()
	{
		// User ID or screen name
		return array(
			array(234654235457),
			array('testUser'),
			array(null)
			);
	}

	/**
	 * Tests the getAllLists method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 */
	public function testGetAllLists($user)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getAllLists($user);
		}

		$path = $this->object->fetchUrl('/1/lists/all.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getAllLists($user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getAllLists method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 * @expectedException DomainException
	 */
	public function testGetAllListsFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getAllLists($user);
		}

		$path = $this->object->fetchUrl('/1/lists/all.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getAllLists($user);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedListStatuses()
	{
		// List ID or slug and owner
		return array(
			array(234654235457, null),
			array('test-list', 'testUser'),
			array('test-list', 12345),
			array('test-list', null),
			array(null, null)
			);
	}

	/**
	 * Tests the getListStatuses method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testGetListStatuses($list, $owner)
	{
		$since_id = 12345;
		$max_id = 54321;
		$per_page = 10;
		$page = 1;
		$entities = true;
		$include_rts = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListStatuses($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListStatuses($list, $owner);
		}

		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['per_page'] = $per_page;
		$data['page'] = $page;
		$data['include_entities'] = $entities;
		$data['include_rts'] = $include_rts;

		$path = $this->object->fetchUrl('/1/lists/statuses.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getListStatuses($list, $owner, $since_id, $max_id, $per_page, $page, $entities, $include_rts),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListStatuses method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testGetListStatusesFailure($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListStatuses($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListStatuses($list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/statuses.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getListStatuses($list, $owner);
	}

	/**
	 * Tests the getListMemberships method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 */
	public function testGetListMemberships($user)
	{
		$filter = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListMemberships($user);
		}
		$data['filter_to_owned_lists'] = $filter;

		$path = $this->object->fetchUrl('/1/lists/memberships.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getListMemberships($user, $filter),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListMemberships method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 * @expectedException DomainException
	 */
	public function testGetListMembershipsFailure($user)
	{
		$filter = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListMemberships($user);
		}
		$data['filter_to_owned_lists'] = $filter;

		$path = $this->object->fetchUrl('/1/lists/memberships.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getListMemberships($user, $filter);
	}

	/**
	 * Tests the getListSubscribers method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testGetListSubscribers($list, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListSubscribers($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListSubscribers($list, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/subscribers.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getListSubscribers($list, $owner, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListSubscribers method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testGetListSubscribersFailure($list, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListSubscribers($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListSubscribers($list, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/subscribers.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getListSubscribers($list, $owner, $entities, $skip_status);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedListMembers()
	{
		// List, User ID, screen name and owner.
		return array(
			array(234654235457, null, '234654235457', null),
			array('test-list', null, 'userTest', 'testUser'),
			array('test-list', '234654235457', null, '56165105642'),
			array('test-list', 'testUser', null, null),
			array('test-list', null, null, 'testUser'),
			array('test-list', 'testUser', '234654235457', 'userTest'),
			array(null, null, null, null)
			);
	}

	/**
	 * Tests the deleteListMembers method
	 *
	 * @param   mixed   $list         Either an integer containing the list ID or a string containing the list slug.
	 * @param   string  $user_id      A comma separated list of user IDs, up to 100 are allowed in a single request.
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   mixed   $owner        Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListMembers
	 */
	public function testDeleteListMembers($list, $user_id, $screen_name, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		if ($user_id)
		{
			$data['user_id'] = $user_id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($user_id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/members/destroy_all.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteListMembers method - failure
	 *
	 * @param   mixed   $list         Either an integer containing the list ID or a string containing the list slug.
	 * @param   string  $user_id      A comma separated list of user IDs, up to 100 are allowed in a single request.
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   mixed   $owner        Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListMembers
	 * @expectedException DomainException
	 */
	public function testDeleteListMembersFailure($list, $user_id, $screen_name, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		if ($user_id)
		{
			$data['user_id'] = $user_id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($user_id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/members/destroy_all.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->deleteListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
	}

	/**
	 * Tests the subscribe method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testSubscribe($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->subscribe($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->subscribe($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/subscribers/create.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->subscribe($this->oauth, $list, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the subscribe method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testSubscribeFailure($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->subscribe($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->subscribe($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/subscribers/create.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->subscribe($this->oauth, $list, $owner);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 12.3
	*/
	public function seedListUserOwner()
	{
		// List, User and Owner.
		return array(
			array(234654235457, '234654235457', null),
			array('test-list', 'userTest', 'testUser'),
			array('test-list', '234654235457', '56165105642'),
			array('test-list', 'testUser', null),
			array('test-list', null, 'testUser'),
			array(null, null, null)
			);
	}

	/**
	 * Tests the isListMember method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $user   Either an integer containing the user ID or a string containing the screen name of the user to remove.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListUserOwner
	 */
	public function testIsListMember($list, $user, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->isListMember($this->oauth, $list, $user, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->isListMember($this->oauth, $list, $user, $owner);
		}

		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// We don't have a valid entry
			$this->setExpectedException('RuntimeException');
			$this->object->isListMember($this->oauth, $list, $user, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/members/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->isListMember($this->oauth, $list, $user, $owner, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the isListMember method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $user   Either an integer containing the user ID or a string containing the screen name of the user to remove.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListUserOwner
	 * @expectedException DomainException
	 */
	public function testIsListMemberFailure($list, $user, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->isListMember($this->oauth, $list, $user, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->isListMember($this->oauth, $list, $user, $owner);
		}

		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// We don't have a valid entry
			$this->setExpectedException('RuntimeException');
			$this->object->isListMember($this->oauth, $list, $user, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/members/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->isListMember($this->oauth, $list, $user, $owner, $entities, $skip_status);
	}

	/**
	 * Tests the isListSubscriber method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $user   Either an integer containing the user ID or a string containing the screen name of the user to remove.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListUserOwner
	 */
	public function testIsListSubscriber($list, $user, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
		}

		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// We don't have a valid entry
			$this->setExpectedException('RuntimeException');
			$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/subscribers/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->isListSubscriber($this->oauth, $list, $user, $owner, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the isListSubscriber method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $user   Either an integer containing the user ID or a string containing the screen name of the user to remove.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name of the owner.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListUserOwner
	 * @expectedException DomainException
	 */
	public function testIsListSubscriberFailure($list, $user, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
		}

		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// We don't have a valid entry
			$this->setExpectedException('RuntimeException');
			$this->object->isListSubscriber($this->oauth, $list, $user, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/subscribers/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->isListSubscriber($this->oauth, $list, $user, $owner, $entities, $skip_status);
	}

	/**
	 * Tests the unsubscribe method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testUnsubscribe($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->unsubscribe($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->unsubscribe($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/subscribers/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->unsubscribe($this->oauth, $list, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the unsubscribe method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testUnsubscribeFailure($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->unsubscribe($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->unsubscribe($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/subscribers/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->unsubscribe($this->oauth, $list, $owner);
	}

	/**
	 * Tests the addListMembers method
	 *
	 * @param   mixed   $list         Either an integer containing the list ID or a string containing the list slug.
	 * @param   string  $user_id      A comma separated list of user IDs, up to 100 are allowed in a single request.
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   mixed   $owner        Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListMembers
	 */
	public function testAddListMembers($list, $user_id, $screen_name, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		if ($user_id)
		{
			$data['user_id'] = $user_id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($user_id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/members/create_all.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the addListMembers method - failure
	 *
	 * @param   mixed   $list         Either an integer containing the list ID or a string containing the list slug.
	 * @param   string  $user_id      A comma separated list of user IDs, up to 100 are allowed in a single request.
	 * @param   string  $screen_name  A comma separated list of screen names, up to 100 are allowed in a single request.
	 * @param   mixed   $owner        Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListMembers
	 * @expectedException DomainException
	 */
	public function testAddListMembersFailure($list, $user_id, $screen_name, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		if ($user_id)
		{
			$data['user_id'] = $user_id;
		}
		if ($screen_name)
		{
			$data['screen_name'] = $screen_name;
		}
		if ($user_id == null && $screen_name == null)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/members/create_all.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->addListMembers($this->oauth, $list, $user_id, $screen_name, $owner);
	}

	/**
	 * Tests the getListMembers method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testGetListMembers($list, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListMembers($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListMembers($list, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/members.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getListMembers($list, $owner, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListMembers method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testGetListMembersFailure($list, $owner)
	{
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListMembers($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListMembers($list, $owner);
		}

		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/1/lists/members.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getListMembers($list, $owner, $entities, $skip_status);
	}

	/**
	 * Tests the getListById method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testGetListByIdtMembers($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListById($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListById($list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getListById($list, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListById method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testGetListByIdtMembersFailure($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->getListById($list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getListById($list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/show.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getListById($list, $owner);
	}

	/**
	 * Tests the getLists method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 */
	public function testGetLists($user)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getLists($user);
		}

		$path = $this->object->fetchUrl('/1/lists.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLists($user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getLists method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 * @expectedException DomainException
	 */
	public function testGetListsFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getLists($user);
		}

		$path = $this->object->fetchUrl('/1/lists.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getLists($user);
	}

	/**
	 * Tests the getSubscriptions method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 */
	public function testGetSubscriptions($user)
	{
		$count = 10;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getSubscriptions($user);
		}
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/lists/subscriptions.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSubscriptions($user, $count),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSubscriptions method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedUser
	 * @expectedException DomainException
	 */
	public function testGetSubscriptionsFailure($user)
	{
		$count = 10;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->getSubscriptions($user);
		}
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/1/lists/subscriptions.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSubscriptions($user, $count);
	}

	/**
	 * Tests the updateList method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testUpdateList($list, $owner)
	{
		$name = 'test list';
		$mode = 'private';
		$description = 'this is a description';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->updateList($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->updateList($this->oauth, $list, $owner);
		}

		$data['name'] = $name;
		$data['mode'] = $mode;
		$data['description'] = $description;

		$path = $this->object->fetchUrl('/1/lists/update.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateList($this->oauth, $list, $owner, $name, $mode, $description),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateList method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testUpdateListFailure($list, $owner)
	{
		$name = 'test list';
		$mode = 'private';
		$description = 'this is a description';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->updateList($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->updateList($this->oauth, $list, $owner);
		}

		$data['name'] = $name;
		$data['mode'] = $mode;
		$data['description'] = $description;

		$path = $this->object->fetchUrl('/1/lists/update.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->updateList($this->oauth, $list, $owner, $name, $mode, $description);
	}

	/**
	 * Tests the createList method
	 *
	 * @return  void
	 *
	 * @since 12.3
	 */
	public function testCreateList()
	{
		$name = 'test list';
		$mode = 'private';
		$description = 'this is a description';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['name'] = $name;
		$data['mode'] = $mode;
		$data['description'] = $description;

		$path = $this->object->fetchUrl('/1/lists/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createList($this->oauth, $name, $mode, $description),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createList method - failure
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @expectedException DomainException
	 */
	public function testCreateListFailure()
	{
		$name = 'test list';
		$mode = 'private';
		$description = 'this is a description';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['name'] = $name;
		$data['mode'] = $mode;
		$data['description'] = $description;

		$path = $this->object->fetchUrl('/1/lists/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->createList($this->oauth, $name, $mode, $description);
	}

	/**
	 * Tests the deleteList method
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 */
	public function testDeleteList($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->deleteList($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteList($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteList($this->oauth, $list, $owner),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteList method - failure
	 *
	 * @param   mixed  $list   Either an integer containing the list ID or a string containing the list slug.
	 * @param   mixed  $owner  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 12.3
	 * @dataProvider seedListStatuses
	 * @expectedException DomainException
	 */
	public function testDeleteListFailure($list, $owner)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($list))
		{
			$data['list_id'] = $list;
		}
		elseif (is_string($list))
		{
			$data['slug'] = $list;

			if (is_numeric($owner))
			{
				$data['owner_id'] = $owner;
			}
			elseif (is_string($owner))
			{
				$data['owner_screen_name'] = $owner;
			}
			else
			{
				// We don't have a valid entry
				$this->setExpectedException('RuntimeException');
				$this->object->deleteList($this->oauth, $list, $owner);
			}
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->deleteList($this->oauth, $list, $owner);
		}

		$path = $this->object->fetchUrl('/1/lists/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->deleteList($this->oauth, $list, $owner);
	}
}
