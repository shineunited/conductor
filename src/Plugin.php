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

namespace ShineUnited\Conductor;

use ShineUnited\Conductor\Configuration\Configuration;
use ShineUnited\Conductor\Capability\GeneratorProvider as GeneratorProviderCapability;
use ShineUnited\Conductor\Filesystem\Generator\GeneratorInterface;
use ShineUnited\Conductor\Filesystem\InstallationManager;
use ShineUnited\Conductor\Filesystem\Filesystem;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event as ScriptEvent;

/**
 * Composer Plugin
 */
class Plugin implements PluginInterface, EventSubscriberInterface {
	private Composer $composer;
	private IOInterface $io;
	private Configuration $configuration;

	/**
	 * {@inheritDoc}
	 */
	public function activate(Composer $composer, IOInterface $io): void {
		$this->composer = $composer;
		$this->io = $io;
		$this->configuration = $this->createConfiguration($composer, $io);

		$installationManager = $this->createInstallationManager($io, $composer, $this->configuration);
		$composerInstallationManager = $composer->getInstallationManager();
		$composerInstallationManager->addInstaller($installationManager);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deactivate(Composer $composer, IOInterface $io): void {
		// do nothing
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall(Composer $composer, IOInterface $io): void {
		// do nothing
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents(): array {
		return [
			ScriptEvents::POST_AUTOLOAD_DUMP => 'generateFiles'
		];
	}

	/**
	 * Creates a configuration object.
	 *
	 * @param Composer    $composer Composer object.
	 * @param IOInterface $io       IO interface object.
	 *
	 * @return Configuration The configuration object.
	 */
	protected function createConfiguration(Composer $composer, IOInterface $io): Configuration {
		return new Configuration($composer, $io);
	}

	/**
	 * Creates an installation manager object.
	 *
	 * @param IOInterface   $io            IO interface object.
	 * @param Composer      $composer      Composer object.
	 * @param Configuration $configuration Conductor configuration.
	 *
	 * @return InstallationManager The installation manager object.
	 */
	protected function createInstallationManager(IOInterface $io, Composer $composer, Configuration $configuration): InstallationManager {
		return new InstallationManager($io, $composer, $configuration);
	}

	/**
	 * Creates a filesystem object, plugin must be activated.
	 *
	 * @return Filesystem The filesystem object.
	 */
	protected function createFilesystem(): Filesystem {
		return new Filesystem($this->composer, $this->io, $this->configuration);
	}

	/**
	 * Post autoload dump event handler.
	 *
	 * @param ScriptEvent $event Composer script event.
	 *
	 * @return void
	 */
	public function generateFiles(ScriptEvent $event): void {
		$filesystem = $this->createFilesystem();
		$filesystem->generateFiles();
	}
}
