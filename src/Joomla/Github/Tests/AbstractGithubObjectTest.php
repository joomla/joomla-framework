<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Registry\Registry;

require_once __DIR__ . '/stubs/JGithubObjectMock.php';

/**
 * Test class for Joomla\Github\Object.
 *
 * @since  1.0
 */
class AbstractGithubObjectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \Joomla\Github\Http  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    \Joomla\Github\Tests\ObjectMock  Object under test.
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

		$this->object = new ObjectMock($this->options, $this->client);
	}

	/**
	 * Data provider method for the fetchUrl method tests.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function fetchUrlData()
	{
		return array(
			'Standard github - no pagination data' => array('https://api.github.com', '/gists', 0, 0, 'https://api.github.com/gists'),
			'Enterprise github - no pagination data' => array('https://mygithub.com', '/gists', 0, 0, 'https://mygithub.com/gists'),
			'Standard github - page 3' => array('https://api.github.com', '/gists', 3, 0, 'https://api.github.com/gists?page=3'),
			'Enterprise github - page 3, 50 per page' => array('https://mygithub.com', '/gists', 3, 50, 'https://mygithub.com/gists?page=3&per_page=50'),
		);
	}

	/**
	 * Tests the fetchUrl method
	 *
	 * @param   string   $apiUrl    @todo
	 * @param   string   $path      @todo
	 * @param   integer  $page      @todo
	 * @param   integer  $limit     @todo
	 * @param   string   $expected  @todo
	 *
	 * @return  void
	 *
	 * @since        1.0
	 * @dataProvider fetchUrlData
	 */
	public function testFetchUrl($apiUrl, $path, $page, $limit, $expected)
	{
		$this->options->set('api.url', $apiUrl);

		$this->assertThat(
			$this->object->fetchUrl($path, $page, $limit),
			$this->equalTo($expected)
		);
	}

	/**
	 * Tests the fetchUrl method with basic authentication data
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFetchUrlBasicAuth()
	{
		$this->options->set('api.url', 'https://api.github.com');

		$this->options->set('api.username', 'MyTestUser');
		$this->options->set('api.password', 'MyTestPass');

		$this->assertThat(
			$this->object->fetchUrl('/gists', 0, 0),
			$this->equalTo('https://MyTestUser:MyTestPass@api.github.com/gists')
		);
	}

	/**
	 * Tests the fetchUrl method using an oAuth token.
	 *
	 * @return void
	 */
	public function testFetchUrlToken()
	{
		$this->options->set('api.url', 'https://api.github.com');

		$this->options->set('gh.token', 'MyTestToken');

		$this->assertThat(
			$this->object->fetchUrl('/gists', 0, 0),
			$this->equalTo('https://api.github.com/gists?access_token=MyTestToken')
		);
	}
}
