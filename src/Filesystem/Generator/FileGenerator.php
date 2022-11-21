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

use ShineUnited\Conductor\Exception\Filesystem\InvalidBlueprintException;
use ShineUnited\Conductor\Filesystem\File;
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprintInterface;
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;

/**
 * Simple file generator implementation, handles FileBlueprintInterface
 */
class FileGenerator implements GeneratorInterface {

	/**
	 * {@inheritDoc}
	 */
	public function handlesBlueprint(BlueprintInterface $blueprint): bool {
		if ($blueprint instanceof FileBlueprintInterface) {
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidBlueprintException If blueprint is NOT a file blueprint.
	 */
	public function generateContents(BlueprintInterface $blueprint, File $file, Configuration $config): string {
		if (!$blueprint instanceof FileBlueprintInterface) {
			throw new InvalidBlueprintException($blueprint, FileBlueprintInterface::class);
		}

		return $config->processValue($blueprint->getContents());
	}
}
