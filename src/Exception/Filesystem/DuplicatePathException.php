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

use LogicException;
use Throwable;

/**
 * Duplicate Path Exception
 */
class DuplicatePathException extends LogicException {
	private string $path;

	/**
	 * Initializes the exception.
	 *
	 * @param string    $path     The duplicated path.
	 * @param Throwable $previous Previous exception (if applicable).
	 */
	public function __construct(string $path, ?Throwable $previous = null) {
		$this->path = $path;

		parent::__construct('path "' . $path . '" already exists.', 0, $previous);
	}

	/**
	 * Gets the duplicated path.
	 *
	 * @return string The duplicated path.
	 */
	public function getPath(): string {
		return $this->path;
	}
}
