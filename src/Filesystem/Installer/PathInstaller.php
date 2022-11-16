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

namespace ShineUnited\Conductor\Filesystem\Installer;

use ShineUnited\Conductor\Configuration\Configuration;
use Composer\Package\PackageInterface;
use Symfony\Component\Filesystem\Path;

/**
 * A simple path installer.
 */
class PathInstaller implements InstallerInterface {
	private array $types;
	private mixed $installPath;
	private int $priority;

	/**
	 * Initializes the installer.
	 *
	 * @param string|string[] $types       Supported type(s).
	 * @param string|callable $installPath Expects to be processed.
	 * @param integer         $priority    The installer priority, lower is higher priority.
	 */
	public function __construct(string|array $types, string|callable $installPath, int $priority = 1) {
		if (is_array($types)) {
			$this->types = $types;
		} else {
			$this->types = [$types];
		}

		$this->installPath = $installPath;
		$this->priority = $priority;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPriority(): int {
		return $this->priority;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSupportedTypes(): array {
		return $this->types;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getInstallPath(PackageInterface $package, Configuration $config): string {
		list($vendor, $name) = explode('/', $package->getName());

		$path = $config->processValue($this->installPath, [
			'vendor' => $vendor,
			'name'   => $name
		]);

		$path = Path::canonicalize($path);

		if (!Path::isAbsolute($path)) {
			$path = Path::makeAbsolute($path, $config['working-dir']);
		}

		return $path;
	}
}
