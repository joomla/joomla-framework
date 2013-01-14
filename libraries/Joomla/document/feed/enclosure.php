<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document.Feed
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Document\Feed;

defined('JPATH_PLATFORM') or die;

/**
 * JFeedEnclosure is an internal class that stores feed enclosure information
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class Enclosure
{
	/**
	 * URL enclosure element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $url = "";

	/**
	 * Length enclosure element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $length = "";

	/**
	 * Type enclosure element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = "";
}