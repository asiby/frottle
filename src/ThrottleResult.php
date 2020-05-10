<?php
/**
 * @package     camcloud\api\v2\sse\lib
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace ASiby;

use Exception;

class ThrottleResult
{
	private $result;
	private $isEmpty;

	public function __construct($value, $isEmpty = false)
	{
		$this->result = $value;
		$this->isEmpty = $isEmpty;
	}

	public function getResult() {
		return $this->result;
	}

	public function getValue() {
		return $this->getResult();
	}

	public function isEmpty() {
		return $this->isEmpty;
	}

	public function then(callable $callback = null) {
		if (!$this->isEmpty) {
			if (is_callable($callback))
			{
				return $callback($this->getResult());
			}
			return $this->getResult();
		}

		return null;
	}

	/**
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function __toString() {
		if (is_string($this->result)) {
			return $this->result;
		}

		try
		{
			return (string) $this->result;
		} catch (Exception $exception) {
			$message = "The result cannot be converted to a string.";
			if (version_compare(PHP_VERSION, '7.4') >= 0)
			{
				/** @noinspection PhpLanguageLevelInspection */
				throw new Exception($message, 500);
			} else {
				error_log($message);
				return '';
			}
		}
	}
}