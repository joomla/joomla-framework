<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/twitter.php';

/**
 * Test class for JTwitter.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 * @since       12.1
 */
class JTwitterTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.1
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock http object.
	 * @since  12.1
	 */
	protected $client;

	/**
	 * @var    JTwitter  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitter($this->options, $this->client);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the magic __get method - friends
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetFriends()
	{
		$this->assertThat(
			$this->object->friends,
			$this->isInstanceOf('JTwitterFriends')
		);
	}

	/**
	 * Tests the magic __get method - help
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetHelp()
	{
		$this->assertThat(
			$this->object->help,
			$this->isInstanceOf('JTwitterHelp')
		);
	}

	/**
	 * Tests the magic __get method - other (non existant)
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetOther()
	{
		$this->assertThat(
			$this->object->other,
			$this->isNull()
		);
	}

	/**
	 * Tests the magic __get method - statuses
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetStatuses()
	{
		$this->assertThat(
			$this->object->statuses,
			$this->isInstanceOf('JTwitterStatuses')
		);
	}

	/**
	 * Tests the magic __get method - users
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetUsers()
	{
		$this->assertThat(
			$this->object->users,
			$this->isInstanceOf('JTwitterUsers')
		);
	}

	/**
	 * Tests the magic __get method - search
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetSearch()
	{
		$this->assertThat(
			$this->object->search,
			$this->isInstanceOf('JTwitterSearch')
		);
	}

	/**
	 * Tests the magic __get method - favorites
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetFavorites()
	{
		$this->assertThat(
			$this->object->favorites,
			$this->isInstanceOf('JTwitterFavorites')
		);
	}

	/**
	 * Tests the magic __get method - directMessages
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetDirectMessages()
	{
		$this->assertThat(
			$this->object->directMessages,
			$this->isInstanceOf('JTwitterDirectMessages')
		);
	}

	/**
	 * Tests the magic __get method - lists
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function test__GetLists()
	{
		$this->assertThat(
			$this->object->lists,
			$this->isInstanceOf('JTwitterLists')
		);
	}

	/**
	 * Tests the setOption method
	 *
	 * @return  void
	 *
	 * @since   12.1
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
	 * @since   12.1
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
