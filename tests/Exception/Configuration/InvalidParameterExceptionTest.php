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
use ShineUnited\Conductor\Exception\InvalidObjectException;
use ShineUnited\Conductor\Exception\Configuration\InvalidParameterException;
use DomainException;

/**
 * Invalid Parameter Exception Test
 */
class InvalidParameterExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$object = 'string';

		$exception = new InvalidParameterException($object);

		$this->assertInstanceOf(InvalidObjectException::class, $exception);
		$this->assertInstanceOf(DomainException::class, $exception);
	}
}
