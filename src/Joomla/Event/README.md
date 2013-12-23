# The Event Package

The event package provides foundations to build event systems and an implementation supporting prioritized listeners.

## Events

### Example

An event has a name and can transport arguments.

```php
namespace MyApp;

use Joomla\Event\Event;

// Creating an Event called "onSomething".
$event = new Event('onSomething');

// Adding an argument named "foo" with value "bar".
$event->addArgument('foo', 'bar');

// Setting the "foo" argument with a new value.
$event->setArgument('foo', new \stdClass);

// Getting the "foo" argument value.
$foo = $event->getArgument('foo');
```

Its propagation can be stopped

```php
$event->stop();
```

## Event Listeners

Event listeners can be any php [callable](http://www.php.net/manual/en/language.types.callable.php).

**It MUST take an EventInterface (or children) as unique parameter.**

## The Dispatcher

The Dispatcher is the central point of the Event system, it manages the registration of Events, listeners and the triggering of Events.

### Registering Listeners

```php
namespace MyApp;

use Joomla\Event\Dispatcher;
use Joomla\Event\Priority;

// Of course, it shouldn't be empty.
$listener = function (EventInterface $event) {
};

$dispatcher = new Dispatcher;

/**
 * Here, we register it for the onContentSave event.
 */
$dispatcher->addListener(
	$listener,
	'onContentSave'
);
```

### Registration with Priority

```php

$dispatcher->addListener(
	$listener,
	'onContentSave',
	Priority::ABOVE_NORMAL
);
```

The default priority is `Priority::NORMAL`.

If some listeners have the same priority for a given event, they will be called in the order they were added to the Dispatcher.

### Registering Events

You can register Events to the Dispatcher, if you need custom ones.

```php
namespace MyApp;

use Joomla\Event\Dispatcher;
use Joomla\Event\Event;

// Creating an event with a "foo" argument.
$event = new Event('onBeforeContentSave');
$event->setArgument('foo', 'bar');

// Registering the event to the Dispatcher.
$dispatcher = new Dispatcher;
$dispatcher->addEvent($event);
```

By default, an `Event` object is created with no arguments, when triggering the Event.

## Triggering Events

Once you registered your listeners (and eventually events to the Dispatcher), you can trigger the events.

The listeners will be called in a queue according to their priority for that Event.

```php
// Triggering the onAfterSomething Event.
$dispatcher->triggerEvent('onAfterSomething');
```

If you registered an Event object having the `onAfterSomething` name, then it will be passed to all listeners instead of the default one.

You can also pass a custom Event when triggering it

```php
namespace MyApp;

use Joomla\Event\Dispatcher;
use Joomla\Event\Event;

// Creating an event called "onAfterSomething" with a "foo" argument.
$event = new Event('onAfterSomething');
$event->setArgument('foo', 'bar');

$dispatcher = new Dispatcher;

// Triggering the onAfterSomething Event.
$dispatcher->triggerEvent($event);
```

If you already added an Event with the onAfterSomething name using `addEvent`, then the event passed to the `triggerEvent` method will be chosen instead.

## Stopping the Propagation

As said above, you can stop the Event propagation if you are listening to an Event supporting it, it is the case for the `Event` class.

```php
namespace MyApp;

use Joomla\Event\Event;

class ContentListener
{
	public function onBeforeContentSave(Event $event)
	{
		// Stopping the Event propagation.
		$event->stop();
	}
}
```

When stopping the Event propagation, the next listeners in the queue won't be called.

## Observable classes

Observable classes depend on a Dispatcher, and they may implement the `DispatcherAwareInterface` interface.

Example of a Model class :

```php
namespace MyApp;

use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\Event;

class ContentModel implements DispatcherAwareInterface
{
	const ON_BEFORE_SAVE_EVENT = 'onBeforeSaveEvent';
	const ON_AFTER_SAVE_EVENT = 'onAfterSaveEvent';

	/**
	 * The underlying dispatcher.
	 *
	 * @var  DispatcherInterface
	 */
	protected $dispatcher;

	public function save()
	{
		$this->dispatcher->triggerEvent(self::ON_BEFORE_SAVE_EVENT);

		// Perform the saving.

		$this->dispatcher->triggerEvent(self::ON_AFTER_SAVE_EVENT);
	}

	/**
	 * Set the dispatcher to use.
	 *
	 * @param   DispatcherInterface  $dispatcher  The dispatcher to use.
	 *
	 * @return  DispatcherAwareInterface  This method is chainable.
	 */
	public function setDispatcher(DispatcherInterface $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}
}
```

## Immutable Events

An immutable event cannot be modified after its instanciation:

- its arguments cannot be modified
- its propagation can't be stopped

It is useful when you don't want the listeners to manipulate it (they can only inspect it).

```php
namespace MyApp;

use Joomla\Event\EventImmutable;

// Creating an immutable event called onSomething with an argument "foo" with value "bar"
$event = new EventImmutable('onSomething', array('foo' => 'bar'));
```

## The Delegating Dispatcher

A dispatcher that delegates its method to an other Dispatcher. It is an easy way to achieve immutability for a Dispatcher.

```php
namespace MyApp;

use Joomla\Event\DelegatingDispatcher;
use Joomla\Event\Dispatcher;

$dispatcher = new Dispatcher;

// Here you add you listeners and your events....

// Instanciating a delegating dispatcher.
$delegatingDispatcher = new DelegatingDispatcher($dispatcher);

// Now you inject this dispatcher in your system, and it has only the triggerEvent method.
```

This is useful when you want to make sure that 3rd party applications, won't register or remove listeners from the Dispatcher.

## Installation via Composer

Add `"joomla/event": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/event": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/event "dev-master"
```
