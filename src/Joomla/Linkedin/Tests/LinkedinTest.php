<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin\Tests;

use Joomla\Test\TestHelper;
use Joomla\Linkedin\Linkedin;
use Joomla\Linkedin\People;
use Joomla\Linkedin\Groups;
use Joomla\Linkedin\Communications;
use Joomla\Linkedin\Companies;
use Joomla\Linkedin\Stream;
use Joomla\Linkedin\Jobs;
use \DomainException;

require_once __DIR__ . '/case/LinkedinTestCase.php';

/**
 * Test class for Linkedin.
 *
 * @since  1.0
 */
class LinkedinTest extends LinkedinTestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Linkedin($this->oauth, $this->options, $this->client);
	}

	/**
	 * Tests the magic __get method - people
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetPeople()
	{
		$this->assertThat(
			$this->object->people,
			$this->isInstanceOf('Joomla\\Linkedin\\People')
		);
	}

	/**
	 * Tests the magic __get method - groups
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetGroups()
	{
		$this->assertThat(
			$this->object->groups,
			$this->isInstanceOf('Joomla\\Linkedin\\Groups')
		);
	}

	/**
	 * Tests the magic __get method - companies
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetCompanies()
	{
		$this->assertThat(
			$this->object->companies,
			$this->isInstanceOf('Joomla\\Linkedin\\Companies')
		);
	}

	/**
	 * Tests the magic __get method - jobs
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetJobs()
	{
		$this->assertThat(
			$this->object->jobs,
			$this->isInstanceOf('Joomla\\Linkedin\\Jobs')
		);
	}

	/**
	 * Tests the magic __get method - stream
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetStream()
	{
		$this->assertThat(
			$this->object->stream,
			$this->isInstanceOf('Joomla\\Linkedin\\Stream')
		);
	}

	/**
	 * Tests the magic __get method - communications
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetCommunications()
	{
		$this->assertThat(
			$this->object->communications,
			$this->isInstanceOf('Joomla\\Linkedin\\Communications')
		);
	}

	/**
	 * Tests the magic __get method - other (non existant)
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  \InvalidArgumentException
	 */
	public function test__GetOther()
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

		$value = TestHelper::getValue($this->object, 'options');

		$this->assertThat(
			$value['api.url'],
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
		TestHelper::setValue(
			$this->object, 'options', array(
				'api.url' => 'https://example.com/gettest'
			)
		);

		$this->assertThat(
			$this->object->getOption('api.url'),
			$this->equalTo('https://example.com/gettest')
		);
	}
}
