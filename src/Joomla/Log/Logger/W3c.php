<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

/**
 * Joomla! W3C Logging class
 *
 * This class is designed to build log files based on the W3c specification
 * at: http://www.w3.org/TR/WD-logfile.html
 *
 * @since  1.0
 */
class W3c extends Formattedtext
{
	/**
	 * The format which each entry follows in the log file.  All fields must be
	 * named in all caps and be within curly brackets eg. {FOOBAR}.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $format = '{DATE}	{TIME}	{PRIORITY}	{CLIENTIP}	{CATEGORY}	{MESSAGE}';

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 */
	public function __construct(array &$options)
	{
		// The name of the text file defaults to 'error.w3c.php' if not explicitly given.
		if (empty($options['text_file']))
		{
			$options['text_file'] = 'error.w3c.php';
		}

		// Call the parent constructor.
		parent::__construct($options);
	}
}
