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
		$this->configuration = new Configuration($composer, $io);

		$installationManager = $composer->getInstallationManager();
		$installationManager->addInstaller(new InstallationManager($io, $composer, $this->configuration));
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
	 * Post autoload dump event handler.
	 *
	 * @param ScriptEvent $event Composer script event.
	 *
	 * @return void
	 */
	public function generateFiles(ScriptEvent $event): void {
		$filesystem = new Filesystem($this->composer, $this->io, $this->configuration);
		$filesystem->generateFiles();
	}
}
