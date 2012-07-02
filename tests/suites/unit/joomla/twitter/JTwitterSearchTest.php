<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/twitter/twitter.php';
require_once JPATH_PLATFORM . '/joomla/twitter/http.php';
require_once JPATH_PLATFORM . '/joomla/twitter/search.php';

/**
 * Test class for JTwittersearch.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Twitter
 *
 * @since       12.1
 */
class JTwitterSearchTest extends TestCase
{
	/**
	 * @var    JRegistry  Options for the Twitter object.
	 * @since  12.1
	 */
	protected $options;

	/**
	 * @var    JTwitterHttp  Mock client object.
	 * @since  12.1
	 */
	protected $client;

	/**
	 * @var    JTwitterSearch  Object under test.
	 * @since  12.1
	 */
	protected $object;

	/**
	 * @var    JTwitterOAuth  Authentication object for the Twitter object.
	 * @since  12.1
	 */
	protected $oauth;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.1
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON Twitter error message.
	 * @since  12.1
	 */
	protected $twitterErrorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.1
	 */
	protected $rateLimit = '{"remaining_hits":150, "reset_time":"Mon Jun 25 17:20:53 +0000 2012"}';

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
		$key = "lIio7RcLe5IASG5jpnZrA";
		$secret = "dl3BrWij7LT04NUpy37BRJxGXpWgjNvMrneuQ11EveE";
		$my_url = "http://127.0.0.1/gsoc/joomla-platform/twitter_test.php";

		$this->options = new JRegistry;
		$this->client = $this->getMock('JTwitterHttp', array('get', 'post', 'delete', 'put'));

		$this->object = new JTwitterSearch($this->options, $this->client);
		$this->oauth = new JTwitterOAuth($key, $secret, $my_url, $this->client);
		$this->oauth->setToken($key, $secret);
	}

	/**
	 * Tests the search method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testSearch()
	{
		$query = '@noradio';
		$callback = 'callback';
		$geocode = '37.781157,-122.398720,1mi';
		$lang = 'fr';
		$locale = 'ja';
		$page = 1;
		$result_type = 'recent';
		$rpp = 100;
		$show_user = true;
		$until = '2010-03-28';
		$since_id = 12345;
		$max_id = 54321;
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
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
		$data['page'] = $page;
		$data['result_type'] = $result_type;
		$data['rpp'] = $rpp;
		$data['show_user'] = $show_user;
		$data['until'] = $until;
		$data['since_id'] = $since_id;
		$data['max_id'] = $max_id;
		$data['include_entities'] = $entities;

		$path = $this->object->fetchUrl('http://search.twitter.com/search.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->search($query, $callback, $geocode, $lang, $locale, $page, $result_type, $rpp, $show_user, $until, $since_id, $max_id, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the search method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException DomainException
	 */
	public function testSearchFailure()
	{
		$query = '@noradio';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		$data['q'] = rawurlencode($query);

		$path = $this->object->fetchUrl('http://search.twitter.com/search.json', $data);

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
	 * @since   12.1
	 */
	public function testGetSavedSearches()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/1/saved_searches.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSavedSearches($this->oauth),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSavedSearches method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException DomainException
	 */
	public function testGetSavedSearchesFailure()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/1/saved_searches.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSavedSearches($this->oauth);
	}

	/**
	 * Tests the getSavedSearchesById method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testGetSavedSearchesById()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/1/saved_searches/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getSavedSearchesById($this->oauth, $id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getSavedSearchesById method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException DomainException
	 */
	public function testGetSavedSearchesByIdFailure()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$this->client->expects($this->at(0))
		->method('get')
		->with('/1/account/rate_limit_status.json')
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/1/saved_searches/' . $id . '.json');

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getSavedSearchesById($this->oauth, $id);
	}

	/**
	 * Tests the createSavedSearch method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testCreateSavedSearch()
	{
		$query = 'test';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set POST request data
		$data['query'] = $query;

		$path = $this->object->fetchUrl('/1/saved_searches/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->createSavedSearch($this->oauth, $query),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createSavedSearch method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
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

		$path = $this->object->fetchUrl('/1/saved_searches/create.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->createSavedSearch($this->oauth, $query);
	}

	/**
	 * Tests the deleteSavedSearch method
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function testDeleteSavedSearch()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->object->fetchUrl('/1/saved_searches/destroy/' . $id . '.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->deleteSavedSearch($this->oauth, $id),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the deleteSavedSearch method - failure
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @expectedException DomainException
	 */
	public function testDeleteSavedSearchFailure()
	{
		$id = 12345;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$path = $this->object->fetchUrl('/1/saved_searches/destroy/' . $id . '.json');

		$this->client->expects($this->once())
		->method('post')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->deleteSavedSearch($this->oauth, $id);
	}
}
