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

namespace ShineUnited\Conductor\Tests\Filesystem\Generator;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprintInterface;
use ShineUnited\Conductor\Filesystem\Generator\GeneratorInterface;
use ShineUnited\Conductor\Filesystem\Generator\FileGenerator;
use ShineUnited\Conductor\Configuration\Configuration;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprint;
use ShineUnited\Conductor\Filesystem\File;

/**
 * File Generator Test
 */
class FileGeneratorTest extends TestCase {

	/**
	 * @return void
	 */
	public function testHandlesBlueprint(): void {
		$generator = new FileGenerator();

		$fileBlueprint = $this->createStub(FileBlueprintInterface::class);
		$genericBlueprint = $this->createStub(BlueprintInterface::class);

		$this->assertTrue($generator->handlesBlueprint($fileBlueprint));
		$this->assertFalse($generator->handlesBlueprint($genericBlueprint));
	}

	/**
	 * @return void
	 */
	public function testGenerateContentsException(): void {
		$blueprint = $this->createStub(BlueprintInterface::class);
		$file = $this->createStub(File::class);
		$configuration = $this->createStub(Configuration::class);

		$generator = new FileGenerator();

		$this->toDo(); // need to create proper exception classes
		$this->expectException(\Exception::class);
		$generator->generateContents($blueprint, $file, $configuration);
	}

	/**
	 * @return void
	 */
	public function testGenerateContentsProcessed(): void {
		$blueprint = new FileBlueprint('path', 'contents');

		$file = $this->createStub(File::class);

		$configuration = $this->createMock(Configuration::class);

		$configuration
			->expects($this->once())
			->method('processValue')
			->with($blueprint->getContents())
			->willReturn($blueprint->getContents())
		;

		$generator = new FileGenerator();

		$generator->generateContents($blueprint, $file, $configuration);
	}
}
