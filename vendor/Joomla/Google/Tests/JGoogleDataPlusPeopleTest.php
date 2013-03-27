<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

use Joomla\Google\Data\Plus\People;

require_once __DIR__ . '/case/GoogleTestCase.php';

/**
 * Test class for JGoogleDataPlusPeople.
 *
 * @since  1.0
 */
class JGoogleDataPlusPeopleTest extends GoogleTestCase
{
	/**
	 * @var    JGoogleDataPlusPeople  Object under test.
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
	protected $errorString = '{"error": {"message": "Generic Error."}}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new People($this->options, $this->auth);

		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('clientsecret', 'jeDs8rKw_jDJW8MMf-ff8ejs');
		$this->object->setOption('redirecturi', 'http://localhost/oauth');
	}

	/**
	 * Tests the auth method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testAuth()
	{
		$this->assertEquals($this->auth->authenticate(), $this->object->authenticate());
	}

	/**
	 * Tests the isauth method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testIsAuth()
	{
		$this->assertEquals($this->auth->isAuthenticated(), $this->object->isAuthenticated());
	}

	/**
	 * Tests the getPeople method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetPeople()
	{
		$id = '124346363456';
		$fields = 'aboutMe,birthday';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'people/' . $id . '?fields=' . $fields;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getPeople($id, $fields),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->getPeople($id, $fields),
			$this->equalTo(false)
		);
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
		$query = 'test search';
		$fields = 'aboutMe,birthday';
		$language = 'en-GB';
		$max = 5;
		$token = 'EAoaAA';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'people?query=' . urlencode($query) . '&fields=' . $fields . '&language=' . $language .
			'&maxResults=' . $max . '&pageToken=' . $token;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->search($query, $fields, $language, $max, $token),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->search($query),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the listByActivity method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testListByActivity()
	{
		$activityId = 'z12ezrmamsvydrgsy221ypew2qrkt1ja404';
		$collection = 'plusoners';
		$fields = 'aboutMe,birthday';
		$max = 5;
		$token = 'EAoaAA';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'activities/' . $activityId . '/people/' . $collection . '?fields=' . $fields .
			'&maxResults=' . $max . '&pageToken=' . $token;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->listByActivity($activityId, $collection, $fields, $max, $token),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->listByActivity($activityId, $collection),
			$this->equalTo(false)
		);
	}
}
