## The Profiler Package

The Joomla Framework provides you a Profiler to profile the time that it takes to do certain tasks or reach various milestones as your extension runs.

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

You must at least mark the first point which will be the reference.

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
Notes 0.000 seconds (+0.000); 0.00 MB (+0.000) - Start
Notes 1.000 seconds (+1.000); 3.00 MB (+3.000) - Middle
Notes 1.813 seconds (+0.813); 6.24 MB (+3.240) - End
```

You can see each line is qualified by the name you used when creating your profiler, and then the names you used for the mark.

The start point (the first marked point) is the reference, and by consequence has a null time and memory usage.

We can see that the code executed between the "Start" and the "Middle" point took 1 second to perform and increased the memory usage by 3 Mega Bytes.

## Writing your own Renderer

You can write your own renderer if you need an other formatting. In order to do so, you need to implement the ProfilerRendererInterface.

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


## Installation via Composer

Add `"joomla/profiler": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/profiler": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/profiler "dev-master"
```
