<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\Facebook;
use Joomla\Test\TestHelper;

require_once __DIR__ . '/case/FacebookTestCase.php';

/**
 * Test class for Joomla\Facebook\Facebook.
 *
 * @since  1.0
 */
class FacebookTest extends FacebookTestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Facebook($this->oauth, $this->options, $this->client);
	}

	/**
	 * Tests the magic __get method - user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetUser()
	{
		$this->assertThat(
			$this->object->user,
			$this->isInstanceOf('Joomla\\Facebook\\User')
		);
	}

	/**
	 * Tests the magic __get method - status
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetStatus()
	{
		$this->assertThat(
			$this->object->status,
			$this->isInstanceOf('Joomla\\Facebook\\Status')
		);
	}

	/**
	 * Tests the magic __get method - checkin
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetCheckin()
	{
		$this->assertThat(
			$this->object->checkin,
			$this->isInstanceOf('Joomla\\Facebook\\Checkin')
		);
	}

	/**
	 * Tests the magic __get method - event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetEvent()
	{
		$this->assertThat(
			$this->object->event,
			$this->isInstanceOf('Joomla\\Facebook\\Event')
		);
	}

	/**
	 * Tests the magic __get method - group
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetGroup()
	{
		$this->assertThat(
			$this->object->group,
			$this->isInstanceOf('Joomla\\Facebook\\Group')
		);
	}

	/**
	 * Tests the magic __get method - link
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetLink()
	{
		$this->assertThat(
			$this->object->link,
			$this->isInstanceOf('Joomla\\Facebook\\Link')
		);
	}

	/**
	 * Tests the magic __get method - note
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetNote()
	{
		$this->assertThat(
			$this->object->note,
			$this->isInstanceOf('Joomla\\Facebook\\Note')
		);
	}

	/**
	 * Tests the magic __get method - post
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetPost()
	{
		$this->assertThat(
			$this->object->post,
			$this->isInstanceOf('Joomla\\Facebook\\Post')
		);
	}

	/**
	 * Tests the magic __get method - comment
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetComment()
	{
		$this->assertThat(
			$this->object->comment,
			$this->isInstanceOf('Joomla\\Facebook\\Comment')
		);
	}

	/**
	 * Tests the magic __get method - photo
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetPhoto()
	{
		$this->assertThat(
			$this->object->photo,
			$this->isInstanceOf('Joomla\\Facebook\\Photo')
		);
	}

	/**
	 * Tests the magic __get method - video
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetVideo()
	{
		$this->assertThat(
			$this->object->video,
			$this->isInstanceOf('Joomla\\Facebook\\Video')
		);
	}

	/**
	 * Tests the magic __get method - album
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetAlbum()
	{
		$this->assertThat(
			$this->object->album,
			$this->isInstanceOf('Joomla\\Facebook\\Album')
		);
	}

	/**
	 * Tests the magic __get method - other (non existent)
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
