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
 * Float Parameter
 */
class FloatParameter extends FilterParameter {

	/**
	 * {@inheritDoc}
	 */
	protected function getNormalizeFilter(Configuration $config): int {
		return FILTER_VALIDATE_FLOAT;
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
		$options = [];

		if ($this->hasOption('decimal', $config)) {
			$options['decimal'] = $this->getOption('decimal', $config);
		}

		return $options;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getValidateOptions(Configuration $config): array {
		$options = $this->getNormalizeOptions($config);

		if ($this->hasOption('min', $config)) {
			$options['min_range'] = $this->getOption('min', $config);
		}

		if ($this->hasOption('max', $config)) {
			$options['max_range'] = $this->getOption('max', $config);
		}

		return $options;
	}
}
