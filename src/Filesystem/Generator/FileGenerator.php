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

namespace ShineUnited\Conductor\Filesystem\Generator;

use ShineUnited\Conductor\Filesystem\File;
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprintInterface;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;

/**
 * Simple file generator implementation, handles 'file' type
 */
class FileGenerator implements GeneratorInterface {
	public const TYPE = 'file';

	/**
	 * {@inheritDoc}
	 */
	public function handlesType(string $type): bool {
		$type = strtolower(trim($type));

		if ($type == self::TYPE) {
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception
	 */
	public function generateContents(BlueprintInterface $blueprint, File $file, Configuration $config): string {
		if (!$blueprint instanceof FileBlueprintInterface) {
			throw new \Exception('Invalid ' . FileBlueprintInterface::class);
		}

		return $config->processValue($blueprint->getContents());
	}
}
