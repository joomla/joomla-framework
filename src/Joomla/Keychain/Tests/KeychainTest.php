<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Keychain\Tests;

use Joomla\Keychain\Keychain;
use Joomla\Keychain\KeychainFactory;

/**
 * Tests for the Joomla Framework Keychain Class
 *
 * @since  1.0
 */
class KeychainTest extends \PHPUnit_Framework_TestCase
{
	/*
	 * @var  Joomla\Keychain\Keychain
	 */
	protected $object;

	public function setUp()
	{
		$this->object = KeychainFactory::getKeychain();
	}

	/**
	 * Set up the system by ensuring some files aren't there.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function setUpBeforeClass()
	{
		// Clean up files
		@unlink(__DIR__ . '/data/web-keychain.dat');
		@unlink(__DIR__ . '/data/web-passphrase.dat');

		parent::setUpBeforeClass();
	}

	/**
	 * Clean up afterwards.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function tearDownAfterClass()
	{
		// Clean up files
		@unlink(__DIR__ . '/data/web-keychain.dat');
		@unlink(__DIR__ . '/data/web-passphrase.dat');

		parent::tearDownAfterClass();
	}

	/**
	 * Test loading a file created in the CLI client (Joomla! Framework)
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadCLIKeychain()
	{
		$keychainFile = __DIR__ . '/data/cli-keychain.dat';
		$passphraseFile = __DIR__ . '/data/cli-passphrase.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';

		$this->object->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('value', $this->object->get('test'));
	}

	/**
	 * Test trying to create a new passphrase file
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreatePassphraseFile()
	{
		$keychainFile = __DIR__ . '/data/web-keychain.dat';
		$privateKeyFile = __DIR__ . '/data/private.key';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->createPassphraseFile('testpassphrase', $passphraseFile, $privateKeyFile, 'password');

		$this->assertTrue(file_exists($passphraseFile), 'Test passphrase file exists');
	}

	/**
	 * Try to load a keychain that liaosn't exist (this shouldn't cause an error)
	 *
	 * @expectedException         RuntimeException
	 * @expectedExceptionMessage  Attempting to load non-existent keychain file
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadKeychainNonexistant()
	{
		$keychainFile = __DIR__ . '/data/fake-web-keychain.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);
	}

	/**
	 * Try to load a keychain that isn't a keychain
	 *
	 * @depends                   testCreatePassphraseFile
	 * @expectedException         RuntimeException
	 * @expectedExceptionMessage  Failed to decrypt keychain file
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadKeychainInvalid()
	{
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->loadKeychain($passphraseFile, $passphraseFile, $publicKeyFile);
	}

	/**
	 * Create a new keychain and persist it to a new file.
	 *
	 * @depends  testCreatePassphraseFile
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSaveKeychain()
	{
		$keychainFile = __DIR__ . '/data/web-keychain.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->set('dennis', 'liao');
		$this->assertTrue((bool) $this->object->saveKeychain($keychainFile, $passphraseFile, $publicKeyFile), 'Assert that saveKeychain returns true.');

		$this->assertTrue(file_exists($keychainFile), 'Check that keychain file was created properly.');
	}

	/**
	 * Load a keychain file we just created
	 *
	 * @return  void
	 *
	 * @depends  testSaveKeychain
	 * @since    1.0
	 */
	public function testLoadKeychain()
	{
		$keychainFile = __DIR__ . '/data/web-keychain.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('liao', $this->object->get('dennis'));
	}

	/**
	 * Delete a value from the keychain
	 *
	 * @return  void
	 *
	 * @depends  testSaveKeychain
	 * @since    1.0
	 */
	public function testDeleteValue()
	{
		$keychainFile = __DIR__ . '/data/web-keychain.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';
		$passphraseFile = __DIR__ . '/data/web-passphrase.dat';

		$this->object->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('liao', $this->object->get('dennis'));

		$this->object->deleteValue('dennis');

		$this->assertFalse($this->object->exists('dennis'));

		$this->object->saveKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$keychain2 = KeychainFactory::getKeychain();

		$keychain2->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertFalse($keychain2->exists('dennis'));
	}
}
