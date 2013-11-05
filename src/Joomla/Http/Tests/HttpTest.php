<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http\Tests;

use Joomla\Http\Http;
use Joomla\Uri\Uri;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Http\Http.
 *
 * @since  1.0
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    array  Options for the Http object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Transport  Mock transport object.
	 * @since  1.0
	 */
	protected $transport;

	/**
	 * @var    Http  Object under test.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		static $classNumber = 1;
		$this->options = array();
		$this->transport = $this->getMock(
			'Joomla\Http\Transport\Stream',
			array('request'),
			array($this->options),
			'CustomTransport' . $classNumber ++,
			false
		);

		$this->object = new Http($this->options, $this->transport);
	}

	/**
	 * Tests the getOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetOption()
	{
		$this->object->setOption('testKey', 'testValue');

		$value = TestHelper::getValue($this->object, 'options');

		$this->assertThat(
			$value['testKey'],
			$this->equalTo('testValue')
		);
	}

	/**
	 * Tests the setOption method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetOption()
	{
		TestHelper::setValue(
			$this->object, 'options', array(
				'testKey' => 'testValue'
			)
		);

		$this->assertThat(
			$this->object->getOption('testKey'),
			$this->equalTo('testValue')
		);
	}

	/**
	 * Tests the options method
	 *
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * @return  void
	 *
	 * @since   1.0
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
