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
use DomainException;
use Throwable;

/**
 * Validation Exception
 */
class ValidationException extends DomainException {
	private ParameterInterface $parameter;

	/**
	 * Initializes the exception.
	 *
	 * @param ParameterInterface $parameter The parameter that generated the error.
	 * @param string             $message   The validation error message.
	 * @param Throwable          $previous  Previous exception (if applicable).
	 */
	public function __construct(ParameterInterface $parameter, string $message, ?Throwable $previous = null) {
		parent::__construct($message, 0, $previous);

		$this->parameter = $parameter;
	}

	/**
	 * Gets the parameter that generated the exception.
	 *
	 * @return mixed The parameter.
	 */
	public function getParameter(): mixed {
		return $this->parameter;
	}
}
