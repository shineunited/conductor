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

namespace ShineUnited\Conductor\Configuration\Parameter;

use ShineUnited\Conductor\Configuration\Configuration;
use ShineUnited\Conductor\Exception\Configuration\ValidationException;

/**
 * Parameter Interface
 */
interface ParameterInterface {

	/**
	 * Get the name of the parameter.
	 *
	 * @return string The parameter name.
	 */
	public function getName(): string;

	/**
	 * Get the default value of the parameter.
	 *
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return mixed The default value.
	 */
	public function getDefault(Configuration $config): mixed;

	/**
	 * Check if the parameter value is locked to the default.
	 *
	 * @return boolean True if the parameter is locked.
	 */
	public function isLocked(): bool;

	/**
	 * Normalize a value.
	 *
	 * @param mixed         $value  The value to normalize.
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return mixed The normalized value.
	 */
	public function normalizeValue(mixed $value, Configuration $config): mixed;

	/**
	 * Validate a value.
	 *
	 * @param mixed         $value  The value to validate.
	 * @param Configuration $config Conductor configuration.
	 *
	 * @throws ValidationException For invalid values.
	 *
	 * @return void
	 */
	public function validateValue(mixed $value, Configuration $config): void;
}
