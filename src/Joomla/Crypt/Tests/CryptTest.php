<?php
/**
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Crypt\Tests;

use Joomla\Crypt\Crypt;

/**
 * Test class for JCrypt.
 *
 * @since  1.0
 */
class CryptTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Joomla\Crypt\Crypt
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Crypt;
	}

	/**
	 * Test __construct()
	 *
	 * @covers  Joomla\Crypt\Crypt::__construct()
	 *
	 * @return  void
	 */
	public function test__construct()
	{
		$this->assertAttributeEquals(null, 'key', $this->object);
		$this->assertAttributeInstanceOf('Joomla\\Crypt\\CipherInterface', 'cipher', $this->object);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Crypt\Crypt::decrypt
	 * @todo Implement testDecrypt().
	 *
	 * @return void
	 */
	public function testDecrypt()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Crypt\Crypt::encrypt
	 * @todo Implement testEncrypt().
	 *
	 * @return void
	 */
	public function testEncrypt()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Crypt\Crypt::generateKey
	 *
	 * @return void
	 */
	public function testGenerateKey()
	{
		$key = $this->object->generateKey();

		$this->assertInstanceOf('Joomla\\Crypt\\Key', $key);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Crypt\Crypt::setKey
	 *
	 * @return void
	 */
	public function testSetKey()
	{
		$keyMock = $this->getMock('Joomla\\Crypt\\Key', array(), array('simple'));

		$this->object->setKey($keyMock);

		$this->assertAttributeEquals($keyMock, 'key', $this->object);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Crypt\Crypt::genRandomBytes
	 *
	 * @return void
	 */
	public function testGenRandomBytes()
	{
		// We're just testing wether the value has the expected length.
		// We obviously can't test the result since it's random.

		$randomBytes16 = Crypt::genRandomBytes();
		$this->assertEquals(strlen($randomBytes16), 16);

		$randomBytes8 = Crypt::genRandomBytes(8);
		$this->assertEquals(strlen($randomBytes8), 8);

		$randomBytes17 = Crypt::genRandomBytes(17);
		$this->assertEquals(strlen($randomBytes17), 17);
	}
}
