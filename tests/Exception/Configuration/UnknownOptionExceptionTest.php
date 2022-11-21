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
use ShineUnited\Conductor\Exception\Configuration\UnknownOptionException;
use InvalidArgumentException;

/**
 * Unknown Option Exception Test
 */
class UnknownOptionExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$exception = new UnknownOptionException();

		$this->assertInstanceOf(InvalidArgumentException::class, $exception);
	}
}
