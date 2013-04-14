<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories;
use Joomla\Registry\Registry;

/**
 * Test class for Repositories.
 *
 * @since  1.0
 */
class RepositoriesTest extends \PHPUnit_Framework_TestCase
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
	 * @var Repositories
	 */
	protected $object;

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
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Repositories($this->options, $this->client);
	}

	/**
	 * Tests the GetListOwn method.
	 *
	 * @return void
	 */
	public function testGetListOwn()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/user/repos?type=all&sort=full_name&direction=asc', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListOwn(),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListOwn method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListOwnInvalidType()
	{
		$this->object->getListOwn('INVALID');
	}

	/**
	 * Tests the GetListOwn method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListOwnInvalidSortField()
	{
		$this->object->getListOwn('all', 'INVALID');
	}

	/**
	 * Tests the GetListOwn method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListOwnInvalidSortOrder()
	{
		$this->object->getListOwn('all', 'full_name', 'INVALID');
	}

	/**
	 * Tests the GetListUser method.
	 *
	 * @return void
	 */
	public function testGetListUser()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/users/joomla/repos?type=all&sort=full_name&direction=asc', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListUser('joomla'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListUser method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListUserInvalidType()
	{
		$this->object->getListUser('joomla', 'INVALID');
	}

	/**
	 * Tests the GetListUser method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListUserInvalidSortField()
	{
		$this->object->getListUser('joomla', 'all', 'INVALID');
	}

	/**
	 * Tests the GetListUser method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListUserInvalidSortOrder()
	{
		$this->object->getListUser('joomla', 'all', 'full_name', 'INVALID');
	}

	/**
	 * Tests the GetListOrg method.
	 *
	 * @return void
	 */
	public function testGetListOrg()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/orgs/joomla/repos?type=all', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListOrg('joomla'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetList method.
	 *
	 * @return void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repositories', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList(),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the Create method.
	 *
	 * @return void
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/user/repos',
				'{"name":"joomla-test","description":"","homepage":"","private":false,"has_issues":false,'
					. '"has_wiki":false,"has_downloads":false,"team_id":0,"auto_init":false,"gitignore_template":""}',
				0, 0
			)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla-test'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the Get method.
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListOrg method.
	 *
	 * @expectedException \RuntimeException
	 *
	 * @return void
	 */
	public function testGetListOrgInvalidType()
	{
		$this->object->getListOrg('joomla', 'INVALID');
	}

	/**
	 * Tests the Edit method.
	 *
	 * @return void
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-test',
				'{"name":"joomla-test-1","description":"","homepage":"","private":false,"has_issues":false,'
					. '"has_wiki":false,"has_downloads":false,"default_branch":""}', 0
			)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-test', 'joomla-test-1'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListContributors method.
	 *
	 * @return void
	 */
	public function testGetListContributors()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/contributors', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListContributors('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListLanguages method.
	 *
	 * @return void
	 */
	public function testGetListLanguages()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/languages', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListLanguages('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListTeams method.
	 *
	 * @return void
	 */
	public function testGetListTeams()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/teams', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListTeams('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListTags method.
	 *
	 * @return void
	 */
	public function testGetListTags()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/tags', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListTags('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetListBranches method.
	 *
	 * @return void
	 */
	public function testGetListBranches()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/branches', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListBranches('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the GetBranch method.
	 *
	 * @return void
	 */
	public function testGetBranch()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-cms/branches/master', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getBranch('joomla', 'joomla-cms', 'master'),
			$this->equalTo(json_decode($this->response->body))
		);
	}

	/**
	 * Tests the Delete method.
	 *
	 * @return void
	 */
	public function testDelete()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-cms', 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete('joomla', 'joomla-cms'),
			$this->equalTo(json_decode($this->response->body))
		);
	}
}
