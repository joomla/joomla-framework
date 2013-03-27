<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Language\Helper as LanguageHelper;

/**
 * Test class for LanguageHelper.
 *
 * @since  1.0
 */
class LanguageHelperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Joomla\Language\Helper
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

		$this->object = new LanguageHelper;
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Helper::createLanguageList
	 *
	 * @return void
	 */
	public function testCreateLanguageList()
	{
		// This method creates a list consisting of the name and value of language
		$actualLanguage = 'en-GB';

		$option = array(
			'text' => 'English (United Kingdom)',
			'value' => 'en-GB',
			'selected' => 'selected="selected"'
		);
		$listCompareEqual = array(
			0 => $option,
		);

		$list = LanguageHelper::createLanguageList('en-GB', __DIR__ . '/data', false);
		$this->assertEquals(
			$listCompareEqual,
			$list
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Helper::detectLanguage
	 * @todo Implement testDetectLanguage().
	 *
	 * @return void
	 */
	public function testDetectLanguage()
	{
		$lang = LanguageHelper::detectLanguage();

		// Since we're running in a CLI context we can only check the defualt value
		$this->assertNull(
			$lang
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Helper::getLanguages
	 * @todo Implement testGetLanguages().
	 *
	 * @return void
	 */
	public function testGetLanguages()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
