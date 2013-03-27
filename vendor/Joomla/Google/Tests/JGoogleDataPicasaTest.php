<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

use Joomla\Google\Data\Picasa;

require_once __DIR__ . '/case/GoogleTestCase.php';

/**
 * Test class for JGoogleDataPicasa.
 *
 * @since  1.0
 */
class JGoogleDataPicasaTest extends GoogleTestCase
{
	/**
	 * @var    JGoogleDataPicasa  Object under test.
	 */
	protected $object;

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

		$this->object = new Picasa($this->options, $this->auth);

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
	 * Tests the listAlbums method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testListAlbums()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\picasaAlbumlistCallback'));
		$results = $this->object->listAlbums('userID');

		$this->assertEquals(count($results), 2);
		$i = 1;

		foreach ($results as $result)
		{
			$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
			$this->assertEquals($result->getTitle(), 'Album ' . $i);
			$i++;
		}
	}

	/**
	 * Tests the listAlbums method with wrong XML
	 *
	 * @group	JGoogle
	 * @expectedException UnexpectedValueException
	 * @return void
	 */
	public function testListAlbumsException()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\picasaBadXmlCallback'));
		$this->object->listAlbums();
	}

	/**
	 * Tests the createAlbum method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testCreateAlbum()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\dataPicasaAlbumCallback'));
		$result = $this->object->createAlbum('userID', 'New Title', 'private');
		$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
		$this->assertEquals($result->getTitle(), 'New Title');
	}

	/**
	 * Tests the getAlbum method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testGetAlbum()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\picasaAlbumCallback'));
		$result = $this->object->getAlbum('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');
		$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
		$this->assertEquals($result->getTitle(), 'Album 2');
	}

	/**
	 * Tests the setOption method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testSetOption()
	{
		$this->object->setOption('key', 'value');

		$this->assertThat(
			$this->options->get('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the getOption method
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testGetOption()
	{
		$this->options->set('key', 'value');

		$this->assertThat(
			$this->object->getOption('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests that all functions properly return false
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testFalse()
	{
		$this->oauth->setToken(false);

		$functions['listAlbums'] = array('userID');
		$functions['createAlbum'] = array('userID', 'New Title', 'private');
		$functions['getAlbum'] = array('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');

		foreach ($functions as $function => $params)
		{
			$this->assertFalse(call_user_func_array(array($this->object, $function), $params));
		}
	}

	/**
	 * Tests that all functions properly return Exceptions
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function testExceptions()
	{
		$this->http->expects($this->atLeastOnce())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\picasaExceptionCallback'));
		$this->http->expects($this->atLeastOnce())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\picasaDataExceptionCallback'));

		$functions['listAlbums'] = array('userID');
		$functions['createAlbum'] = array('userID', 'New Title', 'private');
		$functions['getAlbum'] = array('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');

		foreach ($functions as $function => $params)
		{
			$exception = false;

			try
			{
				call_user_func_array(array($this->object, $function), $params);
			}
			catch (\UnexpectedValueException $e)
			{
				$exception = true;
				$this->assertEquals($e->getMessage(), 'Unexpected data received from Google: `BADDATA`.');
			}
			$this->assertTrue($exception);
		}
	}
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function picasaAlbumCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'text/html');
	$response->body = file_get_contents(__DIR__ . '/album.txt');

	return $response;
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   mixed    $data     Either an associative array or a string to be sent with the request.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function dataPicasaAlbumCallback($url, $data, array $headers = null, $timeout = null)
{
	\PHPUnit_Framework_TestCase::assertContains('<title>New Title</title>', $data);

	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = $data;

	return $response;
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function picasaAlbumlistCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = file_get_contents(__DIR__ . '/albumlist.txt');

	return $response;
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function picasaExceptionCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = 'BADDATA';

	return $response;
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   mixed    $data     Either an associative array or a string to be sent with the request.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function picasaDataExceptionCallback($url, $data, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = 'BADDATA';

	return $response;
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  JHttpResponse
 *
 * @since   1.0
 */
function picasaBadXmlCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = '<feed />';

	return $response;
}
