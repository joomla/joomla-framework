<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Issues\Assignees;
use Joomla\Registry\Registry;

/**
 * Test class for the GitHub API package.
 *
 * @since  1.0
 */
class AssigneesTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Assignees  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var string
	 * @since  1.0
	 */
	protected $owner = 'joomla';

	/**
	 * @var string
	 * @since  1.0
	 */
	protected $repo = 'joomla-framework';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  1.0
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

		$this->object = new Assignees($this->options, $this->client);
	}

	/**
	 * Tests the getList method
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
	]';

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/' . $this->owner . '/' . $this->repo . '/assignees', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList($this->owner, $this->repo),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the getList method
	 * Response:
	 * If the given assignee login belongs to an assignee for the repository,
	 * a 204 header with no content is returned.
	 * Otherwise a 404 status code is returned.
	 *
	 * @return void
	 */
	public function testCheck()
	{
		$this->response->code = 204;
		$this->response->body = '';

		$assignee = 'elkuku';

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/' . $this->owner . '/' . $this->repo . '/assignees/' . $assignee, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->check($this->owner, $this->repo, $assignee),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the getList method with a negative response
	 * Response:
	 * If the given assignee login belongs to an assignee for the repository,
	 * a 204 header with no content is returned.
	 * Otherwise a 404 status code is returned.
	 *
	 * @return void
	 */
	public function testCheckNo()
	{
		$this->response->code = 404;
		$this->response->body = '';

		$assignee = 'elkuku';

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/' . $this->owner . '/' . $this->repo . '/assignees/' . $assignee, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->check($this->owner, $this->repo, $assignee),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the getList method with a negative response
	 * Response:
	 * If the given assignee login belongs to an assignee for the repository,
	 * a 204 header with no content is returned.
	 * Otherwise a 404 status code is returned.
	 *
	 * @expectedException \DomainException
	 *
	 * @return void
	 */
	public function testCheckException()
	{
		$this->response->code = 666;
		$this->response->body = '';

		$assignee = 'elkuku';

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/' . $this->owner . '/' . $this->repo . '/assignees/' . $assignee, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->check($this->owner, $this->repo, $assignee),
			$this->equalTo(false)
		);
	}
}
