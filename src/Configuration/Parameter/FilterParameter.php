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

use ShineUnited\Conductor\Exception\Configuration\ValidationException;
use ShineUnited\Conductor\Configuration\Configuration;

/**
 * Abstract filter_var based parameter class
 */
abstract class FilterParameter extends BaseParameter {

	/**
	 * {@inheritDoc}
	 */
	public function normalizeValue(mixed $value, Configuration $config): mixed {
		$filter = $this->getNormalizeFilter($config);
		$options = $this->getNormalizeOptions($config);

		return filter_var($value, $filter, [
			'options' => $options
		]);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws ValidationException If value is invalid.
	 */
	public function validateValue(mixed $value, Configuration $config): void {
		$filter = $this->getValidateFilter($config);
		$options = $this->getValidateOptions($config);

		$test = filter_var($value, $filter, [
			'options' => $options,
			'flags'   => FILTER_NULL_ON_FAILURE
		]);

		if (is_null($test)) {
			throw new ValidationException($this, 'Invalid value');
		}
	}

	/**
	 * Get normalize filter int (filter_var).
	 *
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return integer Filter integer to use for normalization.
	 */
	abstract protected function getNormalizeFilter(Configuration $config): int;

	/**
	 * Get validate filter int (filter_var).
	 *
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return integer Filter integer to use for validation.
	 */
	abstract protected function getValidateFilter(Configuration $config): int;

	/**
	 * Get normalize filter options (filter_var).
	 *
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return integer[] Filter options to use for normalization.
	 */
	abstract protected function getNormalizeOptions(Configuration $config): array;

	/**
	 * Get validate filter options (filter_var).
	 *
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return integer[] Filter options to use for validation.
	 */
	abstract protected function getValidateOptions(Configuration $config): array;
}
