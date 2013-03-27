<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Tests for JDate class.
 *
 * @package  Joomla\Framework\Test
 * @since    11.3
 */
class JFactoryTest extends TestCase
{
	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	public function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();
	}

	/**
	 * Tears down the fixture.
	 *
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	public function tearDown()
	{
		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * Tests the JFactory::getConfig method.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 * @covers  JFactory::getConfig
	 * @covers  JFactory::createConfig
	 */
	public function testGetConfig()
	{
		// Temporarily override the config cache in JFactory.
		$temp = JFactory::$config;
		JFactory::$config = null;

		$this->assertInstanceOf(
			'JRegistry',
			JFactory::getConfig(JPATH_TESTS . '/config.php'),
			'Line: ' . __LINE__
		);

		JFactory::$config = $temp;
	}

	/**
	 * Tests the JFactory::getLangauge method.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @covers  JFactory::getLangauge
	 * @covers  JFactory::createLanguage
	 * @todo    Implement testGetLanguage().
	 */
	public function testGetLanguage()
	{
		$this->assertInstanceOf(
			'JLanguage',
			JFactory::getLanguage(),
			'Line: ' . __LINE__
		);

		$this->markTestIncomplete(
			'This test has not been implemented completely yet.'
		);
	}
}
