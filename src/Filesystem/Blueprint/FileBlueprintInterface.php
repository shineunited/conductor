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

use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;

/**
 * File Blueprint Interface
 */
interface FileBlueprintInterface extends BlueprintInterface {

	/**
	 * Get the blueprint contents.
	 *
	 * @return string|callable The contents, expects to be processed.
	 */
	public function getContents(): string|callable;
}
