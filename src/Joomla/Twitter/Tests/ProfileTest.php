<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Profile;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Profile.
 *
 * @since  1.0
 */
class ProfileTest extends TwitterTestCase
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
	protected $rateLimit = '{"resources": {"account": {
			"/account/update_profile": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/account/update_profile_background_image": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/account/update_profile_image": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/account/update_profile_colors": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/account/settings": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
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

		$this->object = new Profile($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the updateProfile method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testUpdateProfile()
	{
		$name = 'testUser';
		$url = 'www.example.com/url';
		$location = 'San Francisco, CA';
		$description = 'Flipped my wig at age 22 and it never grew back. Also: I work at Twitter.';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['name'] = $name;
		$data['url'] = $url;
		$data['location'] = $location;
		$data['description'] = $description;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/account/update_profile.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateProfile($name, $url, $location, $description, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateProfile method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException DomainException
	 */
	public function testUpdateProfileFailure()
	{
		$name = 'testUser';
		$url = 'www.example.com/url';
		$location = 'San Francisco, CA';
		$description = 'Flipped my wig at age 22 and it never grew back. Also: I work at Twitter.';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['name'] = $name;
		$data['url'] = $url;
		$data['location'] = $location;
		$data['description'] = $description;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/account/update_profile.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->updateProfile($name, $url, $location, $description, $entities, $skip_status);
	}

	/**
	 * Tests the updateProfileBackgroundImage method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUpdateProfileBackgroundImage()
	{
		$image = 'path/to/source';
		$tile = true;
		$entities = true;
		$skip_status = true;
		$use = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data['image'] = "@{$image}";
		$data['tile'] = $tile;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;
		$data['use'] = $use;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_background_image.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateProfileBackgroundImage($image, $tile, $entities, $skip_status, $use),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateProfileBackgroundImage method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testUpdateProfileBackgroundImageFailure()
	{
		$image = 'path/to/source';
		$tile = true;
		$entities = true;
		$skip_status = true;
		$use = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data['image'] = "@{$image}";
		$data['tile'] = $tile;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;
		$data['use'] = $use;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_background_image.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateProfileBackgroundImage($image, $tile, $entities, $skip_status, $use);
	}

	/**
	 * Tests the updateProfileImage method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUpdateProfileImage()
	{
		$image = 'path/to/source';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data['image'] = "@{$image}";
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_image.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateProfileImage($image, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateProfileImage method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testUpdateProfileImageFailure()
	{
		$image = 'path/to/source';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data['image'] = "@{$image}";
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_image.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateProfileImage($image, $entities, $skip_status);
	}

	/**
	 * Tests the updateProfileColors method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUpdateProfileColors()
	{
		$background = 'C0DEED ';
		$link = '0084B4';
		$sidebar_border = '0084B4';
		$sidebar_fill = 'DDEEF6';
		$text = '333333';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data['profile_background_color'] = $background;
		$data['profile_link_color'] = $link;
		$data['profile_sidebar_border_color'] = $sidebar_border;
		$data['profile_sidebar_fill_color'] = $sidebar_fill;
		$data['profile_text_color'] = $text;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_colors.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateProfileColors($background, $link, $sidebar_border, $sidebar_fill, $text, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateProfileColors method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testUpdateProfileColorsFailure()
	{
		$background = 'C0DEED ';
		$link = '0084B4';
		$sidebar_border = '0084B4';
		$sidebar_fill = 'DDEEF6';
		$text = '333333';
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data['profile_background_color'] = $background;
		$data['profile_link_color'] = $link;
		$data['profile_sidebar_border_color'] = $sidebar_border;
		$data['profile_sidebar_fill_color'] = $sidebar_fill;
		$data['profile_text_color'] = $text;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$this->client->expects($this->at(1))
			->method('post')
			->with('/account/update_profile_colors.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateProfileColors($background, $link, $sidebar_border, $sidebar_fill, $text, $entities, $skip_status);
	}

	/**
	 * Tests the getSettings method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetSettings()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->at(1))
			->method('get')
			->with('/account/settings.json')
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSettings($this->oauth),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSettings method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetSettingsFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "account"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$this->client->expects($this->at(1))
			->method('get')
			->with('/account/settings.json')
			->will($this->returnValue($returnData));

		$this->object->getSettings($this->oauth);
	}

	/**
	 * Tests the updateSettings method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUpdateSettings()
	{
		$location = 1;
		$sleep_time = true;
		$start_sleep = 10;
		$end_sleep = 14;
		$time_zone = 'Europe/Copenhagen';
		$lang = 'en';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request parameters.
		$data['trend_location_woeid '] = $location;
		$data['sleep_time_enabled'] = $sleep_time;
		$data['start_sleep_time'] = $start_sleep;
		$data['end_sleep_time'] = $end_sleep;
		$data['time_zone'] = $time_zone;
		$data['lang'] = $lang;

		$this->client->expects($this->once())
			->method('post')
			->with('/account/settings.json', $data)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->updateSettings($location, $sleep_time, $start_sleep, $end_sleep, $time_zone, $lang),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the updateSettings method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testUpdateSettingsFailure()
	{
		$location = 1;
		$sleep_time = true;
		$start_sleep = 10;
		$end_sleep = 14;
		$time_zone = 'Europe/Copenhagen';
		$lang = 'en';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request parameters.
		$data['trend_location_woeid '] = $location;
		$data['sleep_time_enabled'] = $sleep_time;
		$data['start_sleep_time'] = $start_sleep;
		$data['end_sleep_time'] = $end_sleep;
		$data['time_zone'] = $time_zone;
		$data['lang'] = $lang;

		$this->client->expects($this->once())
			->method('post')
			->with('/account/settings.json', $data)
			->will($this->returnValue($returnData));

		$this->object->updateSettings($location, $sleep_time, $start_sleep, $end_sleep, $time_zone, $lang);
	}
}
