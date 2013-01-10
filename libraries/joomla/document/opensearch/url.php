<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document.Opensearch
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Document\Opensearch;

defined('JPATH_PLATFORM') or die;

/**
 * JOpenSearchUrl is an internal class that stores the search URLs for the OpenSearch description
 *
 * @package     Joomla.Platform
 * @subpackage  Document.Opensearch
 * @since       11.1
 */
class Url
{
	/**
	 * Type item element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'text/html';

	/**
	 * Rel item element
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $rel = 'results';

	/**
	 * Template item element. Has to contain the {searchTerms} parameter to work.
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $template;
}
