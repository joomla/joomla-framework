<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Media
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Image\Filter;


use Joomla\Image\Filter;

/**
 * Image Filter class inspector for testing purposes.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Media
 * @since       11.3
 */
class Inspector extends Filter
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
	}
}
