<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\OAuth;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter OAuth.
 *
 * @since  1.0
 */
class OAuthTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"errorCode":401, "message": "Generic error"}';

	/**
	 * Provides test data for request format detection.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function seedVerifyCredentials()
	{
		// Code, body, expected
		return array(
			array(200, $this->sampleString, true),
			array(401, $this->errorString, false)
			);
	}

	/**
	 * Tests the verifyCredentials method
	 *
	 * @param   integer  $code      The return code.
	 * @param   string   $body      The JSON string.
	 * @param   boolean  $expected  Expected return value.
	 *
	 * @return  void
	 *
	 * @dataProvider seedVerifyCredentials
	 * @since   1.0
	 */
	public function testVerifyCredentials($code, $body, $expected)
	{
		$path = 'https://api.twitter.com/1.1/account/verify_credentials.json';

		$returnData = new stdClass;
		$returnData->code = $code;
		$returnData->body = $body;

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->oauth->verifyCredentials(),
			$this->equalTo($expected)
		);
	}

	/**
	 * Tests the endSession method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEndSession()
	{
		$path = 'https://api.twitter.com/1.1/account/end_session.json';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->oauth->endSession(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
