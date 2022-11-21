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

namespace ShineUnited\Conductor\Tests;

use ShineUnited\Conductor\Plugin;
use ShineUnited\Conductor\Filesystem\InstallationManager;
use ShineUnited\Conductor\Filesystem\Filesystem;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\Composer;
use Composer\Config;
use Composer\Package\PackageInterface;
use Composer\Installer\InstallationManager as ComposerInstallationManager;
use Composer\Installer\InstallerInterface;
use Composer\Package\RootPackageInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event as ScriptEvent;

/**
 * Plugin Test
 */
class PluginTest extends TestCase {

	/**
	 * @return void
	 */
	public function testActivate(): void {
		$composerInstallationManager = $this->createMock(ComposerInstallationManager::class);
		$composerInstallationManager
			->expects($this->once())
			->method('addInstaller')
			->with($this->isInstanceOf(InstallationManager::class))
		;

		$composer = $this->createMock(Composer::class);
		$composer
			->expects($this->any())
			->method('getInstallationManager')
			->willReturn($composerInstallationManager)
		;

		$io = $this->createStub(IOInterface::class);

		$configuration = $this->createStub(Configuration::class);
		$installationManager = $this->createStub(InstallationManager::class);

		$plugin = new class ($configuration, $installationManager) extends Plugin {
			private Configuration $configuration;
			private InstallationManager $installationManager;

			/**
			 * Initializes plugin.
			 *
			 * @param Configuration       $configuration       Conductor configuration.
			 * @param InstallationManager $installationManager Conductor installation manager.
			 */
			public function __construct(Configuration $configuration, InstallationManager $installationManager) {
				$this->configuration = $configuration;
				$this->installationManager = $installationManager;
			}

			/**
			 * {@inheritDoc}
			 */
			protected function createConfiguration(Composer $composer, IOInterface $io): Configuration {
				return $this->configuration;
			}

			/**
			 * {@inheritDoc}
			 */
			protected function createInstallationManager(IOInterface $io, Composer $composer, Configuration $configuration): InstallationManager {
				return $this->installationManager;
			}
		};

		$plugin->activate($composer, $io);
	}

	/**
	 * @return void
	 */
	public function testGetSubscribedEvents(): void {
		$plugin = new Plugin();

		$this->assertInstanceOf(EventSubscriberInterface::class, $plugin);

		$events = $plugin->getSubscribedEvents();

		$eventName = ScriptEvents::POST_AUTOLOAD_DUMP;

		$this->assertArrayHasKey($eventName, $events);
		$this->assertSame('generateFiles', $events[$eventName]);
	}

	/**
	 * @return void
	 */
	public function testGenerateFiles(): void {
		$filesystem = $this->createMock(Filesystem::class);

		$filesystem
			->expects($this->once())
			->method('generateFiles')
		;

		$plugin = new class ($filesystem) extends Plugin {
			private Filesystem $filesystem;

			/**
			 * Initializes plugin.
			 *
			 * @param Filesystem $filesystem Conductor filesystem.
			 */
			public function __construct(Filesystem $filesystem) {
				$this->filesystem = $filesystem;
			}

			/**
			 * {@inheritDoc}
			 */
			protected function createFilesystem(): Filesystem {
				return $this->filesystem;
			}
		};

		$event = $this->createStub(ScriptEvent::class);

		$plugin->generateFiles($event);
	}
}
