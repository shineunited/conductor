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

namespace ShineUnited\Conductor\Capability;

use Composer\Plugin\Capability\Capability;
use ShineUnited\Conductor\Configuration\Parameter\ParameterInterface;

/**
 * Installer Provider Interface
 *
 * This capability will receive an array with 'composer' and 'io' keys as
 * constructor argument. Those contain Composer\Composer and Composer\IO\IOInterface
 * instances. It also contains a 'plugin' key containing the plugin instance that
 * created the capability.
 */
interface ParameterProvider extends Capability {

	/**
	 * Retrieves and array of parameters
	 *
	 * @return ParameterInterface[]
	 */
	public function getParameters(): array;
}
