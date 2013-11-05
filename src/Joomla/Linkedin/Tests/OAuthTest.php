<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin\Tests;

use Joomla\Linkedin\OAuth;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/LinkedinTestCase.php';

/**
 * Test class for OAuth.
 *
 * @since  1.0
 */
class OAuthTest extends LinkedinTestCase
{
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
		// Set request parameters.
		$data['format'] = 'json';

		$path = 'https://api.linkedin.com/v1/people::(~)';

		$returnData = new stdClass;
		$returnData->code = $code;
		$returnData->body = $body;

		$path = $this->oauth->toUrl($path, $data);

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
	 * Tests the setScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetScope()
	{
		$this->oauth->setScope('read_stream');

		$this->assertThat(
				$this->options->get('scope'),
				$this->equalTo('read_stream')
		);
	}

	/**
	 * Tests the getScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetScope()
	{
		$this->options->set('scope', 'read_stream');

		$this->assertThat(
				$this->oauth->getScope(),
				$this->equalTo('read_stream')
		);
	}
}
