<?php
/**
 * Part of the Joomla Framework Profiler Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler;

use Joomla\Profiler\Renderer\DefaultRenderer;
use InvalidArgumentException;
use LogicException;
use ArrayIterator;
use IteratorAggregate;
use Countable;

/**
 * Implementation of ProfilerInterface.
 *
 * @since  1.0
 */
class Profiler implements ProfilerInterface, IteratorAggregate, Countable
{
	/**
	 * The name of the profiler.
	 *
	 * @var  string
	 */
	protected $name;

	/**
	 * An array of profiler points (from the first to last).
	 *
	 * @var  ProfilePointInterface[]
	 */
	protected $points;

	/**
	 * A lookup array containing the
	 * names of the already marked points as keys
	 * and their indexes in $points as value.
	 * It is used to quickly find a point
	 * without having to traverse $points.
	 *
	 * @var  array
	 */
	protected $lookup = array();

	/**
	 * A flag to see if we must get
	 * the real memory usage, or the usage of emalloc().
	 *
	 * @var  boolean
	 */
	protected $memoryRealUsage;

	/**
	 * The timestamp with microseconds
	 * when the first point was marked.
	 *
	 * @var  float
	 */
	protected $startTimeStamp = 0.0;

	/**
	 * The memory usage in bytes
	 * when the first point was marked.
	 *
	 * @var  integer
	 */
	protected $startMemoryBytes = 0;

	/**
	 * The memory peak in bytes during
	 * the profiler run.
	 *
	 * @var  integer
	 */
	protected $memoryPeakBytes;

	/**
	 * The profiler renderer.
	 *
	 * @var  ProfilerRendererInterface
	 */
	protected $renderer;

	/**
	 * Constructor.
	 *
	 * @param   string                     $name             The profiler name.
	 * @param   ProfilerRendererInterface  $renderer         The renderer.
	 * @param   ProfilePointInterface[]    $points           An array of profile points.
	 * @param   boolean                    $memoryRealUsage  True to get the real memory usage.
	 *
	 * @throws  InvalidArgumentException
	 */
	public function __construct($name, ProfilerRendererInterface $renderer = null, array $points = array(), $memoryRealUsage = false)
	{
		$this->name = $name;
		$this->renderer = $renderer ? : new DefaultRenderer;

		if (empty($points))
		{
			$this->points = array();
		}

		else
		{
			$this->setPoints($points);
		}

		$this->memoryRealUsage = (bool) $memoryRealUsage;
	}

	/**
	 * Set the points in this profiler.
	 * This function is called by the constructor when injecting an array of points
	 * (mostly for testing purposes).
	 *
	 * @param   ProfilePointInterface[]  $points  An array of profile points.
	 *
	 * @return  void
	 *
	 * @throws  InvalidArgumentException
	 */
	protected function setPoints(array $points)
	{
		foreach ($points as $point)
		{
			if (!($point instanceof ProfilePointInterface))
			{
				throw new InvalidArgumentException(
					'One of the passed point does not implement ProfilePointInterface.'
				);
			}

			if (isset($this->lookup[$point->getName()]))
			{
				throw new InvalidArgumentException(
					sprintf(
						'The point %s already exists in the profiler %s.',
						$point->getName(),
						$this->name
					)
				);
			}

			// Store the point.
			$this->points[] = $point;

			// Add it in the lookup table.
			$this->lookup[$point->getName()] = count($this->points) - 1;
		}
	}

	/**
	 * Get the name of this profiler.
	 *
	 * @return  string  The name of this profiler.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Mark a profile point.
	 *
	 * @param   string  $name  The profile point name.
	 *
	 * @return  ProfilerInterface  This method is chainable.
	 *
	 * @throws  InvalidArgumentException  If the point already exists.
	 */
	public function mark($name)
	{
		// If a point already exists with this name.
		if (isset($this->lookup[$name]))
		{
			throw new InvalidArgumentException(
				sprintf(
					'A point already exists with the name %s in the profiler %s.',
					$name,
					$this->name
				)
			);
		}

		// Update the memory peak (it cannot decrease).
		$this->memoryPeakBytes = memory_get_peak_usage($this->memoryRealUsage);

		// Get the current timestamp and allocated memory amount.
		$timeStamp = microtime(true);
		$memoryBytes = memory_get_usage($this->memoryRealUsage);

		// If this is the first point.
		if (empty($this->points))
		{
			$this->startTimeStamp = $timeStamp;
			$this->startMemoryBytes = $memoryBytes;
		}

		// Create the point.
		$point = new ProfilePoint(
			$name,
			$timeStamp - $this->startTimeStamp,
			$memoryBytes - $this->startMemoryBytes
		);

		// Store it.
		$this->points[] = $point;

		// Store the point name and its key in $points, in the lookup array.
		$this->lookup[$name] = count($this->points) - 1;

		return $this;
	}

