<?php
/**
 * Part of the Joomla Framework Archive Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive;

use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * An Archive handling class
 *
 * @since  1.0
 */
class Archive implements LoggerAwareInterface
{
	/**
	 * The array of instantiated archive adapters.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $adapters = array();

	/**
	 * A logger.
	 *
	 * @var    LoggerInterface
	 * @since  1.0
	 */
	private $logger;

	/**
	 * Holds the options array.
	 *
	 * @var    mixed  Array or object that implements \ArrayAccess
	 * @since  1.0
	 */
	public $options = array();

	/**
	 * Create a new Archive object.
	 *
	 * @param  mixed  $options  An array of options or an object that implements \ArrayAccess
	 *
	 * @since  1.0
	 */
	public function __construct($options = array())
	{
		$this->options = $options;
	}
	

	/**
	 * Extract an archive file to a directory.
	 *
	 * @param   string  $archivename  The name of the archive file
	 * @param   string  $extractdir   Directory to unpack into
	 *
	 * @return  boolean  True for success
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function extract($archivename, $extractdir)
	{
		$result = false;
		$ext = pathinfo($archivename, PATHINFO_EXTENSION);
		$filename = pathinfo($archivename, PATHINFO_FILENAME);
		$path = pathinfo($archivename, PATHINFO_DIRNAME);

		switch ($ext)
		{
			case 'zip':
				$result = $this->getAdapter('zip')->extract($archivename, $extractdir);
				break;

			case 'tar':
				$result = $this->getAdapter('tar')->extract($archivename, $extractdir);
				break;

			case 'tgz':
			case 'gz':
			case 'gzip':
				// This may just be an individual file (e.g. sql script)
				$tmpfname = $this->options['tmp_path'] . '/' . uniqid('gzip');
				$gzresult = $this->getAdapter('gzip')->extract($archivename, $tmpfname);

				if ($gzresult instanceof \Exception)
				{
					@unlink($tmpfname);

					return false;
				}

				if ($ext === 'tgz' || stripos($filename, '.tar') !== false)
				{
					$result = $this->getAdapter('tar')->extract($tmpfname, $extractdir);
				}
				else
				{
					Folder::create($path);
					$result = File::copy($tmpfname, $extractdir, null, 1);
				}

				@unlink($tmpfname);

				break;

			case 'tbz2':
			case 'bz2':
			case 'bzip2':
				// This may just be an individual file (e.g. sql script)
				$tmpfname = $this->options['tmp_path'] . '/' . uniqid('bzip2');
				$bzresult = $this->getAdapter('bzip2')->extract($archivename, $tmpfname);

				if ($bzresult instanceof \Exception)
				{
					@unlink($tmpfname);

					return false;
				}

				if ($ext === 'tbz2' || stripos($filename, '.tar') !== false)
				{
					$result = $this->getAdapter('tar')->extract($tmpfname, $extractdir);
				}
				else
				{
					Folder::create($path);
					$result = File::copy($tmpfname, $extractdir, null, 1);
				}

				@unlink($tmpfname);

				break;

			default:
				throw new \InvalidArgumentException(sprintf('Unknown archive type: %s', $ext));
		}

		if (!$result || $result instanceof \Exception)
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to override the provided adapter with your own implementation.
	 *
	 * @param   string  $type      Name of the adapter to set.
	 * @param   object  $adapter   Class which implements ExtractableInterface.
	 * @param   object  $override  True to force override the adapter type.
	 *
	 * @return  Archive  This object for chaining.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function setAdapter($type, $adapter, $override = true)
	{
		if (!($adapter instanceof ExtractableInterface))
		{
			throw new \InvalidArgumentException(sprintf('The provided %s adapter %s must implement Joomla\\Archive\\ExtractableInterface', $type), 500);
		}

		if ($override || !isset($this->adapters[$type]))
		{
			$this->adapters[$type] = $value;
		}

		return $this;
	}

	/**
	 * Get a file compression adapter.
	 *
	 * @param   string  $type  The type of adapter (bzip2|gzip|tar|zip).
	 *
	 * @return  Joomla\Archive\ExtractableInterface  Adapter for the requested type
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getAdapter($type)
	{
		$type = strtolower($type);

		if (!isset($this->adapters[$type]))
		{
			// Try to load the adapter object
			$class = 'Joomla\\Archive\\' . ucfirst($type);

			if (!class_exists($class) || !$class::isSupported())
			{
				throw new \UnexpectedValueException(sprintf('Archive adapter %s not found or supported.', $type), 500);
			}

			$this->adapters[$type] = new $class($this->options);
		}

		return $this->adapters[$type];
	}

	/**
	 * Get the logger.
	 *
	 * @return  LoggerInterface
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getLogger()
	{
		if ($this->hasLogger())
		{
			return $this->logger;
		}

		throw new \UnexpectedValueException('Logger not set in ' . __CLASS__);
	}

	/**
	 * Checks if a logger is available.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function hasLogger()
	{
		return ($this->logger instanceof LoggerInterface);
	}

	/**
	 * Set the logger.
	 *
	 * @param   LoggerInterface  $logger  The logger.
	 *
	 * @return  Archive  This object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;

		return $this;
	}
}
