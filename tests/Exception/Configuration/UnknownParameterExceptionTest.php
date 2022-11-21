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
use ShineUnited\Conductor\Exception\Configuration\UnknownParameterException;
use InvalidArgumentException;

/**
 * Unknown Parameter Exception Test
 */
class UnknownParameterExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$exception = new UnknownParameterException();

		$this->assertInstanceOf(InvalidArgumentException::class, $exception);
	}
}
