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

/**
 *  Blueprint Interface
 */
interface BlueprintInterface {

	/**
	 * Get the output path of the blueprint.
	 *
	 * @return string|callable The output path, expects to be processed.
	 */
	public function getPath(): string|callable;

	/**
	 * Checks is the filesystem can create the file.
	 *
	 * @param File        $file The current file.
	 * @param IOInterface $io   The IOInterface.
	 *
	 * @return boolean True if create is allowed.
	 */
	public function canCreate(File $file, IOInterface $io): bool;

	/**
	 * Checks is the filesystem can update the file.
	 *
	 * @param \ShineUnited\Conductor\Filesystem\File $file The current file.
	 * @param \Composer\IO\IOInterface               $io   The IOInterface.
	 *
	 * @return boolean True if update is allowed.
	 */
	public function canUpdate(File $file, IOInterface $io): bool;
}
