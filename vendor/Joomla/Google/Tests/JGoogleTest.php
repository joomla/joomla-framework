<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/case/GoogleTestCase.php';

/**
 * Test class for JGoogle.
 *
 * @package  Joomla\Framework\Test
 * @since    12.3
 */
class JGoogleTest extends GoogleTestCase
{
	/**
	 * @var    JGoogle  Object under test.
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

		$this->object = new JGoogle($this->options, $this->auth);
	}

	/**
	 * Tests the magic __get method - data
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function test__GetData()
	{
		$this->options->set('clientid', '1075367716947.apps.googleusercontent.com');
		$this->options->set('redirecturi', 'http://j.aaronschmitz.com/web/calendar-test');
		$this->assertThat(
			$this->object->data('Plus'),
			$this->isInstanceOf('JGoogleDataPlus')
		);
		$this->assertThat(
			$this->object->data('Picasa'),
			$this->isInstanceOf('JGoogleDataPicasa')
		);
		$this->assertThat(
			$this->object->data('Adsense'),
			$this->isInstanceOf('JGoogleDataAdsense')
		);
		$this->assertThat(
			$this->object->data('Calendar'),
			$this->isInstanceOf('JGoogleDataCalendar')
		);
		$this->assertNull($this->object->data('NotAClass'));
	}

	/**
	 * Tests the magic __get method - embed
	 *
	 * @group	JGoogle
	 * @return void
	 */
	public function test__GetEmbed()
	{
		$this->assertThat(
			$this->object->embed('Maps'),
			$this->isInstanceOf('JGoogleEmbedMaps')
		);
		$this->assertThat(
			$this->object->embed('Analytics'),
			$this->isInstanceOf('JGoogleEmbedAnalytics')
		);
		$this->assertNull($this->object->embed('NotAClass'));
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
}
