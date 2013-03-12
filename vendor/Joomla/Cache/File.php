<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Joomla\Registry\Registry;

/**
 * Filesystem cache driver for the Joomla Platform.
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
	 * @param   Registry  $options  Caching options object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct(Registry $options = null)
	{
		parent::__construct($options);

		$this->options->def('file.locking', true);
		$this->checkFilePath($this->options->get('file.path'));
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
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doGet($key)
	{
		// If the cached data has expired remove it and return.
		if ($this->exists($key) && $this->isExpired($key))
		{
			try
			{
				$this->doDelete($key);
			}
			catch (\RuntimeException $e)
			{
				throw new \RuntimeException(sprintf('Unable to clean expired cache entry for %s.', $key), null, $e);
			}

			return;
		}

		if (!$this->exists($key))
		{
			return;
		}

		$resource = @fopen($this->fetchStreamUri($key), 'rb');

		if (!$resource)
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot open the resource.', $key));
		}

		// If locking is enabled get a shared lock for reading on the resource.
		if ($this->options->get('file.locking') && !flock($resource, LOCK_SH))
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot obtain a lock.', $key));
		}

		$data = stream_get_contents($resource);

		// If locking is enabled release the lock on the resource.
		if ($this->options->get('file.locking') && !flock($resource, LOCK_UN))
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s.  Connot release the lock.', $key));
		}

		fclose($resource);

		return unserialize($data);
	}

	/**
	 * Method to remove a storage entry for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doDelete($key)
	{
		$success = (bool) @unlink($this->fetchStreamUri($key));

		if (!$success)
		{
			throw new \RuntimeException(sprintf('Unable to remove cache entry for %s.', $key));
		}
	}

	/**
	 * Method to set a value for a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doSet($key, $value, $ttl = null)
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
			($this->options->get('file.locking') ? LOCK_EX : null)
		);

		if (!$success)
		{
			throw new \RuntimeException(sprintf('Unable to set cache entry for %s.', $value));
		}
	}

	/**
	 * Check that the file path is a directory and writable.
	 *
	 * @param   string  $filePath  A file path.
	 *
	 * @return  void
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
		$filePath = $this->options->get('file.path');
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
		if (filemtime($this->fetchStreamUri($key)) < (time() - $this->options->get('ttl')))
		{
			return true;
		}

		return false;
	}
}