	/**
	 * Check if the profiler has marked the given point.
	 *
	 * @param   string  $name  The name of the point.
	 *
	 * @return  boolean  True if the profiler has marked the point, false otherwise.
	 */
	public function hasPoint($name)
	{
		return isset($this->lookup[$name]);
	}

	/**
	 * Get the point identified by the given name.
	 *
	 * @param   string  $name     The name of the point.
	 * @param   mixed   $default  The default value if the point hasn't been marked.
	 *
	 * @return  ProfilePointInterface|mixed  The profile point or the default value.
	 */
	public function getPoint($name, $default = null)
	{
		if (isset($this->lookup[$name]))
		{
			$index = $this->lookup[$name];

			return $this->points[$index];
		}

		return $default;
	}

	/**
	 * Get the elapsed time in seconds between the two points.
	 *
	 * @param   string  $first   The name of the first point.
	 * @param   string  $second  The name of the second point.
	 *
	 * @return  float  The elapsed time between these points in seconds.
	 *
	 * @throws  LogicException  If the points were not marked.
	 */
	public function getTimeBetween($first, $second)
	{
		if (!isset($this->lookup[$first]))
		{
			throw new LogicException(
				sprintf(
					'The point %s was not marked in the profiler %s.',
					$first,
					$this->name
				)
			);
		}

		if (!isset($this->lookup[$second]))
		{
			throw new LogicException(
				sprintf(
					'The point %s was not marked in the profiler %s.',
					$second,
					$this->name
				)
			);
		}

		$indexFirst = $this->lookup[$first];
		$indexSecond = $this->lookup[$second];

		$firstPoint = $this->points[$indexFirst];
		$secondPoint = $this->points[$indexSecond];

		return abs($secondPoint->getTime() - $firstPoint->getTime());
	}

	/**
	 * Get the amount of allocated memory in bytes between the two points.
	 *
	 * @param   string  $first   The name of the first point.
	 * @param   string  $second  The name of the second point.
	 *
	 * @return  integer  The amount of allocated memory between these points in bytes.
	 *
	 * @throws  LogicException  If the points were not marked.
	 */
	public function getMemoryBytesBetween($first, $second)
	{
		if (!isset($this->lookup[$first]))
		{
			throw new LogicException(
				sprintf(
					'The point %s was not marked in the profiler %s.',
					$first,
					$this->name
				)
			);
		}

		if (!isset($this->lookup[$second]))
		{
			throw new LogicException(
				sprintf(
					'The point %s was not marked in the profiler %s.',
					$second,
					$this->name
				)
			);
		}

		$indexFirst = $this->lookup[$first];
		$indexSecond = $this->lookup[$second];

		$firstPoint = $this->points[$indexFirst];
		$secondPoint = $this->points[$indexSecond];

		return abs($secondPoint->getMemoryBytes() - $firstPoint->getMemoryBytes());
	}

	/**
	 * Get the memory peak in bytes during the profiler run.
	 *
	 * @return  integer  The memory peak in bytes.
	 */
	public function getMemoryPeakBytes()
	{
		return $this->memoryPeakBytes;
	}

	/**
	 * Get the points in this profiler (from the first to the last).
	 *
	 * @return  ProfilePointInterface[]  An array of points in this profiler.
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
	 * Set the renderer to render this profiler.
	 *
	 * @param   ProfilerRendererInterface  $renderer  The renderer.
	 *
	 * @return  Profiler  This method is chainable.
	 */
	public function setRenderer(ProfilerRendererInterface $renderer)
	{
		$this->renderer = $renderer;

		return $this;
	}

	/**
	 * Get the currently used renderer in this profiler.
	 *
	 * @return  ProfilerRendererInterface  The renderer.
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * Render the profiler.
	 *
	 * @return  string  The rendered profiler.
	 */
	public function render()
	{
		return $this->renderer->render($this);
	}

	/**
	 * Cast the profiler to a string using the renderer.
	 *
	 * @return  string  The rendered profiler.
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Get an iterator on the profiler points.
	 *
	 * @return  ArrayIterator  An iterator on the profiler points.
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->points);
	}

	/**
	 * Count the number of points in this profiler.
	 *
	 * @return  integer  The number of points.
	 */
	public function count()
	{
		return count($this->points);
	}
}
