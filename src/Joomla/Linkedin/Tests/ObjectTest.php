<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin\Tests;

use Joomla\Linkedin\Object;

require_once __DIR__ . '/case/LinkedinTestCase.php';
require_once __DIR__ . '/stubs/ObjectMock.php';

/**
 * Test class for JLinkedinObject.
 *
 * @since  1.0
 */
class ObjectTest extends LinkedinTestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new ObjectMock($this->options, $this->client, $this->oauth);
	}

	/**
	 * Tests the setOption method
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function testSetOption()
	{
		$this->object->setOption('api.url', 'https://example.com/settest');

		$this->assertThat(
			$this->options->get('api.url'),
			$this->equalTo('https://example.com/settest')
		);
	}
}
