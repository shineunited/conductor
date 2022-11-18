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

namespace ShineUnited\Conductor\Filesystem\Blueprint;

use ShineUnited\Conductor\Filesystem\File;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Path;

/**
 * Base class for blueprints
 */
abstract class BaseBlueprint implements BlueprintInterface {
	private mixed $path;
	private string $allowCreate;
	private string $allowUpdate;

	public const ALWAYS = 'always';
	public const NEVER = 'never';
	public const ASK = 'ask';

	/**
	 * Initializes the blueprint.
	 *
	 * @param string|callable $path        Output path.
	 * @param string          $allowCreate Create limits, should be always|ask|never.
	 * @param string          $allowUpdate Update limits, should be always|ask|never.
	 */
	public function __construct(string|callable $path, ?string $allowCreate = null, ?string $allowUpdate = null) {
		$this->path = $path;

		$this->allowCreate = self::ALWAYS;
		if (!is_null($allowCreate)) {
			$this->allowCreate = $allowCreate;
		}

		$this->allowUpdate = self::ALWAYS;
		if (!is_null($allowUpdate)) {
			$this->allowUpdate = $allowUpdate;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPath(): string|callable {
		return $this->path;
	}

	/**
	 * {@inheritDoc}
	 */
	public function canCreate(File $file, IOInterface $io): bool {
		return $this->checkAllow('create', $file, $io);
	}

	/**
	 * {@inheritDoc}
	 */
	public function canUpdate(File $file, IOInterface $io): bool {
		return $this->checkAllow('update', $file, $io);
	}

	private function checkAllow(string $name, File $file, IOInterface $io): bool {
		$name = strtolower(trim($name));
		if (!in_array($name, ['create', 'update'])) {
			throw new \Exception('Unknown allow setting: ' . $name);
		}

		$method = $this->allowCreate;
		if ($name == 'update') {
			$method = $this->allowUpdate;
		}

		$method = strtolower(trim($method));
		if (!in_array($method, [self::ALWAYS, self::NEVER, self::ASK])) {
			throw new \Exception('Unknown allow method: ' . $method);
		}

		if ($method == self::ALWAYS) {
			return true;
		}

		if ($method == self::NEVER) {
			return false;
		}

		// otherwise the method is 'ask'
		$localPath = Path::makeRelative($file->getFullpath(), getcwd());
		$question = 'Do you want to <comment>' . $name . '</comment> file <info>' . $localPath . '</info>? [<comment>Y,n</comment>] ';
		return $io->askConfirmation($question, $io->isInteractive());
	}
}
