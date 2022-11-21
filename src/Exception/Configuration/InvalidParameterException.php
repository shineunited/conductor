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

namespace ShineUnited\Conductor\Exception\Configuration;

use ShineUnited\Conductor\Configuration\Parameter\ParameterInterface;
use ShineUnited\Conductor\Exception\InvalidObjectException;
use Throwable;

/**
 * Invalid Parameter Exception
 */
class InvalidParameterException extends InvalidObjectException {

	/**
	 * Initializes the exception.
	 *
	 * @param mixed     $object   The invalid parameter.
	 * @param string    $class    The expected parameter class.
	 * @param Throwable $previous Previous exception (if applicable).
	 */
	public function __construct(mixed $object, string $class = ParameterInterface::class, ?Throwable $previous = null) {
		parent::__construct($object, $class, $previous);
	}
}
