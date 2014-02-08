<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Authorization;
use Joomla\Registry\Registry;

/**
 * Test class for Authorization.
 *
 * @since  1.0
 */
class AuthorizationsTest extends \PHPUnit_Framework_TestCase
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
	 * @var Authorization
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
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

		$this->options  = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Authorization($this->options, $this->client);
	}

	/**
	 * Tests the createAuthorisation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$authorisation = new \stdClass;
		$authorisation->scopes = array('public_repo');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('post')
			->with('/authorizations', json_encode($authorisation))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create(array('public_repo'), 'My test app', 'http://www.joomla.org'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createAuthorisation method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$authorisation = new \stdClass;
		$authorisation->scopes = array('public_repo');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('post')
			->with('/authorizations', json_encode($authorisation))
			->will($this->returnValue($this->response));

		try
		{
			$this->object->create(array('public_repo'), 'My test app', 'http://www.joomla.org');
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the deleteAuthorisation method
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
			->with('/authorizations/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete(42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteAuthorisation method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/authorizations/42')
			->will($this->returnValue($this->response));

		try
		{
			$this->object->delete(42);
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the editAuthorisation method - Add scopes
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditAddScopes()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$authorisation = new \stdClass;
		$authorisation->add_scopes = array('public_repo', 'gist');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('patch')
			->with('/authorizations/42', json_encode($authorisation))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(42, array(), array('public_repo', 'gist'), array(), 'My test app', 'http://www.joomla.org'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editAuthorisation method - Remove scopes
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditRemoveScopes()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$authorisation = new \stdClass;
		$authorisation->remove_scopes = array('public_repo', 'gist');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('patch')
			->with('/authorizations/42', json_encode($authorisation))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(42, array(), array(), array('public_repo', 'gist'), 'My test app', 'http://www.joomla.org'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editAuthorisation method - Scopes param
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditScopes()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$authorisation = new \stdClass;
		$authorisation->scopes = array('public_repo', 'gist');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('patch')
			->with('/authorizations/42', json_encode($authorisation))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(42, array('public_repo', 'gist'), array(), array(), 'My test app', 'http://www.joomla.org'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editAuthorisation method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$authorisation = new \stdClass;
		$authorisation->add_scopes = array('public_repo', 'gist');
		$authorisation->note = 'My test app';
		$authorisation->note_url = 'http://www.joomla.org';

		$this->client->expects($this->once())
			->method('patch')
			->with('/authorizations/42', json_encode($authorisation))
			->will($this->returnValue($this->response));

		try
		{
			$this->object->edit(42, array(), array('public_repo', 'gist'), array(), 'My test app', 'http://www.joomla.org');
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the editAuthorisation method - too many scope params
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \RuntimeException
	 */
	public function testEditTooManyScopes()
	{
		$this->object->edit(42, array(), array('public_repo', 'gist'), array('public_repo', 'gist'), 'My test app', 'http://www.joomla.org');
	}

	/**
	 * Tests the getAuthorisation method
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
			->with('/authorizations/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getAuthorisation method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/authorizations/42')
			->will($this->returnValue($this->response));

		$this->object->get(42);
	}

	/**
	 * Tests the getAuthorisations method
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
			->with('/authorizations')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getAuthorisations method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetListFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/authorizations')
			->will($this->returnValue($this->response));

		$this->object->getList();
	}

	/**
	 * Tests the getRateLimit method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetRateLimit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/rate_limit')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getRateLimit(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getRateLimit method for an unlimited user.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetRateLimit_unlimited()
	{
		$this->response->code = 404;
		$this->response->body = '';

		$this->client->expects($this->once())
					 ->method('get')
					 ->with('/rate_limit')
					 ->will($this->returnValue($this->response));

		$this->assertFalse($this->object->getRateLimit()->limit, 'The limit should be false for unlimited');
	}

	/**
	 * Tests the getRateLimit method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetRateLimitFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/rate_limit')
			->will($this->returnValue($this->response));

		$this->object->getRateLimit();
	}

	/**
	 * Tests the getAuthorizationLink method
	 *
	 * @return  void
	 */
	public function testGetAuthorizationLink()
	{
		$this->response->code = 200;
		$this->response->body = 'https://github.com/login/oauth/authorize?client_id=12345'
			. '&redirect_uri=aaa&scope=bbb&state=ccc';

		$this->assertThat(
			$this->object->getAuthorizationLink('12345', 'aaa', 'bbb', 'ccc'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the requestToken method
	 *
	 * @return  void
	 */
	public function testRequestToken()
	{
		$this->response->code = 200;
		$this->response->body = '';

		$this->client->expects($this->once())
			->method('post')
			->with('https://github.com/login/oauth/access_token')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->requestToken('12345', 'aaa', 'bbb', 'ccc'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the requestTokenJson method
	 *
	 * @return  void
	 */
	public function testRequestTokenJson()
	{
		$this->response->code = 200;
		$this->response->body = '';

		$this->client->expects($this->once())
			->method('post')
			->with('https://github.com/login/oauth/access_token')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->requestToken('12345', 'aaa', 'bbb', 'ccc', 'json'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the requestTokenXml method
	 *
	 * @return  void
	 */
	public function testRequestTokenXml()
	{
		$this->response->code = 200;
		$this->response->body = '';

		$this->client->expects($this->once())
			->method('post')
			->with('https://github.com/login/oauth/access_token')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->requestToken('12345', 'aaa', 'bbb', 'ccc', 'xml'),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the requestTokenInvalidFormat method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testRequestTokenInvalidFormat()
	{
		$this->response->code = 200;
		$this->response->body = '';

		$this->object->requestToken('12345', 'aaa', 'bbb', 'ccc', 'invalid');
	}
}
