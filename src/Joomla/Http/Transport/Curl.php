<?php
/**
 * Part of the Joomla Framework Http Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http\Transport;

use Joomla\Http\TransportInterface;
use Joomla\Http\Response;
use Joomla\Uri\UriInterface;

/**
 * HTTP transport class for using cURL.
 *
 * @since  1.0
 */
class Curl implements TransportInterface
{
	/**
	 * @var    array  The client options.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * Constructor. CURLOPT_FOLLOWLOCATION must be disabled when open_basedir or safe_mode are enabled.
	 *
	 * @param   array  $options  Client options array.
	 *
	 * @see     http://www.php.net/manual/en/function.curl-setopt.php
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($options = array())
	{
		if (!function_exists('curl_init') || !is_callable('curl_init'))
		{
			throw new \RuntimeException('Cannot use a cURL transport when curl_init() is not available.');
		}

		$this->options = $options;
	}

	/**
	 * Send a request to the server and return a JHttpResponse object with the response.
	 *
	 * @param   string        $method     The HTTP method for sending the request.
	 * @param   UriInterface  $uri        The URI to the resource to request.
	 * @param   mixed         $data       Either an associative array or a string to be sent with the request.
	 * @param   array         $headers    An array of request headers to send with the request.
	 * @param   integer       $timeout    Read timeout in seconds.
	 * @param   string        $userAgent  The optional user agent string to send with the request.
	 *
	 * @return  Response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function request($method, UriInterface $uri, $data = null, array $headers = null, $timeout = null, $userAgent = null)
	{
		// Setup the cURL handle.
		$ch = curl_init();

		// Set the request method.
		$options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);

		// Don't wait for body when $method is HEAD
		$options[CURLOPT_NOBODY] = ($method === 'HEAD');

		// Initialize the certificate store
		$options[CURLOPT_CAINFO] = isset($this->options['curl.certpath']) ? $this->options['curl.certpath'] : __DIR__ . '/cacert.pem';

		// If data exists let's encode it and make sure our Content-type header is set.
		if (isset($data))
		{
			// If the data is a scalar value simply add it to the cURL post fields.
			if (is_scalar($data) || (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'multipart/form-data') === 0))
			{
				$options[CURLOPT_POSTFIELDS] = $data;
			}
			else
			// Otherwise we need to encode the value first.
			{
				$options[CURLOPT_POSTFIELDS] = http_build_query($data);
			}

			if (!isset($headers['Content-Type']))
			{
				$headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
			}

			// Add the relevant headers.
			if (is_scalar($options[CURLOPT_POSTFIELDS]))
			{
				$headers['Content-Length'] = strlen($options[CURLOPT_POSTFIELDS]);
			}
		}

		// Build the headers string for the request.
		$headerArray = array();

		if (isset($headers))
		{
			foreach ($headers as $key => $value)
			{
				$headerArray[] = $key . ': ' . $value;
			}

			// Add the headers string into the stream context options array.
			$options[CURLOPT_HTTPHEADER] = $headerArray;
		}

		// If an explicit timeout is given user it.
		if (isset($timeout))
		{
			$options[CURLOPT_TIMEOUT] = (int) $timeout;
			$options[CURLOPT_CONNECTTIMEOUT] = (int) $timeout;
		}

		// If an explicit user agent is given use it.
		if (isset($userAgent))
		{
			$options[CURLOPT_USERAGENT] = $userAgent;
		}

		// Set the request URL.
		$options[CURLOPT_URL] = (string) $uri;

		// We want our headers. :-)
		$options[CURLOPT_HEADER] = true;

		// Return it... echoing it would be tacky.
		$options[CURLOPT_RETURNTRANSFER] = true;

		// Override the Expect header to prevent cURL from confusing itself in its own stupidity.
		// Link: http://the-stickman.com/web-development/php-and-curl-disabling-100-continue-header/
		$options[CURLOPT_HTTPHEADER][] = 'Expect:';

		// Follow redirects.
		$options[CURLOPT_FOLLOWLOCATION] = (bool) (isset($this->options['follow_location']) ? $this->options['follow_location'] : true);

		// Set the cURL options.
		curl_setopt_array($ch, $options);

		// Execute the request and close the connection.
		$content = curl_exec($ch);

		// Check if the content is a string. If it is not, it must be an error.
		if (!is_string($content))
		{
			$message = curl_error($ch);

			if (empty($message))
			{
				// Error but nothing from cURL? Create our own
				$message = 'No HTTP response received';
			}

			throw new \RuntimeException($message);
		}

		// Get the request information.
		$info = curl_getinfo($ch);

		// Close the connection.
		curl_close($ch);

		return $this->getResponse($content, $info);
	}

	/**
	 * Method to get a response object from a server response.
	 *
	 * @param   string  $content  The complete server response, including headers
	 *                            as a string if the response has no errors.
	 * @param   array   $info     The cURL request information.
	 *
	 * @return  Response
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	protected function getResponse($content, $info)
	{
		// Create the response object.
		$return = new Response;

		// Get the number of redirects that occurred.
		$redirects = isset($info['redirect_count']) ? $info['redirect_count'] : 0;

		/*
		 * Split the response into headers and body. If cURL encountered redirects, the headers for the redirected requests will
		 * also be included. So we split the response into header + body + the number of redirects and only use the last two
		 * sections which should be the last set of headers and the actual body.
		 */
		$response = explode("\r\n\r\n", $content, 2 + $redirects);

		// Set the body for the response.
		$return->body = array_pop($response);

		// Get the last set of response headers as an array.
		$headers = explode("\r\n", array_pop($response));

		// Get the response code from the first offset of the response headers.
		preg_match('/[0-9]{3}/', array_shift($headers), $matches);

		$code = count($matches) ? $matches[0] : null;

		if (is_numeric($code))
		{
			$return->code = (int) $code;
		}

		// No valid response code was detected.
		else
		{
			throw new \UnexpectedValueException('No HTTP response code found.');
		}

		// Add the response headers to the response object.
		foreach ($headers as $header)
		{
			$pos = strpos($header, ':');
			$return->headers[trim(substr($header, 0, $pos))] = trim(substr($header, ($pos + 1)));
		}

		return $return;
	}

	/**
	 * Method to check if HTTP transport cURL is available for use
	 *
	 * @return  boolean  True if available, else false
	 *
	 * @since   1.0
	 */
	public static function isSupported()
	{
		return function_exists('curl_version') && curl_version();
	}
}
