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

/**
 * String Parameter
 */
class StringParameter extends BaseParameter {

	/**
	 * {@inheritDoc}
	 */
	public function normalizeValue(mixed $value, Configuration $config): mixed {
		return strval($value);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \InvalidArgumentException If type is invalid.
	 */
	public function validateValue(mixed $value, Configuration $config): void {
		if (is_array($value) || is_object($value) && !method_exists($value, '__toString')) {
			throw new \InvalidArgumentException('Invalid Type');
		}
	}
}
