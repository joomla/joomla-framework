<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive;

/**
 * Archieve class interface
 *
 * @since  1.0
 */
interface ExtractableInterface
{
	/**
	 * Extract a compressed file to a given path
	 *
	 * @param   string  $archive      Path to archive to extract
	 * @param   string  $destination  Path to extract archive to
	 * @param   array   $options      Extraction options [may be unused]
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.0
	 */
	public function extract($archive, $destination, array $options = array());

	/**
	 * Tests whether this adapter can unpack files on this computer.
	 *
	 * @return  boolean  True if supported
	 *
	 * @since   1.0
	 */
	public static function isSupported();
}
