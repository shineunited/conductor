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

interface InstallerInterface {

	/**
	 * Get the priority for the installer.
	 *
	 * @return integer The installer priority (lower is higher priority).
	 */
	public function getPriority(): int;

	/**
	 * Get a list of supported package types.
	 *
	 * @return string[] Supported package types.
	 */
	public function getSupportedTypes(): array;

	/**
	 * Get the install path for a given package.
	 *
	 * @param PackageInterface $package Package to install.
	 * @param Configuration    $config  Conductor configuration.
	 *
	 * @return string The install path for the provided package.
	 */
	public function getInstallPath(PackageInterface $package, Configuration $config): string;
}
