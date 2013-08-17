<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Search;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Search.
 *
 * @since  1.0
 */
class SearchTest extends TwitterTestCase
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
	protected $searchRateLimit = '{"resources": {"search": {
			"/search/tweets": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $savedSearchesRateLimit = '{"resources": {"saved_searches": {
			"/saved_searches/list": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/saved_searches/show/:id": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/saved_searches/destroy/:id": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
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

		$this->object = new Search($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the search method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSearch()
	{
		$query = '@noradio';
		$callback = 'callback';
		$geocode = '37.781157,-122.398720,1mi';
		$lang = 'fr';
		$locale = 'ja';
		$result_type = 'recent';
		$count = 10;
		$until = '2010-03-28';
		$since_id = 12345;
		$max_id = 54321;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->searchRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "search"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		$data['q'] = rawurlencode($query);
		$data['callback'] = $callback;
		$data['geocode'] = $geocode;
		$data['lang'] = $lang;
		$data['locale'] = $locale;
		$data['result_type'] = $result_type;
		$data['count'] = $count;
		$data['until'] = $until;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('/search/tweets.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->search($query, $callback, $geocode, $lang, $locale, $result_type, $count, $until, $since_id, $max_id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the search method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testSearchFailure()
	{
		$query = '@noradio';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->searchRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "search"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['q'] = rawurlencode($query);

		$path = $this->object->fetchUrl('/search/tweets.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->search($query);
	}

	/**
	 * Tests the getSavedSearches method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetSavedSearches()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/saved_searches/list.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSavedSearches(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSavedSearches method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetSavedSearchesFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/saved_searches/list.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSavedSearches();
	}

	/**
	 * Tests the getSavedSearchesById method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetSavedSearchesById()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/saved_searches/show/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSavedSearchesById($id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSavedSearchesById method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testGetSavedSearchesByIdFailure()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/saved_searches/show/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSavedSearchesById($id);
	}

	/**
	 * Tests the createSavedSearch method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateSavedSearch()
	{
		$query = 'test';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request data
		$data['query'] = $query;

		$path = $this->object->fetchUrl('/saved_searches/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createSavedSearch($query),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createSavedSearch method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testCreateSavedSearchFailure()
	{
		$query = 'test';

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set POST request data
		$data['query'] = $query;

		$path = $this->object->fetchUrl('/saved_searches/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->createSavedSearch($query);
	}

	/**
	 * Tests the deleteSavedSearch method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteSavedSearch()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/saved_searches/destroy/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteSavedSearch($id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteSavedSearch method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @expectedException DomainException
	 */
	public function testDeleteSavedSearchFailure()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->savedSearchesRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "saved_searches"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/saved_searches/destroy/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->deleteSavedSearch($id);
	}
}
