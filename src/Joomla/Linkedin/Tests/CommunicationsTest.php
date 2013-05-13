<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin\Tests;

use Joomla\Linkedin\Communications;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/LinkedinTestCase.php';

/**
 * Test class for Communications.
 *
 * @since  1.0
 */
class CommunicationsTest extends LinkedinTestCase
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

		$this->object = new Communications($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the inviteByEmail method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testInviteByEmail()
	{
		$email = 'example@domain.com';
		$first_name = 'Frist';
		$last_name = 'Last';
		$subject = 'Subject';
		$body = 'body';
		$connection = 'friend';

		$path = '/v1/people/~/mailbox';

		// Build the xml.
		$xml = '<mailbox-item>
				  <recipients>
				  	<recipient>
						<person path="/people/email=' . $email . '">
							<first-name>' . $first_name . '</first-name>
							<last-name>' . $last_name . '</last-name>
						</person>
					</recipient>
				</recipients>
				<subject>' . $subject . '</subject>
				<body>' . $body . '</body>
				<item-content>
				    <invitation-request>
				      <connect-type>' . $connection . '</connect-type>
				    </invitation-request>
				</item-content>
			 </mailbox-item>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->inviteByEmail($email, $first_name, $last_name, $subject, $body, $connection),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the inviteByEmail method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testInviteByEmailFailure()
	{
		$email = 'example@domain.com';
		$first_name = 'Frist';
		$last_name = 'Last';
		$subject = 'Subject';
		$body = 'body';
		$connection = 'friend';

		$path = '/v1/people/~/mailbox';

		// Build the xml.
		$xml = '<mailbox-item>
				  <recipients>
				  	<recipient>
						<person path="/people/email=' . $email . '">
							<first-name>' . $first_name . '</first-name>
							<last-name>' . $last_name . '</last-name>
						</person>
					</recipient>
				</recipients>
				<subject>' . $subject . '</subject>
				<body>' . $body . '</body>
				<item-content>
				    <invitation-request>
				      <connect-type>' . $connection . '</connect-type>
				    </invitation-request>
				</item-content>
			 </mailbox-item>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->inviteByEmail($email, $first_name, $last_name, $subject, $body, $connection);
	}

	/**
	 * Tests the inviteById method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testInviteById()
	{
		$id = 'lcnIwDU0S6';
		$first_name = 'Frist';
		$last_name = 'Last';
		$subject = 'Subject';
		$body = 'body';
		$connection = 'friend';

		$name = 'NAME_SEARCH';
		$value = 'mwjY';

		$path = '/v1/people-search:(people:(api-standard-profile-request))';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = '{"apiStandardProfileRequest": {"headers": {"_total": 1,"values": [{"name": "x-li-auth-token","value": "'
				. $name . ':' . $value . '"}]}}}';

		$data['format'] = 'json';
		$data['first-name'] = $first_name;
		$data['last-name'] = $last_name;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->at(0))
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$path = '/v1/people/~/mailbox';

		// Build the xml.
		$xml = '<mailbox-item>
				  <recipients>
				  	<recipient>
						<person path="/people/id=' . $id . '">
						</person>
					</recipient>
				</recipients>
				<subject>' . $subject . '</subject>
				<body>' . $body . '</body>
				<item-content>
				    <invitation-request>
				      <connect-type>' . $connection . '</connect-type>
				       <authorization>
				      	<name>' . $name . '</name>
				        <value>' . $value . '</value>
				      </authorization>
				    </invitation-request>
				</item-content>
			 </mailbox-item>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->at(1))
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->inviteById($id, $first_name, $last_name, $subject, $body, $connection),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the inviteById method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testInviteByIdFailure()
	{
		$id = 'lcnIwDU0S6';
		$first_name = 'Frist';
		$last_name = 'Last';
		$subject = 'Subject';
		$body = 'body';
		$connection = 'friend';

		$name = 'NAME_SEARCH';
		$value = 'mwjY';

		$path = '/v1/people-search:(people:(api-standard-profile-request))';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['format'] = 'json';
		$data['first-name'] = $first_name;
		$data['last-name'] = $last_name;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->at(0))
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->inviteById($id, $first_name, $last_name, $subject, $body, $connection);
	}

	/**
	 * Tests the sendMessage method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSendMessage()
	{
		$recipient = array('~', 'lcnIwDU0S6');
		$subject = 'Subject';
		$body = 'body';

		$path = '/v1/people/~/mailbox';

		// Build the xml.
		$xml = '<mailbox-item>
				  <recipients>
				  	<recipient>
						<person path="/people/~"/>
					</recipient>
					<recipient>
						<person path="/people/lcnIwDU0S6"/>
					</recipient>
				  </recipients>
				  <subject>' . $subject . '</subject>
				  <body>' . $body . '</body>
				</mailbox-item>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->sendMessage($recipient, $subject, $body),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the sendMessage method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testSendMessageFailure()
	{
		$recipient = array('~', 'lcnIwDU0S6');
		$subject = 'Subject';
		$body = 'body';

		$path = '/v1/people/~/mailbox';

		// Build the xml.
		$xml = '<mailbox-item>
				  <recipients>
				  	<recipient>
						<person path="/people/~"/>
					</recipient>
					<recipient>
						<person path="/people/lcnIwDU0S6"/>
					</recipient>
				  </recipients>
				  <subject>' . $subject . '</subject>
				  <body>' . $body . '</body>
				</mailbox-item>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->sendMessage($recipient, $subject, $body);
	}
}
