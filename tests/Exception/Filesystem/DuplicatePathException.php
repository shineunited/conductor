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

namespace ShineUnited\Conductor\Tests\Exception\Filesystem;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Exception\Filesystem\DuplicatePathException;
use LogicException;

/**
 * Duplicate Path Exception Test
 */
class DuplicatePathExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$path = 'path/to';

		$exception = new DuplicatePathException($path);

		$this->assertInstanceOf(LogicException::class, $exception);
	}

	/**
	 * @return void
	 */
	public function testGetPath(): void {
		$path = 'path/to';

		$exception = new DuplicatePathException($path);

		$this->assertSame($path, $exception->getPath());
	}
}
