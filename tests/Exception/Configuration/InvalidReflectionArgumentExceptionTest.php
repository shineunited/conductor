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

namespace ShineUnited\Conductor\Tests\Exception\Configuration;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Exception\Configuration\InvalidReflectionArgumentException;
use RuntimeException;

/**
 * Invalid Reflection Argument Exception Test
 */
class InvalidReflectionArgumentExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$exception = new InvalidReflectionArgumentException();

		$this->assertInstanceOf(RuntimeException::class, $exception);
	}
}
