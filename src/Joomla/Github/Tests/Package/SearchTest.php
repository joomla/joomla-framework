<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Search;
use Joomla\Registry\Registry;

/**
 * Test class for Activity.
 *
 * @since  1.0
 */
class SearchTest extends \PHPUnit_Framework_TestCase
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
	 * @var Search
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
	 * @since   1.0
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options  = new Registry;
		$this->client   = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Search($this->options, $this->client);
	}

	/**
	 * Tests the issues method
	 *
	 * @return  void
	 */
	public function testIssues()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/legacy/issues/search/joomla/joomla-platform/open/github')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->issues('joomla', 'joomla-platform', 'open', 'github'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the issuesInvalidState method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testIssuesInvalidState()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->object->issues('joomla', 'joomla-platform', 'invalid', 'github');
	}

	/**
	 * Tests the repositories method
	 *
	 * @return  void
	 */
	public function testRepositories()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/legacy/repos/search/joomla')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->repositories('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the users method
	 *
	 * @return  void
	 */
	public function testUsers()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/legacy/user/search/joomla')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->users('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the email method
	 *
	 * @return  void
	 */
	public function testEmail()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/legacy/user/email/email@joomla')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->email('email@joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
