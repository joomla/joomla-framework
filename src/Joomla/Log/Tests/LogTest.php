<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests;

use Joomla\Log\Log;
use Joomla\Log\LogEntry;

/**
 * Test class for Joomla\Log\Log.
 *
 * @since  1.0
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   1.0
	 */
	protected function tearDown()
	{
		// Clear out the log instance.
		$log = new LogInspector;
		Log::setInstance($log);

		parent::tearDown();
	}

	/**
	 * Test the Joomla\Log\Log::addLogEntry method to verify that if called directly it will route the entry to the
	 * appropriate loggers.  We use the echo logger here for easy testing using the PHP output buffer.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddLogEntry()
	{
		// First let's test a set of priorities.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add a loggers to the Log object.
		Log::addLogger(array('logger' => 'echoo'), Log::ALL);

		$this->expectOutputString("DEBUG: TESTING [deprecated]\n");
		$log->addLogEntry(new LogEntry('TESTING', Log::DEBUG, 'DePrEcAtEd'));
	}

	/**
	 * Test that if Joomla\Log\Log::addLogger is called and no Joomla\Log\Log instance has been instantiated yet, that one will
	 * be instantiated automatically and the logger will work accordingly.  We use the echo logger here for
	 * easy testing using the PHP output buffer.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddLoggerAutoInstantiation()
	{
		Log::setInstance(null);

		Log::addLogger(array('logger' => 'echoo'), Log::ALL);

		$this->expectOutputString("WARNING: TESTING [deprecated]\n");
		Log::add(new LogEntry('TESTING', Log::WARNING, 'DePrEcAtEd'));
	}

	/**
	 * Test that if Joomla\Log\Log::addLogger is called and no Joomla\Log\Log instance has been instantiated yet, that one will
	 * be instantiated automatically and the logger will work accordingly.  We use the echo logger here for
	 * easy testing using the PHP output buffer.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddLoggerAutoInstantiationInvalidLogger()
	{
		// We are expecting a InvalidArgumentException to be thrown since we are trying to add a bogus logger.
		$this->setExpectedException('RuntimeException');

		Log::setInstance(null);

		Log::addLogger(array('logger' => 'foobar'), Log::ALL);

		Log::add(new LogEntry('TESTING', Log::WARNING, 'DePrEcAtEd'));
	}

	/**
	 * Test the Joomla\Log\Log::findLoggers method to make sure given a category we are finding the correct loggers that
	 * have been added to Joomla\Log\Log.  It is important to note that if a logger was added with no category, then it
	 * will be returned for all categories.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFindLoggersByCategory()
	{
		// First let's test a set of priorities.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add the loggers to the Log object.

		// Note: 67d00c8f22f5859a1fd73835ee47e4d
		Log::addLogger(array('text_file' => 'deprecated.log'), Log::ALL, 'deprecated');

		// Note: 09826310049345665887853e4688d89e
		Log::addLogger(array('text_file' => 'com_foo.log'), Log::ALL, 'com_foo');

		// Note: 5099e81204381e68555c620cd8140421
		Log::addLogger(array('text_file' => 'none.log'), Log::ALL);

		// Note: 57604db2561c1c4492f5dfceed3d943c
		Log::addLogger(array('text_file' => 'deprecated-com_foo.log'), Log::ALL, array('deprecated', 'com_foo'));

		// Note: 5fbf17c78bfcd300debc791e01066128
		Log::addLogger(array('text_file' => 'foobar-deprecated.log'), Log::ALL, array('foobar', 'deprecated'));

		// Note: b5550c1aa36c1eaf77206565ec5f9021
		Log::addLogger(array('text_file' => 'transactions-paypal.log'), Log::ALL, array('transactions', 'paypal'));

		// Note: 916ed48d2f635431a93aee60c56b0219
		Log::addLogger(array('text_file' => 'transactions.log'), Log::ALL, array('transactions'));

		$this->assertThat(
			$log->findLoggers(Log::EMERGENCY, 'deprecated'),
			$this->equalTo(
				array(
					'767d00c8f22f5859a1fd73835ee47e4d',
					'5099e81204381e68555c620cd8140421',
					'57604db2561c1c4492f5dfceed3d943c',
					'5fbf17c78bfcd300debc791e01066128',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::NOTICE, 'paypal'),
			$this->equalTo(
				array(
					'5099e81204381e68555c620cd8140421',
					'b5550c1aa36c1eaf77206565ec5f9021',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::DEBUG, 'com_foo'),
			$this->equalTo(
				array(
					'09826310049345665887853e4688d89e',
					'5099e81204381e68555c620cd8140421',
					'57604db2561c1c4492f5dfceed3d943c'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::WARNING, 'transactions'),
			$this->equalTo(
				array(
					'5099e81204381e68555c620cd8140421',
					'b5550c1aa36c1eaf77206565ec5f9021',
					'916ed48d2f635431a93aee60c56b0219',
				)
			),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Log\Log::findLoggers method to make sure given a category we are finding the correct loggers that
	 * have been added to Joomla\Log\Log (using exclusion).  It is important to note that empty category can also be excluded.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFindLoggersByNotCategory()
	{
		// First let's test a set of priorities.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add the loggers to the Log object.

		// Note: 46c90979772c19bf707c0d8d6581cad5
		Log::addLogger(array('text_file' => 'not_deprecated.log'), Log::ALL, 'deprecated', true);

		// Note: 96ebc8ec99ccca7d8108232da1f35abe
		Log::addLogger(array('text_file' => 'not_com_foo.log'), Log::ALL, 'com_foo', true);

		// Note: 84c5af052b619356b9fdd2f5cefd90fd
		Log::addLogger(array('text_file' => 'not_none.log'), Log::ALL, '', true);

		// Note: 645f55d76f1d8bc00f79040d5bead8d6
		Log::addLogger(array('text_file' => 'not_deprecated-com_foo.log'), Log::ALL, array('deprecated', 'com_foo'), true);

		// Note: 07abacf4dc704fe78479149ad51bd044
		Log::addLogger(array('text_file' => 'not_foobar-deprecated.log'), Log::ALL, array('foobar', 'deprecated'), true);

		// Note: affc04af81476fbb5e19b2773a927ec6
		Log::addLogger(array('text_file' => 'not_transactions-paypal.log'), Log::ALL, array('transactions', 'paypal'), true);

		// Note: 1aa03749b113bc00fb030b6c5a67b6ec
		Log::addLogger(array('text_file' => 'not_transactions.log'), Log::ALL, array('transactions'), true);

		$this->assertThat(
			$log->findLoggers(Log::EMERGENCY, 'deprecated'),
			$this->equalTo(
				array(
					'96ebc8ec99ccca7d8108232da1f35abe',
					'84c5af052b619356b9fdd2f5cefd90fd',
					'affc04af81476fbb5e19b2773a927ec6',
					'1aa03749b113bc00fb030b6c5a67b6ec',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::NOTICE, 'paypal'),
			$this->equalTo(
				array(
					'46c90979772c19bf707c0d8d6581cad5',
					'96ebc8ec99ccca7d8108232da1f35abe',
					'84c5af052b619356b9fdd2f5cefd90fd',
					'645f55d76f1d8bc00f79040d5bead8d6',
					'07abacf4dc704fe78479149ad51bd044',
					'1aa03749b113bc00fb030b6c5a67b6ec'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::DEBUG, 'com_foo'),
			$this->equalTo(
				array(
					'46c90979772c19bf707c0d8d6581cad5',
					'84c5af052b619356b9fdd2f5cefd90fd',
					'07abacf4dc704fe78479149ad51bd044',
					'affc04af81476fbb5e19b2773a927ec6',
					'1aa03749b113bc00fb030b6c5a67b6ec'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::WARNING, 'transactions'),
			$this->equalTo(
				array(
					'46c90979772c19bf707c0d8d6581cad5',
					'96ebc8ec99ccca7d8108232da1f35abe',
					'84c5af052b619356b9fdd2f5cefd90fd',
					'645f55d76f1d8bc00f79040d5bead8d6',
					'07abacf4dc704fe78479149ad51bd044'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::INFO, ''),
			$this->equalTo(
				array(
					'46c90979772c19bf707c0d8d6581cad5',
					'96ebc8ec99ccca7d8108232da1f35abe',
					'645f55d76f1d8bc00f79040d5bead8d6',
					'07abacf4dc704fe78479149ad51bd044',
					'affc04af81476fbb5e19b2773a927ec6',
					'1aa03749b113bc00fb030b6c5a67b6ec'
				)
			),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Log\Log::findLoggers method to make sure given a priority we are finding the correct loggers that
	 * have been added to Joomla\Log\Log.  It is important to test not only straight values but also bitwise combinations
	 * and the catch all Joomla\Log\Log::ALL as registered loggers.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFindLoggersByPriority()
	{
		// First let's test a set of priorities.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add the loggers to the Log object.

		// Note: 684e35a45ddd17c00024891e95c29046
		Log::addLogger(array('text_file' => 'error.log'), Log::ERROR);

		// Note: 3ab1ff5941725c3ed01e6dd1ff623415
		Log::addLogger(array('text_file' => 'notice.log'), Log::NOTICE);

		// Note: e16e9516d55213efd9255d8c9c13020b
		Log::addLogger(array('text_file' => 'warning.log'), Log::WARNING);

		// Note: d941cfc07f7641537991eaecaa8ea553
		Log::addLogger(array('text_file' => 'error_warning.log'), Log::ERROR | Log::WARNING);

		// Note: a2fae4fb61ef676032361e47068deb9a
		Log::addLogger(array('text_file' => 'all.log'), Log::ALL);

		// Note: aaa7a0e4a4720ef7aed99ded3b764303
		Log::addLogger(array('text_file' => 'all_except_debug.log'), Log::ALL & ~Log::DEBUG);

		$this->assertThat(
			$log->findLoggers(Log::EMERGENCY, null),
			$this->equalTo(
				array(
					'a2fae4fb61ef676032361e47068deb9a',
					'aaa7a0e4a4720ef7aed99ded3b764303',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::NOTICE, null),
			$this->equalTo(
				array(
					'3ab1ff5941725c3ed01e6dd1ff623415',
					'a2fae4fb61ef676032361e47068deb9a',
					'aaa7a0e4a4720ef7aed99ded3b764303'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::DEBUG, null),
			$this->equalTo(
				array(
					'a2fae4fb61ef676032361e47068deb9a'
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::WARNING, null),
			$this->equalTo(
				array(
					'e16e9516d55213efd9255d8c9c13020b',
					'd941cfc07f7641537991eaecaa8ea553',
					'a2fae4fb61ef676032361e47068deb9a',
					'aaa7a0e4a4720ef7aed99ded3b764303'
				)
			),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Log\Log::findLoggers method to make sure given a priority and category we are finding the correct
	 * loggers that have been added to Joomla\Log\Log.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testFindLoggersByPriorityAndCategory()
	{
		// First let's test a set of priorities.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add the loggers to the Log object.

		// Note: 767d00c8f22f5859a1fd73835ee47e4d
		Log::addLogger(array('text_file' => 'deprecated.log'), Log::ALL, 'deprecated');

		// Note: 09826310049345665887853e4688d89e
		Log::addLogger(array('text_file' => 'com_foo.log'), Log::DEBUG, 'com_foo');

		// Note: 5099e81204381e68555c620cd8140421
		Log::addLogger(array('text_file' => 'none.log'), Log::ERROR | Log::CRITICAL | Log::EMERGENCY);

		// Note: 57604db2561c1c4492f5dfceed3d943c
		Log::addLogger(array('text_file' => 'deprecated-com_foo.log'), Log::NOTICE | Log::WARNING, array('deprecated', 'com_foo'));

		// Note: b5550c1aa36c1eaf77206565ec5f9021
		Log::addLogger(array('text_file' => 'transactions-paypal.log'), Log::INFO, array('transactions', 'paypal'));

		// Note: 916ed48d2f635431a93aee60c56b0219
		Log::addLogger(array('text_file' => 'transactions.log'), Log::ERROR, array('transactions'));

		$this->assertThat(
			$log->findLoggers(Log::EMERGENCY, 'deprecated'),
			$this->equalTo(
				array(
					'767d00c8f22f5859a1fd73835ee47e4d',
					'5099e81204381e68555c620cd8140421',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::NOTICE, 'paypal'),
			$this->equalTo(
				array()
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::DEBUG, 'com_foo'),
			$this->equalTo(
				array(
					'09826310049345665887853e4688d89e',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::ERROR, 'transactions'),
			$this->equalTo(
				array(
					'5099e81204381e68555c620cd8140421',
					'916ed48d2f635431a93aee60c56b0219',
				)
			),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->findLoggers(Log::INFO, 'transactions'),
			$this->equalTo(
				array(
					'b5550c1aa36c1eaf77206565ec5f9021',
				)
			),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Log\Log::setInstance method to make sure that if we set a logger instance Joomla\Log\Log is actually going
	 * to use it.  We accomplish this by setting an instance of LogInspector and then performing some
	 * operations using Joomla\Log\Log::addLogger() to alter the state of the internal instance.  We then check that the
	 * LogInspector instance we created (and set) has the same values we would expect for lookup and configuration
	 * so we can assert that the operations we performed using Joomla\Log\Log::addLogger() were actually performed on our
	 * instance of LogInspector that was set.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetInstance()
	{
		$log = new LogInspector;
		Log::setInstance($log);

		// Add a logger to the Log object.
		Log::addLogger(array('logger' => 'w3c'));

		// Get the expected configurations array after adding the single logger.
		$expectedConfigurations = array(
			'55202c195e23298813df4292c827b241' => array('logger' => 'w3c')
		);

		// Get the expected lookup array after adding the single logger.
		$expectedLookup = array(
			'55202c195e23298813df4292c827b241' => (object) array('priorities' => Log::ALL, 'categories' => array(), 'exclude' => false)
		);

		// Get the expected loggers array after adding the single logger (hasn't been instantiated yet so null).
		$expectedLoggers = null;

		$this->assertThat(
			$log->configurations,
			$this->equalTo($expectedConfigurations),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->lookup,
			$this->equalTo($expectedLookup),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->loggers,
			$this->equalTo($expectedLoggers),
			'Line: ' . __LINE__ . '.'
		);

		// Start over so we test that it actually sets the instance appropriately.
		$log = new LogInspector;
		Log::setInstance($log);

		// Add a logger to the Log object.
		Log::addLogger(array('logger' => 'database', 'db_type' => 'mysql', 'db_table' => '#__test_table'), Log::ERROR);

		// Get the expected configurations array after adding the single logger.
		$expectedConfigurations = array(
			'b67483f5ba61450d173aae527fa4163f' => array('logger' => 'database', 'db_type' => 'mysql', 'db_table' => '#__test_table')
		);

		// Get the expected lookup array after adding the single logger.
		$expectedLookup = array(
			'b67483f5ba61450d173aae527fa4163f' => (object) array('priorities' => Log::ERROR, 'categories' => array(), 'exclude' => false)
		);

		// Get the expected loggers array after adding the single logger (hasn't been instantiated yet so null).
		$expectedLoggers = null;

		$this->assertThat(
			$log->configurations,
			$this->equalTo($expectedConfigurations),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->lookup,
			$this->equalTo($expectedLookup),
			'Line: ' . __LINE__ . '.'
		);

		$this->assertThat(
			$log->loggers,
			$this->equalTo($expectedLoggers),
			'Line: ' . __LINE__ . '.'
		);
	}
}
