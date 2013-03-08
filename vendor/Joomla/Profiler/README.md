## The Profiler Package

The Joomla Framework provides you a Profiler to profile the time
that it takes to do certain tasks or reach various milestones as your extension runs.

### Usage

```php
use Joomla\Profiler\Profiler;

// Creating a Profiler having the name "Notes".
$profiler = new Profiler('Notes');

// Mark a point called "Start".
$profiler->mark('Start');

// Execute some code...

// Mark a point called "Middle".
$profiler->mark('Middle');

// Execute some code...

// Mark a point called "End".
$profiler->mark('End');
```

You must at least mark the first point, that will be the reference.
But it is always a good idea to mark the end point, even if you have intermediate steps.

Now, you can retrieve the elapsed time between two points :

```php
use Joomla\Profiler\Profiler;

// Return the elapsed time in seconds between two points (the order does not matter).
$elapsed = $profiler->getTimeBetween('Start', 'Middle');
```

You can also retrieve the amount of allocated memory between two points.

```php
use Joomla\Profiler\Profiler;

// Return the amount of allocated memory between these two points.
$elapsed = $profiler->getMemoryBytesBetween('Start', 'Middle');
```

When you have finished, you can output the result.

```php
// Will display the profiler results.
echo $profiler;

// Will render the profiler as a string.
$render = $profiler->render();
```

The output could look something like the following:

```
Notes 0.015 seconds (+0.015); 0.96 MB (+0.960) - Start
Notes 1.000 seconds (+0.985); 3.00 MB (+2.040) - Middle
Notes 1.813 seconds (+0.813); 6.24 MB (+3.240) - End
```

You can see each line is qualified by the label you used when you created the profiler object, and then the label you used for the mark.  Following that is the time difference from when the profiler object was created down to the millisecond level.  Lastly is the amount of memory that is being usage by PHP.

## Writing your own Renderer

You can write your own renderer if you need an other formatting.
In order to do so, you need to implement the ProfilerRendererInterface.

```php
namespace MyApp;

use Joomla\Profiler\ProfilerRendererInterface;
use Joomla\Profiler\ProfilerInterface;

class MyRenderer implements ProfilerRendererInterface
{
	/**
	 * Renders a profiler.
	 * We want to display the point names and the elapsed time in front of them.
	 *
	 * start : +0 seconds
	 * middle : +x seconds
	 * end : +y seconds.
	 */
	public function render(ProfilerInterface $profiler)
	{
		// Prepare the string.
		$render = '';

		// Initialize a variable containing the last point.
		$lastPoint = null;

		// Get the points in the profiler.
		$points = $profiler->getPoints();

		foreach ($points as $point)
		{
			// Get the time of the last point (if any).
			$lastTime = $lastPoint ? $lastPoint->getTime() : 0;

			$render .= sprintf('%s: %f seconds.', $point->getName(), $point->getTime() - $lastTime);
			$render .= '<br/>';

			$lastPoint = $point;
		}

		return $render;
	}
}
```

Now you can set your renderer in the Profiler :


```php
$profiler->setRenderer(new MyRenderer);

echo $profiler;
```

It should output something like :

```
Start: 0.000000 seconds.
Middle: 0.000172 seconds.
End: 0.000016 seconds.
```
