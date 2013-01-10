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
 * JFeedItem is an internal class that stores feed item information
 *
 * @package     Joomla.Platform
 * @subpackage  Document.Feed
 * @since       11.1
 */
class Item
{
	/**
	 * Title item element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $title;

	/**
	 * Link item element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $link;

	/**
	 * Description item element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $description;

	/**
	 * Author item element
	 *
	 * optional
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $author;

	/**
	 * Author email element
	 *
	 * optional
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $authorEmail;

	/**
	 * Category element
	 *
	 * optional
	 *
	 * @var    array or string
	 * @since  11.1
	 */
	public $category;

	/**
	 * Comments element
	 *
	 * optional
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $comments;

	/**
	 * Enclosure element
	 *
	 * @var    object
	 * @since  11.1
	 */
	public $enclosure = null;

	/**
	 * Guid element
	 *
	 * optional
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $guid;

	/**
	 * Published date
	 *
	 * optional
	 *
	 * May be in one of the following formats:
	 *
	 * RFC 822:
	 * "Mon, 20 Jan 03 18:05:41 +0400"
	 * "20 Jan 03 18:05:41 +0000"
	 *
	 * ISO 8601:
	 * "2003-01-20T18:05:41+04:00"
	 *
	 * Unix:
	 * 1043082341
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $date;

	/**
	 * Source element
	 *
	 * optional
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $source;

	/**
	 * Set the JFeedEnclosure for this item
	 *
	 * @param   JFeedEnclosure  $enclosure  The JFeedEnclosure to add to the feed.
	 *
	 * @return  JFeedItem instance of $this to allow chaining
	 *
	 * @since   11.1
	 */
	public function setEnclosure(Enclosure $enclosure)
	{
		$this->enclosure = $enclosure;

		return $this;
	}
}