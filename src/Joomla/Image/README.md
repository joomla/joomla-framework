## The Image Package

This package comprises of 2 main classes, `Image` and `ImageFilter` which has 8 filter sub-classes that it can use to apply a desired filter to your image. `Image` depends on the `GD` php extension to be loaded on your server. More information on `GD` can be found at: http://php.net/manual/en/book.image.php

Manipulating images in raw PHP using the `GD` image* functions requires a lot of boilerplate code. The intent of this package is to handle those requirements and make it simple for developers to accomplish those tasks through easy to use (and remember) methods.

All classes in this package are supported by the auto-loader so can be invoked at any time.


### Construction

When creating a new `Image` object, the constructor will check that the `gd` extension is loaded, and throw a `RuntimeException` if it is not.

The constructor takes a single optional `$source` parameter. This argument can be one of two things:

- A variable containing an existing, valid image resource created using a `imagecreate*` method.
- A string containing a valid, absolute path to an image

If you choose the first option, the class sets the protected property `$handle` to the provided image resource.
If you choose the second option, the class will call the `loadFile` method, passing along the `$source` parameter.

```php
use Joomla\Image\Image;

// Creating a new Image object, passing it an existing handle.
$resource = imagecreate(100, 100);
$image = new Image($resource);

// Creating a new Image object, passing it an image path
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');

// Creating a new Image object then manually calling `loadFile`
$image = new Image;
$image->loadFile(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');
```

### Usage

Keep in mind that most public methods return a `Image` instance with a valid image handle for easy method chaining. The examples for each method will break each method call out to be able to comment on what the code is doing, but production code can be chained like so (if you prefer):

```php
use Joomla\Image\Image;

$image = new Image();
$image->loadFile(JPATH_ROOT . '/path/to/image.png')
	->crop(600, 250)
	->toFile(JPATH_ROOT . '/tmp/image.png');
```

Since Platform version 12.3, there is a new `destroy()` method that gets called in appropriate places throughout the class which runs the `imagedestroy` function to free memory associated with an image handle. This method is called before each time an image handle is replaced (when `$createNew` is set to false) as well as in the class `__descruct` method as a final cleanup.

#### The `resize` method
__Accepted Parameters__

- `$width`: The width of the resized image in pixels or a percentage.
- `$height`: The height of the resized image in pixels or a percentage.
- `$createNew`: If true the current image will be cloned, resized and returned; else the current image will be resized and returned.
- `$scaleMethod`: Which method to use for scaling

Example: Using `Image::resize()` to generate a resized image.

```php
use Joomla\Image\Image;

// Create our image object
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');

// Resize the image using the SCALE_INSIDE method
$image->resize(300, 150, true, Image::SCALE_INSIDE);

// Write it to disk
$image->toFile(JPATH_ROOT . '/tmp/bar_resized.png');

```


#### The `crop` method
__Accepted Parameters__

- `$width`: The width of the image section to crop in pixels or a percentage.
- `$height`: The height of the image section to crop in pixels or a percentage.
- `$left`: The number of pixels from the left to start cropping.
- `$top`: The number of pixels from the top to start cropping.
- `$createNew`: If true the current image will be cloned, cropped and returned; else the current image will be cropped and returned.

Example: Using `Image::crop()` to generate a cropped image.

```php
use Joomla\Image\Image;

// Create our image object
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');

// Crop the image to 150px square, starting 10 pixels from the left, and 20 pixels from the top
$image->crop(150, null, 10, 20);

// Write it to disk
$image->toFile(JPATH_ROOT . '/tmp/bar_cropped.png');
```

To crop in image after resizing it to maintain proportions use `cropResize` method with familiar arguments `$width`, `$height` and `$createNew`.


#### The `createThumbs` method
__Accepted Parameters__

- `$thumbSizes`: String or array of strings. Example: $thumbSizes = array('150x75','250x150');
- `$creationMethod`: See __Resize Methods__ below.
- `$thumbsFolder`: Destination for thumbnails. Passing null generates a thumbs folder in the loaded image's containing folder.

Example: Using `Image::createThumbs()` to generate thumbnails of an image.

```php
use Joomla\Image\Image;

// Set the desired sizes for our thumbnails.
$sizes = array('300x300', '64x64', '250x125');

// Create our object
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/uploadedImage.jpg');

// Create the thumbnails
$image->createThumbs($sizes, Image::SCALE_INSIDE);
```

In this example, we use the `createThumbs` method of `Image`. This method takes 2 parameters. The first parameter can be a string containing a single size in `WIDTHxHEIGHT` format, or it can be an array of sizes in the format (as shown in the example). The second parameter specifies the resize method. (See Resize Methods below)

To receive Image instances without saving them to disk, use `generateThumbs` method with arguments `$thumbSizes` and `$creationMethod`.


#### Resize Methods

The `resize`, `createThumbs` and `generateThumbs` methods take an optional parameter that defines what method to use when scaling an image.
This parameter can be one of the following:

- `Image::SCALE_FILL` - Gives you a thumbnail of the exact size, stretched or squished to fit the parameters.
- `Image::SCALE_INSIDE` - Fits your thumbnail within your given parameters. It will not be any taller or wider than the size passed, whichever is larger.
- `Image::SCALE_OUTSIDE` - Fits your thumbnail to the given parameters. It will be as tall or as wide as the size passed, whichever is smaller.
- `Image::SCALE_FIT` - Fits your thumbnail to given boundaries maintaining aspect ratio. Result will be aligned vertically to center and horizontally to middle.
- `Image::CROP` - Gives you a thumbnail of the exact size, cropped from the center of the full sized image.
- `Image::CROP_RESIZE` - As above, but gives a clean resize and crop from center.


#### The `toFile` method
__Accepted Parameters__

- `$path`: The filesystem path to save the image.
           When null, the raw image stream will be outputted directly.
- `$type`: The image type to save the file as (`IMAGETYPE_GIF`|`IMAGETYPE_PNG`|`IMAGETYPE_JPEG`).
- `$options`: The image type options to use in saving the file.
              Use `quality` key to set compression level (0..9 for PNGs and 0..100 for JPEGs)

Example: Using `Image::toFile()` to save the image as JPEG with 65 compression level

```php
use Joomla\Image\Image;

// Create our object
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');

// Write to disk
$image->toFile(JPATH_ROOT . '/tmp/bar.jpg', IMAGETYPEJPEG, array('options' => 65));

```

Example: Using `Image::toFile()` to retrieve data blob of an image.

```php
use Joomla\Image\Image;

// Create our object
$image = new Image(JPATH_ROOT . '/media/com_foo/images/uploads/bar.png');

// Enable output buffering
ob_start();

// Retrieve data blob
$image->toFile(null, IMAGETYPE_PNG);
$imageBlob = ob_get_clean();
```


## Installation via Composer

Add `"joomla/image": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/image": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/image "dev-master"
```
