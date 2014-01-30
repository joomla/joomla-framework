<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Test\TestHelper;
use Joomla\Twitter\Twitter;
use Joomla\Twitter\Block;
use Joomla\Twitter\Directmessages;
use Joomla\Twitter\Favorites;
use Joomla\Twitter\Friends;
use Joomla\Twitter\Help;
use Joomla\Twitter\Lists;
use Joomla\Twitter\OAuth;
use Joomla\Twitter\Places;
use Joomla\Twitter\Profile;
use Joomla\Twitter\Search;
use Joomla\Twitter\Statuses;
use Joomla\Twitter\Trends;
use Joomla\Twitter\Users;
use \DomainException;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter.
 *
 * @since  1.0
 */
class TwitterTest extends TwitterTestCase
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

		$this->object = new Twitter($this->oauth, $this->options, $this->client);
	}

	/**
	 * Tests the magic __get method - friends
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetFriends()
	{
		$this->assertThat(
			$this->object->friends,
			$this->isInstanceOf('Joomla\\Twitter\\Friends')
		);
	}

	/**
	 * Tests the magic __get method - help
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetHelp()
	{
		$this->assertThat(
			$this->object->help,
			$this->isInstanceOf('Joomla\\Twitter\\Help')
		);
	}

	/**
	 * Tests the magic __get method - other (non existant)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function test__GetOther()
	{
		$this->object->other;
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
			$this->object->statuses,
			$this->isInstanceOf('Joomla\\Twitter\\Statuses')
		);
	}

	/**
	 * Tests the magic __get method - users
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetUsers()
	{
		$this->assertThat(
			$this->object->users,
			$this->isInstanceOf('Joomla\\Twitter\\Users')
		);
	}

	/**
	 * Tests the magic __get method - search
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetSearch()
	{
		$this->assertThat(
			$this->object->search,
			$this->isInstanceOf('Joomla\\Twitter\\Search')
		);
	}

	/**
	 * Tests the magic __get method - favorites
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetFavorites()
	{
		$this->assertThat(
			$this->object->favorites,
			$this->isInstanceOf('Joomla\\Twitter\\Favorites')
		);
	}

	/**
	 * Tests the magic __get method - directMessages
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetDirectMessages()
	{
		$this->assertThat(
			$this->object->directMessages,
			$this->isInstanceOf('Joomla\\Twitter\\Directmessages')
		);
	}

	/**
	 * Tests the magic __get method - lists
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetLists()
	{
		$this->assertThat(
			$this->object->lists,
			$this->isInstanceOf('Joomla\\Twitter\\Lists')
		);
	}

	/**
	 * Tests the magic __get method - places
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetPlaces()
	{
		$this->assertThat(
			$this->object->places,
			$this->isInstanceOf('Joomla\\Twitter\\Places')
		);
	}

	/**
	 * Tests the magic __get method - trends
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetTrends()
	{
		$this->assertThat(
			$this->object->trends,
			$this->isInstanceOf('Joomla\\Twitter\\Trends')
		);
	}

	/**
	 * Tests the magic __get method - block
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetBlock()
	{
		$this->assertThat(
			$this->object->block,
			$this->isInstanceOf('Joomla\\Twitter\\Block')
		);
	}

	/**
	 * Tests the magic __get method - profile
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetProfile()
	{
		$this->assertThat(
			$this->object->profile,
			$this->isInstanceOf('Joomla\\Twitter\\Profile')
		);
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
			$this->object,
			'options',
			array(
				'api.url' => 'https://example.com/gettest'
			)
		);

		$this->assertThat(
			$this->object->getOption('api.url'),
			$this->equalTo('https://example.com/gettest')
		);
	}
}
