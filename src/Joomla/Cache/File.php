<?php
/**
 * Part of the Joomla Framework Cache Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Filesystem cache driver for the Joomla Framework.
 *
 * Supported options:
 * - ttl (integer)          : The default number of seconds for the cache life.
 * - file.locking (boolean) :
 * - file.path              : The path for cache files.
 *
 * @since  1.0
 */
class File extends Cache
{
	/**
	 * Constructor.
	 *
	 * @param   mixed  $options  An options array, or an object that implements \ArrayAccess
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		if (!isset($this->options['file.locking']))
		{
			$this->options['file.locking'] = true;
		}

		$this->checkFilePath($this->options['file.path']);
	}

	/**
	 * This will wipe out the entire cache's keys....
	 *
	 * @return  boolean  The result of the clear operation.
	 *
	 * @since   1.0
	 */
	public function clear()
	{
		$filePath = $this->options['file.path'];
		$this->checkFilePath($filePath);

		$iterator = new \RegexIterator(
			new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($filePath)
			),
			'/\.data$/i'
		);

		/* @var  \RecursiveDirectoryIterator  $file */
		foreach ($iterator as $file)
		{
			if ($file->isFile())
			{
				@unlink($file->getRealPath());
			}
		}

		return true;
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  CacheItemInterface
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function get($key)
	{
		// If the cached data has expired remove it and return.
		if ($this->exists($key) && $this->isExpired($key))
		{
			try
			{
				$this->remove($key);
			}
			catch (\RuntimeException $e)
			{
				throw new \RuntimeException(sprintf('Unable to clean expired cache entry for %s.', $key), null, $e);
			}

			return new Item($key);
		}

		if (!$this->exists($key))
		{
			return new Item($key);
		}

		$resource = @fopen($this->fetchStreamUri($key), 'rb');

		if (!$resource)
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot open the resource.', $key));
		}

		// If locking is enabled get a shared lock for reading on the resource.
		if ($this->options['file.locking'] && !flock($resource, LOCK_SH))
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot obtain a lock.', $key));
		}

		$data = stream_get_contents($resource);

		// If locking is enabled release the lock on the resource.
		if ($this->options['file.locking'] && !flock($resource, LOCK_UN))
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot release the lock.', $key));
		}

		fclose($resource);

		$item = new Item($key);
		$item->setValue(unserialize($data));

		return $item;
	}

	/**
	 * Method to remove a storage entry for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 */
	public function remove($key)
	{
		return (bool) @unlink($this->fetchStreamUri($key));
	}

	/**
	 * Method to set a value for a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function set($key, $value, $ttl = null)
	{
		$fileName = $this->fetchStreamUri($key);
		$filePath = pathinfo($fileName, PATHINFO_DIRNAME);

		if (!is_dir($filePath))
		{
			mkdir($filePath, 0770, true);
		}

		$success = (bool) file_put_contents(
			$fileName,
			serialize($value),
			($this->options['file.locking'] ? LOCK_EX : null)
		);

		return $success;
	}

	/**
	 * Method to determine whether a storage entry has been set for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	protected function exists($key)
	{
		return is_file($this->fetchStreamUri($key));
	}

	/**
	 * Check that the file path is a directory and writable.
	 *
	 * @param   string  $filePath  A file path.
	 *
	 * @return  boolean  The method will always return true, if it returns.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException if the file path is invalid.
	 */
	private function checkFilePath($filePath)
	{
		if (!is_dir($filePath))
		{
			throw new \RuntimeException(sprintf('The base cache path `%s` does not exist.', $filePath));
		}
		elseif (!is_writable($filePath))
		{
			throw new \RuntimeException(sprintf('The base cache path `%s` is not writable.', $filePath));
		}

		return true;
	}

	/**
	 * Get the full stream URI for the cache entry.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  string  The full stream URI for the cache entry.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException if the cache path is invalid.
	 */
	private function fetchStreamUri($key)
	{
		$filePath = $this->options['file.path'];
		$this->checkFilePath($filePath);

		return sprintf(
			'%s/~%s/%s.data',
			$filePath,
			substr(hash('md5', $key), 0, 4),
			hash('sha1', $key)
		);
	}

	/**
	 * Check whether or not the cached data by id has expired.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean  True if the data has expired.
	 *
	 * @since   1.0
	 */
	private function isExpired($key)
	{
		// Check to see if the cached data has expired.
		if (filemtime($this->fetchStreamUri($key)) < (time() - $this->options['ttl']))
		{
			return true;
		}

		return false;
	}
}
