<?php

/**
 * This file is part of Conductor.
 *
 * (c) Shine United LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ShineUnited\Conductor\Exception;

use DomainException;
use Throwable;

/**
 * Invalid Object Exception
 */
class InvalidObjectException extends DomainException {
	private mixed $object;

	/**
	 * Initializes the exception.
	 *
	 * @param mixed     $object   The invalid object.
	 * @param string    $class    The expected object class.
	 * @param Throwable $previous Previous exception (if applicable).
	 */
	public function __construct(mixed $object, string $class, ?Throwable $previous = null) {
		$message = 'expected object of class "' . $class . '"';
		$message .= ', instead received ';

		if (is_object($object)) {
			$message .= '"' . $object::class . '"';
		} else {
			$message .= '"' . gettype($object) . '"';
		}

		$this->object = $object;

		parent::__construct($message, 0, $previous);
	}

	/**
	 * Gets the invalid object.
	 *
	 * @return mixed The invalid object.
	 */
	public function getObject(): mixed {
		return $this->object;
	}
}
