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

use ShineUnited\Conductor\Capability\GeneratorProvider as GeneratorProviderCapability;
use ShineUnited\Conductor\Capability\BlueprintProvider as BlueprintProviderCapability;
use ShineUnited\Conductor\Exception\Filesystem\DuplicatePathException;
use ShineUnited\Conductor\Exception\Filesystem\InvalidBlueprintException;
use ShineUnited\Conductor\Exception\Filesystem\InvalidGeneratorException;
use ShineUnited\Conductor\Filesystem\Generator\GeneratorInterface;
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\Composer;
use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Path;

/**
 * Filesystem
 */
class Filesystem {
	private Composer $composer;
	private IOInterface $io;
	private Configuration $config;

	/**
	 * Initializes the filesystem.
	 *
	 * @param Composer      $composer Composer object.
	 * @param IOInterface   $io       Composer IO interface.
	 * @param Configuration $config   Conductor configuration.
	 */
	public function __construct(Composer $composer, IOInterface $io, Configuration $config) {
		$this->composer = $composer;
		$this->io = $io;
		$this->config = $config;
	}

	/**
	 * Generates files based on provided blueprints.
	 *
	 * @return void
	 *
	 * @throws DuplicatePathException    When blueprint paths overlap.
	 * @throws InvalidBlueprintException If an invalid blueprint is provided.
	 * @throws InvalidGeneratorException If an invalid generator is provided.
	 */
	public function generateFiles(): void {
		$pluginManager = $this->composer->getPluginManager();

		$generatorProviders = $pluginManager->getPluginCapabilities(GeneratorProviderCapability::class, [
			'composer' => $this->composer,
			'io'       => $this->io
		]);

		$generators = [];
		foreach ($generatorProviders as $provider) {
			foreach ($provider->getGenerators() as $generator) {
				if (!$generator instanceof GeneratorInterface) {
					throw new InvalidGeneratorException($generator);
				}

				$generators[] = $generator;
			}
		}

		$blueprintProviders = $pluginManager->getPluginCapabilities(BlueprintProviderCapability::class, [
			'composer' => $this->composer,
			'io'       => $this->io
		]);

		$files = [];
		foreach ($blueprintProviders as $provider) {
			foreach ($provider->getBlueprints() as $blueprint) {
				if (!$blueprint instanceof BlueprintInterface) {
					throw new InvalidBlueprintException($blueprint);
				}

				$path = $this->config->processValue($blueprint->getPath());

				if (isset($files[$path])) {
					throw new DuplicatePathException($path);
				}

				// find provider
				foreach ($generators as $generator) {
					if ($generator->handlesBlueprint($blueprint)) {
						$file = new File($path);
						$contents = $generator->generateContents($blueprint, $file, $this->config);

						if ($file->alreadyExists()) {
							if ($file->getContents() == $contents) {
								// file has not changed, continue
								continue;
							}

							if (!$blueprint->canUpdate($file, $this->io)) {
								// cannot update file, continue
								continue;
							}
						} else {
							if (!$blueprint->canCreate($file, $this->io)) {
								// cannot create file, continue
								continue;
							}
						}

						$file->setContents($contents);

						$files[] = $file;
					}
				}
			}
		}

		$filesCreated = 0;
		$filesUpdated = 0;
		foreach ($files as $path => $file) {
			if ($file->alreadyExists()) {
				$filesUpdated++;
				$this->updateFile($file);
			} else {
				$filesCreated++;
				$this->createFile($file);
			}
		}

		if ($filesCreated > 0 || $filesUpdated > 0) {
			$this->io->write('<info>Files generated: ' . $filesCreated . ' created, ' . $filesUpdated . ' updated');
		}
	}

	private function updateFile(File $file): void {
		$this->saveFile($file, 'update');
	}

	private function createFile(File $file): void {
		$this->saveFile($file, 'create');
	}

	private function saveFile(File $file, string $comment): void {
		$fullpath = $file->getFullpath();
		$directory = $file->getDirectory();
		$contents = $file->getContents();

		$relativeFilepath = Path::makeRelative($fullpath, getcwd());
		$this->io->write('  - Generating <info>' . $relativeFilepath . '</info> (<comment>' . $comment . '</comment>)');

		// create directories as needed
		if (!is_dir($directory)) {
			$relativeDirectory = Path::makeRelative($directory, getcwd());
			$result = mkdir($directory, 0777, true);
			if ($result === false) {
				$this->io->writeError('<error>Unable to create directory: ' . $relativeDirectory . '</error>');
			}
		}

		$result = file_put_contents($fullpath, $contents);
		if ($result === false) {
			$relativeFilename = Path::makeRelative($fullpath, getcwd());
			$this->io->writeError('<error>Unable to create file: ' . $relativeFilename . '</error>');
		}
	}
}
