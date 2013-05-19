<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Github;
use Joomla\Github\Http;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\Github\Github.
 *
 * @since  1.0
 */
class GithubTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Github  Object under test.
	 * @since  1.0
	 */
	protected $object;

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

		$this->options = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));

		$this->object = new Github($this->options, $this->client);
	}

	/**
	 * Tests the magic __get method - forks
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetForks()
	{
		$this->assertThat(
			$this->object->repositories->forks,
			$this->isInstanceOf('Joomla\Github\Package\Repositories\Forks')
		);
	}

	/**
	 * Tests the magic __get method - commits
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetCommits()
	{
		$this->assertThat(
			$this->object->repositories->commits,
			$this->isInstanceOf('Joomla\Github\Package\Repositories\Commits')
		);
	}

	/**
	 * Tests the magic __get method - statuses
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetStatuses()
	{
		$this->assertThat(
			$this->object->repositories->statuses,
			$this->isInstanceOf('Joomla\Github\Package\Repositories\Statuses')
		);
	}

	/**
	 * Tests the magic __get method - hooks
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetHooks()
	{
		$this->assertThat(
			$this->object->repositories->hooks,
			$this->isInstanceOf('Joomla\Github\Package\Repositories\Hooks')
		);
	}

	/**
	 * Tests the magic __get method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  \InvalidArgumentException
	 */
	public function test__GetFailure()
	{
		$tmp = $this->object->other;
	}

	/**
	 * Tests the setOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$this->assertThat(
			$this->options->get('api.url'),
			$this->equalTo('https://example.com/settest')
		);
	}

	/**
	 * Tests the getOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOption()
	{
		$this->options->set('api.url', 'https://example.com/gettest');

		$this->assertThat(
			$this->object->getOption('api.url', 'https://example.com/gettest'),
			$this->equalTo('https://example.com/gettest')
		);
	}
}
