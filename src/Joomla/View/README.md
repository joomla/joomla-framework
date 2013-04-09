# The View Package

## Interfaces

### `View\View`

`View\View` is an interface that requires a class to be implemented with an
`escape` and a `render` method.

## Classes

# `View\AbstractView`

##### Construction

The contructor for `View\AbstractView` takes a mandatory `Model\Model` parameter.

Note that `Model\Model` is an interface so the actual object passed does
necessarily have to extend from `Model\Base` class. Given that, the view
should only rely on the API that is exposed by the interface and not
concrete classes unless the constructor is changed in a derived class to
take more explicit classes or interfaces as required by the developer.

##### Usage

The `View\AbstractView` class is abstract so cannot be used directly. It forms a
simple base for rendering any kind of data. The class already implements
the escape method so only a render method need to be added. Views
derived from this class would be used to support very simple cases, well
suited to supporting web services returning JSON, XML or possibly binary
data types. This class does not support layouts.

```php
/**
 * My custom view.
 *
 * @package  Examples
 *
 * @since   1.0
 */
 
namespace myApp;

use Joomla\View\AbstractView;

class MyView extends AbstractView
{
	/**
	 * Render some data
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   1.0
	 * @throws  RuntimeException on database error.
	 */
	public function render()
	{
		// Prepare some data from the model.
		$data = array('count' => $this->model->getCount());

		// Convert the data to JSON format.
		return json_encode($data);
	}
}

try
{
	$view = new MyView(new MyDatabaseModel());
	echo $view->render();
}
catch (RuntimeException $e)
{
	// Handle database error.
}
```

## `View\Html`

##### Construction

`View\Html` is extended from `View\AbstractBase`. The constructor, in addition to
the required model argument, take an optional `SplPriorityQueue` object
that serves as a lookup for layouts. If omitted, the view defers to the
protected loadPaths method.

##### Usage

The `View\Html` class is abstract so cannot be used directly. This view
class implements render. It will try to find the layout, include it
using output buffering and return the result. The following examples
show a layout file that is assumed to be stored in a generic layout
folder not stored under the web-server root.

```php
<?php
/**
 * Example layout "layouts/count.php".
 *
 * @package  Examples
 * @since    1.0
 */

namespace myApp;

use Joomla\View;

// Declare variables to support type hinting.

/** @var $this MyHtmlView */
?>

<dl>
	<dt>Count</dt>
	<dd><?php echo $this->model->getCount(); ?></dd>
</dl>
```

```php
/**
 * My custom HTML view.
 *
 * @package  Examples
 * @since    1.0
 */
class MyHtmlView extends View\Html
{
	/**
	 * Redefine the model so the correct type hinting is available in the layout.
	 *
	 * @var     MyDatabaseModel
	 * @since   1.0
	 */
	protected $model;
}

try
{
	$paths = new SplPriorityQueue;
	$paths->insert(__DIR__ . '/layouts');

	$view = new MyView(new MyDatabaseModel, $paths);
	$view->setLayout('count');
	echo $view->render();

	// Alternative approach.
	$view = new MyView(new MyDatabaseModel);

	// Use some chaining.
	$view->setPaths($paths)->setLayout('count');

	// Take advantage of the magic __toString method.
	echo $view;
}
catch (RuntimeException $e)
{
	// Handle database error.
}
```

The default extension for layouts is ".php". This can be modified in derived views by changing the default value for the extension argument. For example:

```php
/**
 * My custom HTML view.
 *
 * @package  Examples
 * @since    1.0
 */
 
namespace myApp;

use Joomla\View;

class MyHtmlView extends View\Html
{
	/**
	 * Override the parent method to use the '.phtml' extension for layout files.
	 *
	 * @param   string  $layout  The base name of the layout file (excluding extension).
	 * @param   string  $ext     The extension of the layout file (default: "phtml").
	 *
	 * @return  mixed  The layout file name if found, false otherwise.
	 *
	 * @see     JViewHtml::getPath
	 * @since   1.0
	 */
	public function getPath($layout, $ext = 'phtml')
	{
		return parent::getPath($layout, $ext);
	}
}
```
