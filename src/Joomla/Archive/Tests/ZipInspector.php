<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive\Tests;

use Joomla\Archive\Zip as ArchiveZip;

/**
 * Inspector for the JApplicationBase class.
 *
 * @since  1.0
 */
class ZipInspector extends ArchiveZip
{
	/**
	 * Test...
	 *
	 * @param   string  $archive      @todo
	 * @param   string  $destination  @todo
	 * @param   array   $options      @todo
	 *
	 * @return mixed
	 */
	public function accessExtractCustom($archive, $destination, array $options = array())
	{
		return parent::extractCustom($archive, $destination, $options);
	}

	/**
	 * Test...
	 *
	 * @param   string  $archive      @todo
	 * @param   string  $destination  @todo
	 * @param   array   $options      @todo
	 *
	 * @return bool
	 */
	public function accessExtractNative($archive, $destination, array $options = array())
	{
		return parent::extractNative($archive, $destination, $options);
	}
}
