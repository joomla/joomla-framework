<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories\Statistics;
use Joomla\Registry\Registry;

/**
 * Test class for Statistics.
 *
 * @since  1.0
 */
class StatisticsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Registry\Registry  Options for the GitHub object.
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
	 * @var    Statistics  Object under test.
	 * @since  11.4
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  11.4
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  11.4
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

		$this->object = new Statistics($this->options, $this->client);
	}

	/**
	 * Tests the getListContributors method.
	 *
	 * @return  void
	 */
	public function testContributors()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/contributors')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListContributors('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getActivityData method.
	 *
	 * @return  void
	 */
	public function testActivity()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/commit_activity')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getActivityData('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCodeFrequency method.
	 *
	 * @return  void
	 */
	public function testFrequency()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/code_frequency')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCodeFrequency('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getParticipation method.
	 *
	 * @return  void
	 */
	public function testParticipation()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/participation')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getParticipation('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getPunchCard method.
	 *
	 * @return  void
	 */
	public function testPunchCard()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/punch_card')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getPunchCard('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the ProcessResponse method with failure.
	 *
	 * @expectedException \DomainException
	 * @return  void
	 */
	public function testProcessResponse202()
	{
		$this->response->code = 202;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-framework/stats/punch_card')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getPunchCard('joomla', 'joomla-framework'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
