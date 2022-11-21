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

namespace ShineUnited\Conductor\Exception\Filesystem;

use ShineUnited\Conductor\Exception\InvalidObjectException;
use ShineUnited\Conductor\Filesystem\Installer\InstallerInterface;
use Throwable;

/**
 * Invalid Installer Exception
 */
class InvalidInstallerException extends InvalidObjectException {

	/**
	 * Initializes the exception.
	 *
	 * @param mixed     $object   The invalid installer.
	 * @param string    $class    The expected installer class.
	 * @param Throwable $previous Previous exception (if applicable).
	 */
	public function __construct(mixed $object, string $class = InstallerInterface::class, ?Throwable $previous = null) {
		parent::__construct($object, $class, $previous);
	}
}
