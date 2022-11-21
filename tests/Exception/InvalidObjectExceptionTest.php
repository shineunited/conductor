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

namespace ShineUnited\Conductor\Tests\Exception;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Exception\InvalidObjectException;
use DomainException;

/**
 * Invalid Object Exception Test
 */
class InvalidObjectExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$object = 'string';
		$class = InvalidObjectException::class;

		$exception = new InvalidObjectException($object, $class);

		$this->assertInstanceOf(DomainException::class, $exception);
	}

	/**
	 * @return void
	 */
	public function testGetObject(): void {
		$object = 'string';
		$class = InvalidObjectException::class;

		$exception = new InvalidObjectException($object, $class);

		$this->assertSame($object, $exception->getObject());
	}
}
