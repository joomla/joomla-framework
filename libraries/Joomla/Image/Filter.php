<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Image
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Image;


use Joomla\Log\Log;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class to manipulate an image.
 *
 * @package     Joomla.Platform
 * @subpackage  Image
 * @since       11.3
 */
abstract class Filter
{
	/**
	 * @var    resource  The image resource handle.
	 * @since  11.3
	 */
	protected $handle;

	/**
	 * Class constructor.
	 *
	 * @param   resource  $handle  The image resource on which to apply the filter.
	 *
	 * @since   11.3
	 * @throws  InvalidArgumentException
	 */
	public function __construct($handle)
	{
		// Verify that image filter support for PHP is available.
		if (!function_exists('imagefilter'))
		{
			// @codeCoverageIgnoreStart
			Log::add('The imagefilter function for PHP is not available.', Log::ERROR);
			throw new RuntimeException('The imagefilter function for PHP is not available.');

			// @codeCoverageIgnoreEnd
		}

		// Make sure the file handle is valid.
		if (!is_resource($handle) || (get_resource_type($handle) != 'gd'))
		{
			Log::add('The image handle is invalid for the image filter.', Log::ERROR);
			throw new InvalidArgumentException('The image handle is invalid for the image filter.');
		}

		$this->handle = $handle;
	}

	/**
	 * Method to apply a filter to an image resource.
	 *
	 * @param   array  $options  An array of options for the filter.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	abstract public function execute(array $options = array());
}
