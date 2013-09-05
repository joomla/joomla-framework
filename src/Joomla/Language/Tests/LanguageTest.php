<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/JLanguageInspector.php';
require_once __DIR__ . '/data/language/en-GB/en-GB.localise.php';

use Joomla\Language\Language;
use Joomla\Filesystem\Folder;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Language\Language.
 *
 * @since  1.0
 */
class LanguageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Joomla\Language\Language
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

		$path = JPATH_ROOT . '/language';

		if (is_dir($path))
		{
			Folder::delete($path);
		}

		Folder::copy(__DIR__ . '/data/language', $path);

		$this->object = new Language;
		$this->inspector = new JLanguageInspector('', true);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		Folder::delete(JPATH_ROOT . '/language');
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getInstance
	 * @covers Joomla\Language\Language::getLanguage
	 *
	 * @return void
	 */
	public function testGetInstanceAndLanguage()
	{
		$instance = Language::getInstance(null);
		$this->assertInstanceOf('Joomla\Language\Language', $instance);

		$this->assertEquals(
			TestHelper::getValue($instance, 'default'),
			$instance->getLanguage(),
			'Asserts that getInstance when called with a null language returns the default language.  Line: ' . __LINE__
		);

		$instance = Language::getInstance('es-ES');

		$this->assertEquals(
			'es-ES',
			$instance->getLanguage(),
			'Asserts that getInstance when called with a specific language returns that language.  Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::__construct
	 *
	 * @return void
	 */
	public function testConstruct()
	{
		// @codingStandardsIgnoreStart
		// @todo check the instanciating new classes without brackets sniff
		$instance = new Language(null, true);
		// @codingStandardsIgnoreEnd

		$this->assertInstanceOf('Joomla\Language\Language', $instance);
		$this->assertTrue($instance->getDebug());

		// @codingStandardsIgnoreStart
		// @todo check the instanciating new classes without brackets sniff
		$instance = new Language(null, false);
		// @codingStandardsIgnoreEnd
		$this->assertInstanceOf('Joomla\Language\Language', $instance);
		$this->assertFalse($instance->getDebug());
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::_
	 *
	 * @return void
	 */
	public function test_()
	{
		$string1 = 'delete';
		$string2 = "delete's";

		$this->assertEquals(
			'delete',
			$this->object->_($string1, false),
			'Line: ' . __LINE__ . ' Exact case should match when javascript safe is false '
		);

		$this->assertNotEquals(
			'Delete',
			$this->object->_($string1, false),
			'Line: ' . __LINE__ . ' Should be case sensitive when javascript safe is false'
		);

		$this->assertEquals(
			'delete',
			$this->object->_($string1, true),
			'Line: ' . __LINE__ . ' Exact case match should work when javascript safe is true'
		);

		$this->assertNotEquals(
			'Delete',
			$this->object->_($string1, true),
			'Line: ' . __LINE__ . ' Should be case sensitive when javascript safe is true'
		);

		$this->assertEquals(
			'delete\'s',
			$this->object->_($string2, false),
			'Line: ' . __LINE__ . ' Exact case should match when javascript safe is false '
		);

		$this->assertNotEquals(
			'Delete\'s',
			$this->object->_($string2, false),
			'Line: ' . __LINE__ . ' Should be case sensitive when javascript safe is false'
		);

		$this->assertEquals(
			"delete\'s",
			$this->object->_($string2, true),
			'Line: ' . __LINE__ . ' Exact case should match when javascript safe is true, also it calls addslashes (\' => \\\') '
		);

		$this->assertNotEquals(
			"Delete\'s",
			$this->object->_($string2, true),
			'Line: ' . __LINE__ . ' Should be case sensitive when javascript safe is true,, also it calls addslashes (\' => \\\') '
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::transliterate
	 *
	 * @return void
	 */
	public function testTransliterate()
	{
		$string1 = 'Así';
		$string2 = 'EÑE';

		$this->assertEquals(
			'asi',
			$this->object->transliterate($string1),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			'Asi',
			$this->object->transliterate($string1),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			'Así',
			$this->object->transliterate($string1),
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			'ene',
			$this->object->transliterate($string2),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			'ENE',
			$this->object->transliterate($string2),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			'EÑE',
			$this->object->transliterate($string2),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getTransliterator
	 *
	 * @return void
	 */
	public function testGetTransliterator()
	{
		$lang = new Language('');

		// The first time you run the method returns NULL
		// Only if there is an setTransliterator, this test is wrong
		$this->assertNull(
			$lang->getTransliterator()
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setTransliterator
	 * @todo Implement testSetTransliterator().
	 *
	 * @return void
	 */
	public function testSetTransliterator()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		// Note: set -> $funtion1: set returns NULL and get returns $function1
		$this->assertNull(
			$lang->setTransliterator($function1)
		);

		$get = $lang->getTransliterator();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setTransliterator($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getTransliterator(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getTransliterator(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getPluralSuffixes
	 *
	 * @return void
	 */
	public function testGetPluralSuffixes()
	{
		$this->assertEquals(
			array('0'),
			$this->object->getPluralSuffixes(0),
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			array('1'),
			$this->object->getPluralSuffixes(1),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getPluralSuffixesCallback
	 *
	 * @return void
	 */
	public function testGetPluralSuffixesCallback()
	{
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getPluralSuffixesCallback())
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setPluralSuffixesCallback
	 * @covers Joomla\Language\Language::getPluralSuffixesCallback
	 *
	 * @return void
	 */
	public function testSetPluralSuffixesCallback()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getPluralSuffixesCallback())
		);

		$this->assertTrue(
			is_callable($lang->setPluralSuffixesCallback($function1))
		);

		$get = $lang->getPluralSuffixesCallback();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setPluralSuffixesCallback($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getPluralSuffixesCallback(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getPluralSuffixesCallback(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getIgnoredSearchWords
	 *
	 * @return void
	 */
	public function testGetIgnoredSearchWords()
	{
		$lang = new Language('');

		$this->assertEquals(
			array('and', 'in', 'on'),
			$lang->getIgnoredSearchWords(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getIgnoredSearchWordsCallback
	 *
	 * @return void
	 */
	public function testGetIgnoredSearchWordsCallback()
	{
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getIgnoredSearchWordsCallback())
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setIgnoredSearchWordsCallback
	 * @covers Joomla\Language\Language::getIgnoredSearchWordsCallback
	 *
	 * @return void
	 */
	public function testSetIgnoredSearchWordsCallback()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getIgnoredSearchWordsCallback())
		);

		// Note: set -> $funtion1: set returns NULL and get returns $function1
		$this->assertTrue(
			is_callable($lang->setIgnoredSearchWordsCallback($function1))
		);

		$get = $lang->getIgnoredSearchWordsCallback();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setIgnoredSearchWordsCallback($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getIgnoredSearchWordsCallback(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getIgnoredSearchWordsCallback(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getLowerLimitSearchWord
	 *
	 * @return void
	 */
	public function testGetLowerLimitSearchWord()
	{
		$lang = new Language('');

		$this->assertEquals(
			3,
			$lang->getLowerLimitSearchWord(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getLowerLimitSearchWordCallback
	 *
	 * @return void
	 */
	public function testGetLowerLimitSearchWordCallback()
	{
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getLowerLimitSearchWordCallback())
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setLowerLimitSearchWordCallback
	 * @covers Joomla\Language\Language::getLowerLimitSearchWordCallback
	 *
	 * @return void
	 */
	public function testSetLowerLimitSearchWordCallback()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getLowerLimitSearchWordCallback())
		);

		// Note: set -> $funtion1: set returns NULL and get returns $function1
		$this->assertTrue(
			is_callable($lang->setLowerLimitSearchWordCallback($function1))
		);

		$get = $lang->getLowerLimitSearchWordCallback();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setLowerLimitSearchWordCallback($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getLowerLimitSearchWordCallback(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getLowerLimitSearchWordCallback(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getUpperLimitSearchWord
	 *
	 * @return void
	 */
	public function testGetUpperLimitSearchWord()
	{
		$lang = new Language('');

		$this->assertEquals(
			20,
			$lang->getUpperLimitSearchWord(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getUpperLimitSearchWordCallback
	 *
	 * @return void
	 */
	public function testGetUpperLimitSearchWordCallback()
	{
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getUpperLimitSearchWordCallback())
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setUpperLimitSearchWordCallback
	 * @covers Joomla\Language\Language::getUpperLimitSearchWordCallback
	 *
	 * @return void
	 */
	public function testSetUpperLimitSearchWordCallback()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getUpperLimitSearchWordCallback())
		);

		// Note: set -> $funtion1: set returns NULL and get returns $function1
		$this->assertTrue(
			is_callable($lang->setUpperLimitSearchWordCallback($function1))
		);

		$get = $lang->getUpperLimitSearchWordCallback();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setUpperLimitSearchWordCallback($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getUpperLimitSearchWordCallback(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getUpperLimitSearchWordCallback(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getSearchDisplayedCharactersNumber
	 *
	 * @return void
	 */
	public function testGetSearchDisplayedCharactersNumber()
	{
		$lang = new Language('');

		$this->assertEquals(
			200,
			$lang->getSearchDisplayedCharactersNumber(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getSearchDisplayedCharactersNumberCallback
	 *
	 * @return void
	 */
	public function testGetSearchDisplayedCharactersNumberCallback()
	{
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getSearchDisplayedCharactersNumberCallback())
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setSearchDisplayedCharactersNumberCallback
	 * @covers Joomla\Language\Language::getSearchDisplayedCharactersNumberCallback
	 *
	 * @return void
	 */
	public function testSetSearchDisplayedCharactersNumberCallback()
	{
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new Language('');

		$this->assertTrue(
			is_callable($lang->getSearchDisplayedCharactersNumberCallback())
		);

		// Note: set -> $funtion1: set returns NULL and get returns $function1
		$this->assertTrue(
			is_callable($lang->setSearchDisplayedCharactersNumberCallback($function1))
		);

		$get = $lang->getSearchDisplayedCharactersNumberCallback();
		$this->assertEquals(
			$function1,
			$get,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$get,
			'Line: ' . __LINE__
		);

		// Note: set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setSearchDisplayedCharactersNumberCallback($function2);
		$this->assertEquals(
			$function1,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function2,
			$set,
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			$function2,
			$lang->getSearchDisplayedCharactersNumberCallback(),
			'Line: ' . __LINE__
		);

		$this->assertNotEquals(
			$function1,
			$lang->getSearchDisplayedCharactersNumberCallback(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::exists
	 * @todo Implement testExists().
	 *
	 * @return void
	 */
	public function testExists()
	{
		$this->assertFalse(
			$this->object->exists(null)
		);

		$basePath = __DIR__ . '/data';

		$this->assertTrue(
			$this->object->exists('en-GB', $basePath)
		);

		$this->assertFalse(
			$this->object->exists('es-ES', $basePath)
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::load
	 * @todo Implement testLoad().
	 *
	 * @return void
	 */
	public function testLoad()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::parse
	 *
	 * @return void
	 */
	public function testParse()
	{
		$strings = $this->inspector->parse(__DIR__ . '/data/good.ini');

		$this->assertThat(
			$strings,
			$this->logicalNot($this->equalTo(array())),
			'Line: ' . __LINE__ . ' good ini file should load properly.'
		);

		$this->assertEquals(
			$strings,
			array('FOO' => 'Bar'),
			'Line: ' . __LINE__ . ' test that the strings were parsed correctly.'
		);

		$strings = $this->inspector->parse(__DIR__ . '/data/bad.ini');

		$this->assertEquals(
			$strings,
			array(),
			'Line: ' . __LINE__ . ' bad ini file should not load properly.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::get
	 * @todo Implement testGet().
	 *
	 * @return void
	 */
	public function testGet()
	{
		$this->assertNull(
			$this->object->get('noExist')
		);

		$this->assertEquals(
			'abc',
			$this->object->get('noExist', 'abc')
		);

		// Note: property = tag, returns en-GB (default language)
		$this->assertEquals(
			'en-GB',
			$this->object->get('tag')
		);

		// Note: property = name, returns English (United Kingdom) (default language)
		$this->assertEquals(
			'English (United Kingdom)',
			$this->object->get('name')
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getName
	 * @todo Implement testGetName().
	 *
	 * @return void
	 */
	public function testGetName()
	{
		$this->assertEquals(
			'English (United Kingdom)',
			$this->object->getName()
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getPaths
	 * @todo Implement testGetPaths().
	 *
	 * @return void
	 */
	public function testGetPaths()
	{
		// Without extension, retuns NULL
		$this->assertNull(
			$this->object->getPaths('')
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getErrorFiles
	 * @todo Implement testGetErrorFiles().
	 *
	 * @return void
	 */
	public function testGetErrorFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getTag
	 * @todo Implement testGetTag().
	 *
	 * @return void
	 */
	public function testGetTag()
	{
		$this->assertEquals(
			'en-GB',
			$this->object->getTag()
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::isRTL
	 * @todo Implement testIsRTL().
	 *
	 * @return void
	 */
	public function testIsRTL()
	{
		$this->assertFalse(
			$this->object->isRTL()
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setDebug
	 * @covers Joomla\Language\Language::getDebug
	 *
	 * @return void
	 */
	public function testGetSetDebug()
	{
		$current = $this->object->getDebug();
		$this->assertEquals(
			$current,
			$this->object->setDebug(true),
			'Line: ' . __LINE__
		);

		$this->object->setDebug(false);
		$this->assertFalse(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug(true);
		$this->assertTrue(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug(0);
		$this->assertFalse(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug(1);
		$this->assertTrue(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug('');
		$this->assertFalse(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug('test');
		$this->assertTrue(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);

		$this->object->setDebug('0');
		$this->assertFalse(
			$this->object->getDebug(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getDefault
	 *
	 * @return void
	 */
	public function testGetDefault()
	{
		$this->assertEquals(
			'en-GB',
			$this->object->getDefault(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setDefault
	 *
	 * @return void
	 */
	public function testSetDefault()
	{
		$this->object->setDefault('de-DE');
		$this->assertEquals(
			'de-DE',
			$this->object->getDefault(),
			'Line: ' . __LINE__
		);
		$this->object->setDefault('en-GB');
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getOrphans
	 * @todo Implement testGetOrphans().
	 *
	 * @return void
	 */
	public function testGetOrphans()
	{
		$this->assertEquals(
			array(),
			$this->object->getOrphans(),
			'Line: ' . __LINE__
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getUsed
	 * @todo Implement testGetUsed().
	 *
	 * @return void
	 */
	public function testGetUsed()
	{
		$this->assertEquals(
			array(),
			$this->object->getUsed(),
			'Line: ' . __LINE__
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::hasKey
	 * @todo Implement testHasKey().
	 *
	 * @return void
	 */
	public function testHasKey()
	{
		// Key doesn't exist, returns false
		$this->assertFalse(
			$this->object->hasKey('com_admin.key')
		);

		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getMetadata
	 * @todo Implement testGetMetadata().
	 *
	 * @return void
	 */
	public function testGetMetadata()
	{
		// Language doesn't exist, retun NULL
		$this->assertNull(
			$this->inspector->getMetadata('es-ES')
		);

		$localeString = 'en_GB.utf8, en_GB.UTF-8, en_GB, eng_GB, en, english, english-uk, uk, gbr, britain, england, great britain, ' .
			'uk, united kingdom, united-kingdom';

		// In this case, returns array with default language
		// - same operation of get method with metadata property
		$options = array(
			'name' => 'English (United Kingdom)',
			'tag' => 'en-GB',
			'rtl' => '0',
			'locale' => $localeString,
			'firstDay' => '0'
		);

		// Language exists, returns array with values
		$this->assertEquals(
			$options,
			$this->inspector->getMetadata('en-GB')
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getKnownLanguages
	 *
	 * @return void
	 */
	public function testGetKnownLanguages()
	{
		// This method returns a list of known languages
		$basePath = __DIR__ . '/data';

		$localeString = 'en_GB.utf8, en_GB.UTF-8, en_GB, eng_GB, en, english, english-uk, uk, gbr, britain, england, great britain,' .
			' uk, united kingdom, united-kingdom';

		$option1 = array(
			'name' => 'English (United Kingdom)',
			'tag' => 'en-GB',
			'rtl' => '0',
			'locale' => $localeString,
			'firstDay' => '0'
		);
		$listCompareEqual1 = array(
			'en-GB' => $option1,
		);

		$list = Language::getKnownLanguages($basePath);
		$this->assertEquals(
			$listCompareEqual1,
			$list,
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getLanguagePath
	 *
	 * @return void
	 */
	public function testGetLanguagePath()
	{
		$basePath = 'test';

		// $language = null, returns language directory
		$this->assertEquals(
			'test/language',
			Language::getLanguagePath($basePath, null),
			'Line: ' . __LINE__
		);

		// $language = value (en-GB, for example), returns en-GB language directory
		$this->assertEquals(
			'test/language/en-GB',
			Language::getLanguagePath($basePath, 'en-GB'),
			'Line: ' . __LINE__
		);

		// With no argument JPATH_ROOT should be returned
		$this->assertEquals(
			JPATH_ROOT . '/language',
			Language::getLanguagePath(),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::setLanguage
	 *
	 * @return void
	 */
	public function testSetLanguage()
	{
		$this->assertEquals(
			'en-GB',
			$this->object->setLanguage('es-ES'),
			'Line: ' . __LINE__
		);

		$this->assertEquals(
			'es-ES',
			$this->object->setLanguage('en-GB'),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getLocale
	 * @todo Implement testGetLocale().
	 *
	 * @return void
	 */
	public function testGetLocale()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::getFirstDay
	 * @todo Implement testGetFirstDay().
	 *
	 * @return void
	 */
	public function testGetFirstDay()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Language\Language::parseLanguageFiles
	 *
	 * @return void
	 */
	public function testParseLanguageFiles()
	{
		$dir = __DIR__ . '/data/language';
		$option = array(
			'name' => 'English (United Kingdom)',
			'tag' => 'en-GB',
			'rtl' => '0',
			'locale' => 'en_GB.utf8, en_GB.UTF-8, en_GB, eng_GB, en, english, english-uk, uk, gbr, britain, england,' .
				' great britain, uk, united kingdom, united-kingdom',
			'firstDay' => '0'
		);
		$expected = array(
			'en-GB' => $option
		);

		$result = Joomla\Language\Language::parseLanguageFiles($dir);

		$this->assertEquals(
			$expected,
			$result,
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers  Joomla\Language\Language::parseXMLLanguageFile
	 *
	 * @return void
	 */
	public function testParseXMLLanguageFile()
	{
		$option = array(
			'name' => 'English (United Kingdom)',
			'tag' => 'en-GB',
			'rtl' => '0',
			'locale' => 'en_GB.utf8, en_GB.UTF-8, en_GB, eng_GB, en, english, english-uk, uk, gbr, britain, england, great britain,' .
				' uk, united kingdom, united-kingdom',
			'firstDay' => '0'
		);
		$path = __DIR__ . '/data/language/en-GB/en-GB.xml';

		$this->assertEquals(
			$option,
			Language::parseXMLLanguageFile($path),
			'Line: ' . __LINE__
		);

		$path2 = __DIR__ . '/data/language/es-ES/es-ES.xml';
		$this->assertEquals(
			$option,
			Language::parseXMLLanguageFile($path),
			'Line: ' . __LINE__
		);
	}

	/**
	 * Test...
	 *
	 * @covers  Joomla\Language\Language::parseXMLLanguageFile
	 * @expectedException  RuntimeException
	 *
	 * @return void
	 */
	public function testParseXMLLanguageFileException()
	{
		$path = __DIR__ . '/data/language/es-ES/es-ES.xml';

		Language::parseXMLLanguageFile($path);
	}
}
