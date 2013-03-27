<?php
/**
 * Part of the Joomla Framework Profiler Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler;

/**
 * Interface for profile points.
 * A Profile point belongs to a ProfilerInterface and the values
 * it holds (time and memory) are relative to the values
 * of the first marked point in that profiler.
 *
 * @since  1.0
 */
interface ProfilePointInterface
{
	/**
	 * Get the name of this profile point.
	 *
	 * @return  string  The name of this profile point.
	 */
	public function getName();

	/**
	 * Get the elapsed time in seconds since the first
	 * point in the profiler it belongs to was marked.
	 *
	 * @return  float  The time in seconds.
	 */
	public function getTime();

	/**
	 * Get the allocated amount of memory in bytes
	 * since the first point in the profiler it belongs to was marked.
	 *
	 * @return  integer  The amount of allocated memory in B.
	 */
	public function getMemoryBytes();

	/**
	 * Get the allocated amount of memory in mega bytes
	 * since the first point in the profiler it belongs to was marked.
	 *
	 * @return  integer  The amount of allocated memory in MB.
	 */
	public function getMemoryMegaBytes();
}
