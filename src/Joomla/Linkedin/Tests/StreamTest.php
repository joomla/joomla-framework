<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin\Tests;

use Joomla\Linkedin\Stream;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/LinkedinTestCase.php';

/**
 * Test class for Stream.
 *
 * @since  1.0
 */
class StreamTest extends LinkedinTestCase
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

		$this->object = new Stream($this->options, $this->client, $this->oauth);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
	*/
	public function seedShare()
	{
		// Company comment, title, url, image, description
		return array(
			array('some comment', 'title example', 'www.example.com', 'www.image-example.com', 'description text'),
			array(null, 'title example', null, 'www.image-example.com', 'description text')
			);
	}

	/**
	 * Tests the share method
	 *
	 * @param   string  $comment      Text of member's comment.
	 * @param   string  $title        Title of shared document.
	 * @param   string  $url          URL for shared content.
	 * @param   string  $image        URL for image of shared content.
	 * @param   string  $description  Description of shared content.
	 *
	 * @return  void
	 *
	 * @dataProvider seedShare
	 * @since   1.0
	 */
	public function testShare($comment, $title, $url, $image, $description)
	{
		$visibility = 'anyone';
		$twitter = true;

		$path = '/v1/people/~/shares?twitter-post=true';

		// Build xml.
		$xml = '<share>
				  <visibility>
					 <code>' . $visibility . '</code>
				  </visibility>';

		// Check if comment specified.
		if ($comment)
		{
			$xml .= '<comment>' . $comment . '</comment>';
		}

		// Check if title and url are specified.
		if ($title && $url)
		{
			$xml .= '<content>
					   <title>' . $title . '</title>
					   <submitted-url>' . $url . '</submitted-url>
					   <submitted-image-url>' . $image . '</submitted-image-url>
					   <description>' . $description . '</description>
					</content>';
		}
		elseif (!$comment)
		{
			$this->setExpectedException('RuntimeException');
			$this->object->share($visibility, $comment, $title, $url, $image, $description, $twitter);
		}

		$xml .= '</share>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->share($visibility, $comment, $title, $url, $image, $description, $twitter),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the share method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testShareFailure()
	{
		$comment = 'some comment';
		$visibility = 'anyone';

		$path = '/v1/people/~/shares';

		// Build xml.
		$xml = '<share>
				  <visibility>
					 <code>' . $visibility . '</code>
				  </visibility>
				  <comment>' . $comment . '</comment>
				</share>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->share($visibility, $comment);
	}

	/**
	 * Tests the reshare method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testReshare()
	{
		$id = 's123435';
		$visibility = 'anyone';
		$comment = 'some comment';
		$twitter = true;

		$path = '/v1/people/~/shares?twitter-post=true';

		// Build xml.
		$xml = '<share>
				  <visibility>
					 <code>' . $visibility . '</code>
				  </visibility>';

		// Check if comment specified.
		if ($comment)
		{
			$xml .= '<comment>' . $comment . '</comment>';
		}

		$xml .= '   <attribution>
					   <share>
					   	  <id>' . $id . '</id>
					   </share>
					</attribution>
				 </share>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->reshare($visibility, $id, $comment, $twitter),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the reshare method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testReshareFailure()
	{
		$id = 's123435';
		$visibility = 'anyone';
		$comment = 'some comment';
		$twitter = true;

		$path = '/v1/people/~/shares?twitter-post=true';

		// Build xml.
		$xml = '<share>
				  <visibility>
					 <code>' . $visibility . '</code>
				  </visibility>';

		// Check if comment specified.
		if ($comment)
		{
			$xml .= '<comment>' . $comment . '</comment>';
		}

		$xml .= '   <attribution>
					   <share>
					   	  <id>' . $id . '</id>
					   </share>
					</attribution>
				 </share>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->reshare($visibility, $id, $comment, $twitter);
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
	*/
	public function seedIdUrl()
	{
		// Member ID or url
		return array(
			array('lcnIwDU0S6', null),
			array(null, 'http://www.linkedin.com/in/dianaprajescu'),
			array(null, null)
			);
	}

	/**
	 * Tests the getCurrentShare method
	 *
	 * @param   string  $id   Member id of the profile you want.
	 * @param   string  $url  The public profile URL.
	 *
	 * @return  void
	 *
	 * @dataProvider seedIdUrl
	 * @since   1.0
	 */
	public function testGetCurrentShare($id, $url)
	{
		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/';

		if ($url)
		{
			$path .= 'url=' . $this->oauth->safeEncode($url);
		}

		if ($id)
		{
			$path .= 'id=' . $id;
		}
		elseif (!$url)
		{
			$path .= '~';
		}

		$path .= ':(current-share)';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getCurrentShare($id, $url),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getCurrentShare method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetCurrentShareFailure()
	{
		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~:(current-share)';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getCurrentShare();
	}

	/**
	 * Tests the getShareStream method
	 *
	 * @param   string  $id   Member id of the profile you want.
	 * @param   string  $url  The public profile URL.
	 *
	 * @return  void
	 *
	 * @dataProvider seedIdUrl
	 * @since   1.0
	 */
	public function testGetShareStream($id, $url)
	{
		// Set request parameters.
		$data['format'] = 'json';
		$data['type'] = 'SHAR';
		$data['scope'] = 'self';

		$path = '/v1/people/';

		if ($url)
		{
			$path .= 'url=' . $this->oauth->safeEncode($url);
		}

		if ($id)
		{
			$path .= $id;
		}
		elseif (!$url)
		{
			$path .= '~';
		}

		$path .= '/network';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getShareStream($id, $url),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getShareStream method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetShareStreamFailure()
	{
		// Set request parameters.
		$data['format'] = 'json';
		$data['type'] = 'SHAR';
		$data['scope'] = 'self';

		$path = '/v1/people/~/network';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getShareStream();
	}

	/**
	* Provides test data for request format detection.
	*
	* @return array
	*
	* @since 1.0
	*/
	public function seedId()
	{
		// Member ID or url
		return array(
			array('lcnIwDU0S6'),
			array(null)
			);
	}

	/**
	 * Tests the getNetworkUpdates method
	 *
	 * @param   string  $id  Member id.
	 *
	 * @return  void
	 *
	 * @dataProvider seedId
	 * @since   1.0
	 */
	public function testGetNetworkUpdates($id)
	{
		$self = true;
		$type = array('PICT', 'STAT');
		$count = 50;
		$start = 1;
		$after = '123346574';
		$before = '123534663';
		$hidden = true;

		// Set request parameters.
		$data['format'] = 'json';
		$data['scope'] = 'self';
		$data['type'] = $type;
		$data['count'] = $count;
		$data['start'] = $start;
		$data['after'] = $after;
		$data['before'] = $before;
		$data['hidden'] = true;

		$path = '/v1/people/';

		if ($id)
		{
			$path .= $id;
		}
		else
		{
			$path .= '~';
		}

		$path .= '/network/updates';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getNetworkUpdates($id, $self, $type, $count, $start, $after, $before, $hidden),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getNetworkUpdates method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetNetworkUpdatesFailure()
	{
		$self = true;
		$type = array('PICT', 'STAT');
		$count = 50;
		$start = 1;
		$after = '123346574';
		$before = '123534663';
		$hidden = true;

		// Set request parameters.
		$data['format'] = 'json';
		$data['scope'] = 'self';
		$data['type'] = $type;
		$data['count'] = $count;
		$data['start'] = $start;
		$data['after'] = $after;
		$data['before'] = $before;
		$data['hidden'] = true;

		$path = '/v1/people/~/network/updates';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getNetworkUpdates(null, $self, $type, $count, $start, $after, $before, $hidden);
	}

	/**
	 * Tests the getNetworkStats method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetNetworkStats()
	{
		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/network-stats';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getNetworkStats(),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getNetworkStats method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetNetworkStatsFailure()
	{
		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/network-stats';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getNetworkStats();
	}

	/**
	 * Tests the postNetworkUpdate method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testPostNetworkUpdate()
	{
		$body = '&amp;lt;a href=&amp;quot;http://www.linkedin.com/profile?viewProfile=&amp;amp;key=3639896&amp;amp;authToken=JdAa&amp;amp;
			authType=name&amp;amp;trk=api*a119686*s128146*&amp;quot;&amp;gt;Kirsten Jones&amp;lt;/a&amp;gt; is reading about &amp;lt;
			a href=&amp;quot;http://www.tigers.com&amp;quot;&amp;gt;Tigers&amp;lt;/a&amp;gt;http://www.tigers.com&amp;gt;Tigers&amp;lt;/a&amp;gt;..';

		$path = '/v1/people/~/person-activities';

		// Build the xml.
		$xml = '<activity locale="en_US">
					<content-type>linkedin-html</content-type>
				    <body>' . $body . '</body>
				</activity>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->postNetworkUpdate($body),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the postNetworkUpdate method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testPostNetworkUpdateFailure()
	{
		$body = '&amp;lt;a href=&amp;quot;http://www.linkedin.com/profile?viewProfile=&amp;amp;key=3639896&amp;amp;authToken=JdAa&amp;amp;
			authType=name&amp;amp;trk=api*a119686*s128146*&amp;quot;&amp;gt;Kirsten Jones&amp;lt;/a&amp;gt; is reading about &amp;lt;
			a href=&amp;quot;http://www.tigers.com&amp;quot;&amp;gt;Tigers&amp;lt;/a&amp;gt;http://www.tigers.com&amp;gt;Tigers&amp;lt;/a&amp;gt;..';

		$path = '/v1/people/~/person-activities';

		// Build the xml.
		$xml = '<activity locale="en_US">
					<content-type>linkedin-html</content-type>
				    <body>' . $body . '</body>
				</activity>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->postNetworkUpdate($body);
	}

	/**
	 * Tests the getComments method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetComments()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/updates/key=' . $key . '/update-comments';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getComments($key),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getComments method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetCommentsFailure()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/updates/key=' . $key . '/update-comments';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getComments($key);
	}

	/**
	 * Tests the postComment method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testPostComment()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';
		$comment = 'Comment text';

		$path = '/v1/people/~/network/updates/key=' . $key . '/update-comments';

		// Build the xml.
		$xml = '<update-comment>
				  <comment>' . $comment . '</comment>
				</update-comment>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 201;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->postComment($key, $comment),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the postComment method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testPostCommentFailure()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';
		$comment = 'Comment text';

		$path = '/v1/people/~/network/updates/key=' . $key . '/update-comments';

		// Build the xml.
		$xml = '<update-comment>
				  <comment>' . $comment . '</comment>
				</update-comment>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->postComment($key, $comment);
	}

	/**
	 * Tests the geLikes method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetLikes()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/updates/key=' . $key . '/likes';

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getLikes($key),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the geLikes method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testGetLikesFailure()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		// Set request parameters.
		$data['format'] = 'json';

		$path = '/v1/people/~/network/updates/key=' . $key . '/likes';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$path = $this->oauth->toUrl($path, $data);

		$this->client->expects($this->once())
			->method('get')
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->getLikes($key);
	}

	/**
	 * Tests the _likeUnlike method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test_likeUnlike()
	{
		// Method tested via requesting classes
		$this->markTestSkipped('This method is tested via requesting classes.');
	}

	/**
	 * Tests the like method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLike()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		$path = '/v1/people/~/network/updates/key=' . $key . '/is-liked';

		$xml = '<is-liked>true</is-liked>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 204;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->like($key),
			$this->equalTo($returnData)
		);
	}

	/**
	 * Tests the like method - failure
	 *
	 * @return  void
	 *
	 * @expectedException DomainException
	 * @since   1.0
	 */
	public function testLikeFailure()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		$path = '/v1/people/~/network/updates/key=' . $key . '/is-liked';

		$xml = '<is-liked>true</is-liked>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 401;
		$returnData->body = $this->errorString;

		$this->client->expects($this->once())
			->method('put', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->object->like($key);
	}

	/**
	 * Tests the unlike method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testUnlike()
	{
		$key = 'APPM-187317358-5635333363205165056-196773';

		$path = '/v1/people/~/network/updates/key=' . $key . '/is-liked';

		$xml = '<is-liked>false</is-liked>';

		$header['Content-Type'] = 'text/xml';

		$returnData = new stdClass;
		$returnData->code = 204;
		$returnData->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put', $xml, $header)
			->with($path)
			->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->unlike($key),
			$this->equalTo($returnData)
		);
	}
}
