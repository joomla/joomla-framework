<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Keychain\Tests;

use Joomla\Keychain\Keychain;

/**
 * Tests for the Joomla Framework Keychain Class
 *
 * @since  1.0
 */
class KeychainTest extends \PHPUnit_Framework_TestCase
{
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
		$keychain = new Keychain;

		$keychainFile = __DIR__ . '/data/cli-keychain.dat';
		$passphraseFile = __DIR__ . '/data/cli-passphrase.dat';
		$publicKeyFile = __DIR__ . '/data/publickey.pem';

		$keychain->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('value', $keychain->get('test'));
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

		$keychain = new Keychain;
		$keychain->createPassphraseFile('testpassphrase', $passphraseFile, $privateKeyFile, 'password');

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

		$keychain = new Keychain;

		$keychain->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);
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

		$keychain = new Keychain;

		$keychain->loadKeychain($passphraseFile, $passphraseFile, $publicKeyFile);
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

		$keychain = new Keychain;
		$keychain->set('dennis', 'liao');
		$this->assertTrue((bool) $keychain->saveKeychain($keychainFile, $passphraseFile, $publicKeyFile), 'Assert that saveKeychain returns true.');

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

		$keychain = new Keychain;

		$keychain->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('liao', $keychain->get('dennis'));
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

		$keychain = new Keychain;

		$keychain->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertEquals('liao', $keychain->get('dennis'));

		$keychain->deleteValue('dennis');

		$this->assertFalse($keychain->exists('dennis'));

		$keychain->saveKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$keychain = new Keychain;

		$keychain->loadKeychain($keychainFile, $passphraseFile, $publicKeyFile);

		$this->assertFalse($keychain->exists('dennis'));
	}
}
