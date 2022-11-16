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

namespace ShineUnited\Conductor\Filesystem;

use ShineUnited\Conductor\Capability\InstallerProvider as InstallerProviderCapability;
use ShineUnited\Conductor\Filesystem\Installer\InstallerInterface;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use React\Promise\PromiseInterface;

/**
 * Installation Manager for Conductor
 */
class InstallationManager extends LibraryInstaller {
	private Composer $fullComposer;
	private Configuration $configuration;
	private array $loadedPlugins = [];
	private array $ignoredTypes = [];
	private array $installers = [];

	/**
	 * Initializes the installation manager.
	 *
	 * @param IOInterface   $io            IO Interface object.
	 * @param Composer      $composer      Composer object.
	 * @param Configuration $configuration Conductor config.
	 */
	public function __construct(IOInterface $io, Composer $composer, Configuration $configuration) {
		parent::__construct($io, $composer);

		$this->fullComposer = $composer;
		$this->configuration = $configuration;
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports(string $packageType) {
		if (in_array($packageType, $this->ignoredTypes)) {
			return false;
		}

		if (isset($this->installers[$packageType])) {
			return true;
		}

		$this->loadInstallers();

		if (isset($this->installers[$packageType])) {
			return true;
		}

		$this->ignoredTypes[] = $packageType;

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getInstallPath(PackageInterface $package): string {
		$type = $package->getType();

		$path = $this->installers[$type]->getInstallPath($package, $this->configuration);

		return $path;
	}

	private function loadInstallers(): void {
		$pluginManager = $this->fullComposer->getPluginManager();

		foreach ($pluginManager->getPlugins() as $plugin) {
			if (in_array($plugin::class, $this->loadedPlugins)) {
				continue;
			}
			$this->loadedPlugins[] = $plugin::class;

			if (!$plugin instanceof Capable) {
				continue;
			}

			$installerProvider = $pluginManager->getPluginCapability($plugin, InstallerProviderCapability::class, [
				'composer' => $this->fullComposer,
				'io'       => $this->io
			]);

			if (is_null($installerProvider)) {
				continue;
			}

			foreach ($installerProvider->getInstallers() as $installer) {
				if (!$installer instanceof InstallerInterface) {
					throw new \Exception('Invalid installer');
				}

				foreach ($installer->getSupportedTypes() as $type) {
					if (isset($this->installers[$type])) {
						if ($this->installers[$type]->getPriority() >= $installer->getPriority()) {
							// existing installer is greater or equal priority, keep it instead
							continue;
						}
					}

					$this->installers[$type] = $installer;

					if (in_array($type, $this->ignoredTypes)) {
						throw new \Exception('Previously ignored type: ' . $type . ' is now supported, check install order...');
					}
				}
			}
		}
	}
}
