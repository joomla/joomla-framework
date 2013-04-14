<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Issues\Milestones;
use Joomla\Registry\Registry;

/**
 * Test class for the GitHub API package.
 *
 * @since  1.0
 */
class MilestonesTest extends \PHPUnit_Framework_TestCase
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
	 * @var    Milestones  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

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
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options  = new Registry;
		$this->client   = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Milestones($this->options, $this->client);
	}

	/**
	 * Test...
	 *
	 * @param   string  $name  The method name.
	 *
	 * @return string
	 */
	protected function xxxgetMethod($name)
	{
		$class = new ReflectionClass('JGithubMilestones');
		$method = $class->getMethod($name);
		$method->setAccessible(true);

		return $method;
	}

	/**
	 * Tests the create method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$milestone = new \stdClass;
		$milestone->title = 'My Milestone';
		$milestone->state = 'open';
		$milestone->description = 'This milestone is impossible';
		$milestone->due_on = '2012-12-25T20:09:31Z';

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/milestones', json_encode($milestone))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 'My Milestone', 'open', 'This milestone is impossible', '2012-12-25T20:09:31Z'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the create method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testCreateFailure()
	{
		$this->response->code = 501;
		$this->response->body = $this->errorString;

		$milestone = new \stdClass;
		$milestone->title = 'My Milestone';
		$milestone->state = 'open';
		$milestone->description = 'This milestone is impossible';
		$milestone->due_on = '2012-12-25T20:09:31Z';

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/milestones', json_encode($milestone))
			->will($this->returnValue($this->response));

		$this->object->create('joomla', 'joomla-platform', 'My Milestone', 'open', 'This milestone is impossible', '2012-12-25T20:09:31Z');
	}

	/**
	 * Tests the edit method
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$milestone = new \stdClass;
		$milestone->state = 'closed';

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/milestones/523', json_encode($milestone))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-platform', 523, null, 'closed'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the edit method with all parameters
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testEditAllParameters()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$milestone = new \stdClass;
		$milestone->title = 'This is the revised title.';
		$milestone->state = 'closed';
		$milestone->description = 'This describes it perfectly.';
		$milestone->due_on = '2012-12-25T20:09:31Z';

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/milestones/523', json_encode($milestone))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-platform', 523, 'This is the revised title.', 'closed', 'This describes it perfectly.',
				'2012-12-25T20:09:31Z'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the edit method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testEditFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$milestone = new \stdClass;
		$milestone->state = 'closed';

		$this->client->expects($this->once())
		->method('patch')
		->with('/repos/joomla/joomla-platform/milestones/523', json_encode($milestone))
		->will($this->returnValue($this->response));

		$this->object->edit('joomla', 'joomla-platform', 523, null, 'closed');
	}

	/**
	 * Tests the get method
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/milestones/523')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 523),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the get method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testGetFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/milestones/523')
			->will($this->returnValue($this->response));

		$this->object->get('joomla', 'joomla-platform', 523);
	}

	/**
	 * Tests the getList method
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('get')
		->with('/repos/joomla/joomla-platform/milestones?state=open&sort=due_date&direction=desc')
		->will($this->returnValue($this->response));

		$this->assertThat(
				$this->object->getList('joomla', 'joomla-platform'),
				$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getList method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testGetListFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
		->method('get')
		->with('/repos/joomla/joomla-platform/milestones?state=open&sort=due_date&direction=desc')
		->will($this->returnValue($this->response));

		$this->object->getList('joomla', 'joomla-platform');
	}

	/**
	 * Tests the delete method
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testDelete()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
		->method('delete')
		->with('/repos/joomla/joomla-platform/milestones/254')
		->will($this->returnValue($this->response));

		$this->object->delete('joomla', 'joomla-platform', 254);
	}

	/**
	 * Tests the delete method - failure
	 *
	 * @expectedException  \DomainException
	 *
	 * @return void
	 *
	 * @since  12.3
	 */
	public function testDeleteFailure()
	{
		$this->response->code = 504;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
		->method('delete')
		->with('/repos/joomla/joomla-platform/milestones/254')
		->will($this->returnValue($this->response));

		$this->object->delete('joomla', 'joomla-platform', 254);
	}
}
