<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Cache
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for JCacheStorage.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Cache
 *
 * @since       11.1
 */
class JCacheStorageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    JCacheStorage
	 * @access protected
	 */
	protected $object;

	/**
	 * @var errors
	 * @access protected
	 */
	protected static $errors;

	/**
	 * @var  boolean
	 */
	protected $apcAvailable;

	/**
	 * @var  boolean
	 */
	protected $eacceleratorAvailable;

	/**
	 * @var  boolean
	 */
	protected $memcacheAvailable;

	/**
	 * @var  boolean
	 */
	protected $xcacheAvailable;

	/**
	 * Setup.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->object = new JCacheStorage;

		$this->checkStores();
	}

	/**
	 * Test...
	 *
	 * @return void
	 */
	protected function checkStores()
	{
		$this->apcAvailable = extension_loaded('apc');
		$this->eacceleratorAvailable = extension_loaded('eaccelerator') && function_exists('eaccelerator_get');
		$this->memcacheAvailable = (extension_loaded('memcache') && class_exists('Memcache')) != true;
		$this->xcacheAvailable = extension_loaded('xcache');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test Cases for getInstance
	 *
	 * @return array
	 */
	public function casesGetInstance()
	{
		$this->checkStores();

		return array(
			'defaultfile' => array(
				'file',
				array(
					'application' => null,
					'language' => 'en-GB',
					'locking' => true,
					'lifetime' => null,
					'cachebase' => JPATH_BASE . '/cache',
					'now' => time(),
				),
				'Joomla\\Cache\\Storage\\File',
			),
			'defaultapc' => array(
				'apc',
				array(
					'application' => null,
					'language' => 'en-GB',
					'locking' => true,
					'lifetime' => null,
					'now' => time(),
				),
				($this->apcAvailable ? 'Joomla\\Cache\\Storage\\Apc' : false),
			),
			'defaulteaccelerator' => array(
				'eaccelerator',
				array(
					'application' => null,
					'language' => 'en-GB',
					'locking' => true,
					'lifetime' => null,
					'now' => time(),
				),
				$this->eacceleratorAvailable ? 'Joomla\\Cache\\Storage\\Eaccelerator' : false,
			),
			'defaultmemcache' => array(
				'memcache',
				array(
					'application' => null,
					'language' => 'en-GB',
					'locking' => true,
					'lifetime' => null,
					'now' => time(),
				),
				$this->memcacheAvailable ? 'Joomla\\Cache\\Storage\\Memcache' : false,
			),
			'defaultxcache' => array(
				'xcache',
				array(
					'application' => null,
					'language' => 'en-GB',
					'locking' => true,
					'lifetime' => null,
					'now' => time(),
				),
				$this->xcacheAvailable ? 'Joomla\\Cache\\Storage\\Xcache' : false,
			),
		);
	}

	/**
	 * Testing getInstance
	 *
	 * @param   string  $handler   cache storage
	 * @param   array   $options   options for cache storage
	 * @param   string  $expClass  name of expected cache storage class
	 *
	 * @return void
	 *
	 * @dataProvider casesGetInstance
	 */
	public function testGetInstance($handler, $options, $expClass)
	{
		if (is_bool($expClass))
		{
			$this->markTestSkipped('The caching method ' . $handler . ' is not supported on this system.');
		}

		$this->object = JCacheStorage::getInstance($handler, $options);

		if (class_exists('JTestConfig'))
		{
			$config = new JTestConfig;
		}

		$this->assertThat(
			$this->object,
			$this->isInstanceOf($expClass),
			'The wrong class was received.'
		);

		$this->assertThat(
			$this->object->_application,
			$this->equalTo($options['application']),
			'Unexpected value for _application.'
		);

		$this->assertThat(
			$this->object->_language,
			$this->equalTo($options['language']),
			'Unexpected value for _language.'
		);

		$this->assertThat(
			$this->object->_locking,
			$this->equalTo($options['locking']),
			'Unexpected value for _locking.'
		);

		$this->assertThat(
			$this->object->_lifetime,

			// @todo remove: $this->equalTo(empty($options['lifetime']) ? $config->get('cachetime')*60 : $options['lifetime']*60),
			$this->equalTo(60),
			'Unexpected value for _lifetime.'
		);

		$this->assertLessThan(
			isset($config->cachetime) ? $config->cachetime : 900,
			abs($this->object->_now - time()),
			'Unexpected value for configuration lifetime.'
		);
	}

	/**
	 * Testing get()
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->assertThat(
			$this->object->get(1, '', time()),
			$this->equalTo(false)
		);
	}

	/**
	 * Testing store().
	 *
	 * @return void
	 */
	public function testStore()
	{
		$this->assertThat(
			$this->object->store(1, '', 'Cached'),
			$this->isTrue()
		);
	}

	/**
	 * Testing remove().
	 *
	 * @return void
	 */
	public function testRemove()
	{
		$this->assertThat(
			$this->object->remove(1, ''),
			$this->isTrue()
		);
	}

	/**
	 * Testing clean().
	 *
	 * @return void
	 */
	public function testClean()
	{
		$this->assertThat(
			$this->object->clean('', 'group'),
			$this->isTrue()
		);
	}

	/**
	 * Testing gc().
	 *
	 * @return void
	 */
	public function testGc()
	{
		$this->assertThat(
			$this->object->gc(),
			$this->isTrue()
		);
	}

	/**
	 * Testing isSupported().
	 *
	 * @return void
	 */
	public function testIsSupported()
	{
		$this->assertThat(
			$this->object->isSupported(),
			$this->isTrue()
		);
	}
}
