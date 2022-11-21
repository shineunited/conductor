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
use Symfony\Component\Filesystem\Path;

/**
 * Path Parameter
 */
class PathParameter extends BaseParameter {

	/**
	 * {@inheritDoc}
	 */
	public function normalizeValue(mixed $value, Configuration $config): mixed {
		return $this->convertToAbsolutePath($value);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws ValidationException If value is invalid.
	 */
	public function validateValue(mixed $value, Configuration $config): void {
		$path = $this->convertToAbsolutePath($value);

		$insideDirs = [];
		if ($this->hasOption('inside', $config)) {
			$insideDirs = $this->getOption('inside', $config);
			if (!is_array($insideDirs)) {
				$insideDirs = [$insideDirs];
			}
		}

		foreach ($insideDirs as $insideDir) {
			if (!is_string($insideDir)) {
				throw new ValidationException($this, 'Inside paths must be strings');
			}

			if ($this->isOutsideDirectory($path, $insideDir)) {
				throw new ValidationException($this, 'Path must be inside ' . $insideDir);
			}
		}

		$outsideDirs = [];
		if ($this->hasOption('outside', $config)) {
			$outsideDirs = $this->getOption('outside', $config);
			if (!is_array($outsideDirs)) {
				$outsideDirs = [$outsideDirs];
			}
		}

		foreach ($outsideDirs as $outsideDir) {
			if (!is_string($outsideDir)) {
				throw new ValidationException($this, 'Outside paths must be strings');
			}

			if ($this->isInsideDirectory($path, $outsideDir)) {
				throw new ValidationException($this, 'Path must be outside ' . $outsideDir);
			}
		}
	}

	private function convertToAbsolutePath(string $path): string {
		$path = Path::canonicalize($path);

		if (Path::isAbsolute($path)) {
			return $path;
		}

		return Path::makeAbsolute($path, getcwd());
	}

	private function isInsideDirectory(string $path, string $dir): bool {
		$path = $this->convertToAbsolutePath($path);
		$dir = $this->convertToAbsolutePath($dir);

		if (Path::isBasePath($dir, $path)) {
			return true;
		}

		return false;
	}

	private function isOutsideDirectory(string $path, string $dir): bool {
		if ($this->isInsideDirectory($path, $dir)) {
			return false;
		}

		return true;
	}
}
