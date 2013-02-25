<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Http
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Http\Http;
use Joomla\Registry\Registry;
use Joomla\Uri\Uri;

/**
 * Test class for JHttp.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Http
 *
 * @since       11.1
 */
class JHttpTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  11.4
	 */
	protected $options;

	/**
	 * @var    JHttpTransport  Mock transport object.
	 * @since  11.4
	 */
	protected $transport;

	/**
	 * @var    Http  Object under test.
	 * @since  11.4
	 */
	protected $object;

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

		static $classNumber = 1;
		$this->options = $this->getMock('Joomla\Registry\Registry', array('get', 'set'));
		$this->transport = $this->getMock('Joomla\Http\Transport\Stream', array('request'), array($this->options), 'CustomTransport' . $classNumber ++, false);

		$this->object = new Http($this->options, $this->transport);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the getOption method
	 *
	 * @return void
	 */
	public function testGetOption()
	{
		$this->options->expects($this->once())
			->method('get')
			->with('testkey')
			->will($this->returnValue('testResult'));

		$this->assertThat(
			$this->object->getOption('testkey'),
			$this->equalTo('testResult')
		);
	}

	/**
	 * Tests the setOption method
	 *
	 * @return void
	 */
	public function testSetOption()
	{
		$this->options->expects($this->once())
			->method('set')
			->with('testkey', 'testvalue');

		$this->assertThat(
			$this->object->setOption('testkey', 'testvalue'),
			$this->equalTo($this->object)
		);
	}

	/**
	 * Tests the options method
	 *
	 * @return void
	 */
	public function testOptions()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('OPTIONS', new Uri('http://example.com'), null, array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->options('http://example.com', array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the head method
	 *
	 * @return void
	 */
	public function testHead()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('HEAD', new Uri('http://example.com'), null, array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->head('http://example.com', array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the get method
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('GET', new Uri('http://example.com'), null, array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->get('http://example.com', array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the post method
	 *
	 * @return void
	 */
	public function testPost()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('POST', new Uri('http://example.com'), array('key' => 'value'), array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->post('http://example.com', array('key' => 'value'), array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the put method
	 *
	 * @return void
	 */
	public function testPut()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('PUT', new Uri('http://example.com'), array('key' => 'value'), array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->put('http://example.com', array('key' => 'value'), array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the delete method
	 *
	 * @return void
	 */
	public function testDelete()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('DELETE', new Uri('http://example.com'), null, array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->delete('http://example.com', array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the trace method
	 *
	 * @return void
	 */
	public function testTrace()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('TRACE', new Uri('http://example.com'), null, array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->trace('http://example.com', array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}

	/**
	 * Tests the patch method
	 *
	 * @return void
	 */
	public function testPatch()
	{
		$this->transport->expects($this->once())
			->method('request')
			->with('PATCH', new Uri('http://example.com'), array('key' => 'value'), array('testHeader'))
			->will($this->returnValue('ReturnString'));

		$this->assertThat(
			$this->object->patch('http://example.com', array('key' => 'value'), array('testHeader')),
			$this->equalTo('ReturnString')
		);
	}
}
