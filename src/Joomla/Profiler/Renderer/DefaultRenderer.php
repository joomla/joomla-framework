<?php
/**
 * Part of the Joomla Framework Profiler Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Renderer;

use Joomla\Profiler\ProfilerRendererInterface;
use Joomla\Profiler\ProfilerInterface;

/**
 * Default profiler renderer.
 *
 * @since  1.0
 */
class DefaultRenderer implements ProfilerRendererInterface
{
	/**
	 * Render the profiler.
	 *
	 * @param   ProfilerInterface  $profiler  The profiler to render.
	 *
	 * @return  string  The rendered profiler.
	 */
	public function render(ProfilerInterface $profiler)
	{
		$render = '';

		/** @var \Joomla\Profiler\ProfilePointInterface $lastPoint **/
		$lastPoint = null;

		$points = $profiler->getPoints();

		foreach ($points as $point)
		{
			$previousTime = $lastPoint ? $lastPoint->getTime() : 0.0;
			$previousMem = $lastPoint ? $lastPoint->getMemoryMegaBytes() : 0;

			$render .= sprintf(
				'<code>%s %.3f seconds (+%.3f); %0.2f MB (%s%0.3f) - %s</code>',
				$profiler->getName(),
				$point->getTime(),
				$point->getTime() - $previousTime,
				$point->getMemoryMegaBytes(),
				($point->getMemoryMegaBytes() > $previousMem) ? '+' : '',
				$point->getMemoryMegaBytes() - $previousMem,
				$point->getName()
			);

			$render .= '<br />';

			$lastPoint = $point;
		}

		return $render;
	}
}
