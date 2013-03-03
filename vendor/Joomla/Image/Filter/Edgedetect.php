<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Image\Filter;

use Joomla\Image\Filter;

/**
 * Image Filter class to add an edge detect effect to an image.
 *
 * @package  Joomla\Framework
 * @since    11.3
 */
class Edgedetect extends Filter
{
	/**
	 * Method to apply a filter to an image resource.
	 *
	 * @param   array  $options  An array of options for the filter.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	public function execute(array $options = array())
	{
		// Perform the edge detection filter.
		imagefilter($this->handle, IMG_FILTER_EDGEDETECT);
	}
}
