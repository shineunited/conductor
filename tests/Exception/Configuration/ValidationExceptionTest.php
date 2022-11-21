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
use ShineUnited\Conductor\Configuration\Parameter\ParameterInterface;
use ShineUnited\Conductor\Exception\Configuration\ValidationException;
use DomainException;

/**
 * Validation Exception Test
 */
class ValidationExceptionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$parameter = $this->createStub(ParameterInterface::class);
		$exception = new ValidationException($parameter, 'invalid parameter');

		$this->assertInstanceOf(DomainException::class, $exception);
	}

	/**
	 * @return void
	 */
	public function testGetParameter(): void {
		$parameter = $this->createStub(ParameterInterface::class);
		$exception = new ValidationException($parameter, 'invalid parameter');

		$this->assertSame($parameter, $exception->getParameter());
	}
}
