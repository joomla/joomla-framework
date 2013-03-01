<?php
/**
 * @package    Joomla\Framework\Tests
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/case/GoogleTestCase.php';

/**
 * Test class for JGoogleDataPlusComments.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Google
 * @since       12.3
 */
class JGoogleDataPlusCommentsTest extends GoogleTestCase
{
	/**
	 * @var    JGoogleDataPlusComments  Object under test.
	 */
	protected $object;

	/**
	 * @var    string  Sample JSON string.
	 * @since  12.3
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
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

		$this->object = new JGoogleDataPlusComments($this->options, $this->auth);

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
	 * Tests the getComment method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testGetComment()
	{
		$id = '124346363456';
		$fields = 'id,actor';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'comments/' . $id . '?fields=' . $fields;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComment($id, $fields),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->getComment($id, $fields),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the listComments method
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	public function testListComments()
	{
		$activityId = 'z12ezrmamsvydrgsy221ypew2qrkt1ja404';
		$fields = 'aboutMe,birthday';
		$max = 5;
		$order = 'ascending';
		$token = 'EAoaAA';
		$alt = 'json';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'activities/' . $activityId . '/comments?fields=' . $fields . '&maxResults=' . $max .
			'&orderBy=' . $order . '&pageToken=' . $token . '&alt=' . $alt;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->listComments($activityId, $fields, $max, $order, $token, $alt),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->listComments($activityId),
			$this->equalTo(false)
		);
	}
}
