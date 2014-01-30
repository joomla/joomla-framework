<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Favorites;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Favorites.
 *
 * @since  1.0
 */
class FavoritesTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $rateLimit = '{"resources": {"favorites": {
			"/favorites/list": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

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

		$this->object = new Favorites($this->options, $this->client, $this->oauth);
	}

	/**
	 * Provides test data for request format detection.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function seedUser()
	{
		// User ID or screen name
		return array(
			array(234654235457),
			array('testUser'),
			array(null)
			);
	}

	/**
	 * Tests the getFavorites method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testGetFavorites($user)
	{
		$count = 10;
		$since_id = 12345;
		$max_id = 54321;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "favorites"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}

		$data['count'] = $count;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/favorites/list.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFavorites($user, $count, $since_id, $max_id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFavorites method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetFavoritesFailure($user)
	{
		$count = 10;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "favorites"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}

		$data['count'] = $count;

		$path = $this->object->fetchUrl('/favorites/list.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFavorites($user, $count);
	}

	/**
	 * Tests the createFavorites method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateFavorites()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/favorites/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createFavorites($id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createFavorites method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testCreateFavoritesFailure()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/favorites/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->createFavorites($id, $entities);
	}

	/**
	 * Tests the deleteFavorites method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteFavorites()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/favorites/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteFavorites($id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteFavorites method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testDeleteFavoritesFailure()
	{
		$id = 12345;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['id'] = $id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/favorites/destroy.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->deleteFavorites($id, $entities);
	}
}
