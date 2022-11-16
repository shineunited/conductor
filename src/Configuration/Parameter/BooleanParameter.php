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
 * Boolean Parameter
 */
class BooleanParameter extends FilterParameter {

	/**
	 * {@inheritDoc}
	 */
	protected function getNormalizeFilter(Configuration $config): int {
		return FILTER_VALIDATE_BOOLEAN;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getValidateFilter(Configuration $config): int {
		return $this->getNormalizeFilter($config);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getNormalizeOptions(Configuration $config): array {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getValidateOptions(Configuration $config): array {
		return $this->getNormalizeOptions($config);
	}
}
