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
 * JOpenSearchImage is an internal class that stores Images for the OpenSearch Description
 *
 * @package     Joomla.Platform
 * @subpackage  Document.Opensearch
 * @since       11.1
 */
class Image
{
	/**
	 * The images MIME type
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = "";

	/**
	 * URL of the image or the image as base64 encoded value
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $data = "";

	/**
	 * The image's width
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $width;

	/**
	 * The image's height
	 *
	 * required
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $height;
}
