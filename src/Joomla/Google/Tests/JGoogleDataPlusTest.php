<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

use Joomla\Google\Data\Plus;

require_once __DIR__ . '/case/GoogleTestCase.php';

/**
 * Test class for JGoogleDataPlus.
 *
 * @since  1.0
 */
class JGoogleDataPlusTest extends GoogleTestCase
{
	/**
	 * @var    JGoogleDataPlus  Object under test.
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

		$this->object = new Plus($this->options, $this->auth);

		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('clientsecret', 'jeDs8rKw_jDJW8MMf-ff8ejs');
		$this->object->setOption('redirecturi', 'http://localhost/oauth');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the magic __get method - people
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetPeople()
	{
		$this->assertThat(
			$this->object->people,
			$this->isInstanceOf('Joomla\\Google\\Data\\Plus\\People')
		);
	}

	/**
	 * Tests the magic __get method - activities
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetActivities()
	{
		$this->assertThat(
			$this->object->activities,
			$this->isInstanceOf('Joomla\\Google\\Data\\Plus\\Activities')
		);
	}

	/**
	 * Tests the magic __get method - comments
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__GetComments()
	{
		$this->assertThat(
			$this->object->comments,
			$this->isInstanceOf('Joomla\\Google\\Data\\Plus\\Comments')
		);
	}

	/**
	 * Tests the magic __get method - failure
	 *
	 * @return  void
	 *
	 * @since              1.0
	 * @expectedException  \InvalidArgumentException
	 */
	public function test__GetFailure()
	{
		$this->object->other;
	}
}
