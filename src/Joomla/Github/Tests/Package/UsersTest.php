<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Users;
use Joomla\Registry\Registry;

/**
 * Test class for Users.
 *
 * @since  1.0
 */
class UsersTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \PHPUnit_Framework_MockObject_MockObject  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var Users
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"message": "Generic Error"}';

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
		parent::setUp();

		$this->options  = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Users($this->options, $this->client);
	}

	/**
	 * Tests the getUser method
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = '{
  "login": "octocat",
  "id": 1,
  "avatar_url": "https://github.com/images/error/octocat_happy.gif",
  "gravatar_id": "somehexcode",
  "url": "https://api.github.com/users/octocat",
  "name": "monalisa octocat",
  "company": "GitHub",
  "blog": "https://github.com/blog",
  "location": "San Francisco",
  "email": "octocat@github.com",
  "hireable": false,
  "bio": "There once was...",
  "public_repos": 2,
  "public_gists": 1,
  "followers": 20,
  "following": 0,
  "html_url": "https://github.com/octocat",
  "created_at": "2008-01-14T04:33:35Z",
  "type": "User"
}';

		$this->client->expects($this->once())
			->method('get')
			->with('/users/joomla', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getUser method with failure
	 *
	 * @expectedException  \DomainException
	 * @return void
	 */
	public function testGetFailure()
	{
		$this->response->code = 404;
		$this->response->body = '{"message":"Not Found"}';

		$this->client->expects($this->once())
			->method('get')
			->with('/users/nonexistentuser', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('nonexistentuser'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getAuthenticatedUser method
	 *
	 * @return void
	 */
	public function testGetAuthenticatedUser()
	{
		$this->response->code = 200;
		$this->response->body = '{
  "login": "octocat",
  "id": 1,
  "avatar_url": "https://github.com/images/error/octocat_happy.gif",
  "gravatar_id": "somehexcode",
  "url": "https://api.github.com/users/octocat",
  "name": "monalisa octocat",
  "company": "GitHub",
  "blog": "https://github.com/blog",
  "location": "San Francisco",
  "email": "octocat@github.com",
  "hireable": false,
  "bio": "There once was...",
  "public_repos": 2,
  "public_gists": 1,
  "followers": 20,
  "following": 0,
  "html_url": "https://github.com/octocat",
  "created_at": "2008-01-14T04:33:35Z",
  "type": "User",
  "total_private_repos": 100,
  "owned_private_repos": 100,
  "private_gists": 81,
  "disk_usage": 10000,
  "collaborators": 8,
  "plan": {
    "name": "Medium",
    "space": 400,
    "collaborators": 10,
    "private_repos": 20
  }
}';

		$this->client->expects($this->once())
			->method('get')
			->with('/user', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getAuthenticatedUser('joomla'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetAuthenticatedUser method with failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 */
	public function testGetAuthenticatedUserFailure()
	{
		$this->response->code = 401;
		$this->response->body = '{"message":"Requires authentication"}';

		$this->client->expects($this->once())
			->method('get')
			->with('/user', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getAuthenticatedUser(),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getUsers method
	 *
	 * @return void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = '[
  {
    "login": "octocat",
    "id": 1,
    "avatar_url": "https://github.com/images/error/octocat_happy.gif",
    "gravatar_id": "somehexcode",
    "url": "https://api.github.com/users/octocat"
  }
],
  {
    "login": "elkuku",
    "id": 33978,
    "avatar_url": "https://github.com/images/error/octocat_happy.gif",
    "gravatar_id": "somehexcode",
    "url": "https://api.github.com/users/elkuku"
  }
]';

		$this->client->expects($this->once())
			->method('get')
			->with('/users', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList(),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getUsers method
	 *
	 * @return void
	 */
	public function testEdit()
	{
		$name     = 'monalisa octocat';
		$email    = 'octocat@github.com';
		$blog     = 'https =>//github.com/blog';
		$company  = 'GitHub';
		$location = 'San Francisco';
		$hireable = true;
		$bio      = 'There once...';

		$this->response->code = 200;
		$this->response->body = '{
  "login": "octocat",
  "id": 1,
  "avatar_url": "https://github.com/images/error/octocat_happy.gif",
  "gravatar_id": "somehexcode",
  "url": "https://api.github.com/users/octocat",
  "name": "' . $name . '",
  "company": "GitHub",
  "blog": "https://github.com/blog",
  "location": "San Francisco",
  "email": "octocat@github.com",
  "hireable": false,
  "bio": "There once was...",
  "public_repos": 2,
  "public_gists": 1,
  "followers": 20,
  "following": 0,
  "html_url": "https://github.com/octocat",
  "created_at": "2008-01-14T04:33:35Z",
  "type": "User",
  "total_private_repos": 100,
  "owned_private_repos": 100,
  "private_gists": 81,
  "disk_usage": 10000,
  "collaborators": 8,
  "plan": {
    "name": "Medium",
    "space": 400,
    "collaborators": 10,
    "private_repos": 20
  }
}';

		$input = json_encode(
			array(
				'name'     => $name,
				'email'    => $email,
				'blog'     => $blog,
				'company'  => $company,
				'location' => $location,
				'hireable' => $hireable,
				'bio'      => $bio
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/user', $input, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit($name, $email, $blog, $company, $location, $hireable, $bio),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getUsers method
	 *
	 * @expectedException \DomainException
	 *
	 * @return void
	 */
	public function testEditFailure()
	{
		$name     = 'monalisa octocat';
		$email    = 'octocat@github.com';
		$blog     = 'https =>//github.com/blog';
		$company  = 'GitHub';
		$location = 'San Francisco';
		$hireable = true;
		$bio      = 'There once...';

		$this->response->code = 404;
		$this->response->body = $this->errorString;

		$input = json_encode(
			array(
				'name'     => $name,
				'email'    => $email,
				'blog'     => $blog,
				'company'  => $company,
				'location' => $location,
				'hireable' => $hireable,
				'bio'      => $bio
			)
		);

		$this->client->expects($this->once())
			->method('patch')
			->with('/user', $input, 0, 0)
			->will($this->returnValue($this->response));

		// $this->object->edit($name, $email, $blog, $company, $location, $hireable, $bio);

		$this->assertThat(
			$this->object->edit($name, $email, $blog, $company, $location, $hireable, $bio),
			$this->equalTo(json_decode($this->response->body))
		);
	}
}
