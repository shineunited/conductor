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
 * Select Parameter
 */
class SelectParameter extends BaseParameter {

	/**
	 * {@inheritDoc}
	 */
	public function normalizeValue(mixed $value, Configuration $config): mixed {
		return $value;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws ValidationException If value is invalid.
	 */
	public function validateValue(mixed $value, Configuration $config): void {
		$options = [];
		if ($this->hasOption('options', $config)) {
			$options = $this->getOption('options', $config);
		}

		if (!in_array($value, $options)) {
			throw new ValidationException($this, 'Invalid option: ' . $value);
		}
	}
}
