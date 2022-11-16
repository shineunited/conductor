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
use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;

/**
 * Generator Interface
 */
interface GeneratorInterface {

	/**
	 * Checks if the blueprint type is handled by this generator.
	 *
	 * @param string $type Type blueprint type to check.
	 *
	 * @return boolean True if this generator handles the type checked.
	 */
	public function handlesType(string $type): bool;

	/**
	 * Generates the file contents from the provided blueprint.
	 *
	 * @param BlueprintInterface $blueprint The blueprint to generate.
	 * @param File               $file      The current file.
	 * @param Configuration      $config    Conductor config.
	 *
	 * @return string The generated file contents.
	 */
	public function generateContents(BlueprintInterface $blueprint, File $file, Configuration $config): string;
}
