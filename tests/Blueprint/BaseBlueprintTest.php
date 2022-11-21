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

namespace ShineUnited\Conductor\Tests\Blueprint;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Filesystem\Blueprint\BaseBlueprint;
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Filesystem\File;
use Composer\IO\IOInterface;

/**
 * Base Blueprint Test
 */
class BaseBlueprintTest extends TestCase {

	private function createBaseBlueprint(string|callable $path, ?string $allowCreate = null, ?string $allowUpdate = null): BaseBlueprint {
		return new class ($path, $allowCreate, $allowUpdate) extends BaseBlueprint {
		};
	}

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$blueprint = $this->createBaseBlueprint('path');

		$this->assertInstanceOf(BlueprintInterface::class, $blueprint);
	}

	/**
	 * @return void
	 */
	public function testGetPathString(): void {
		$string = 'string/path';

		$blueprint = $this->createBaseBlueprint($string);

		$this->assertSame($string, $blueprint->getPath());
	}

	/**
	 * @return void
	 */
	public function testGetPathCallable(): void {
		$callable = function () {
			return 'callable/path';
		};

		$blueprint = $this->createBaseBlueprint($callable);

		$this->assertSame($callable, $blueprint->getPath());
	}

	/**
	 * @return void
	 */
	public function testCanCreateAlways(): void {
		$blueprint = $this->createBaseBlueprint('path', 'always', null);

		$file = $this->createStub(File::class);
		$io = $this->createStub(IOInterface::class);

		$this->assertTrue($blueprint->canCreate($file, $io));
	}

	/**
	 * @return void
	 */
	public function testCanCreateNever(): void {
		$blueprint = $this->createBaseBlueprint('path', 'never', null);

		$file = $this->createStub(File::class);
		$io = $this->createStub(IOInterface::class);

		$this->assertFalse($blueprint->canCreate($file, $io));
	}

	/**
	 * @return void
	 */
	public function testCanCreateAsk(): void {
		$blueprint = $this->createBaseBlueprint('path', 'ask', null);

		$file = $this->createStub(File::class);
		$file = $this->createStub(File::class);

		$io = $this->createStub(IOInterface::class);

		$io
			->method('isInteractive')
			->willReturn(true)
		;

		$io
			->method('askConfirmation')
			->will($this->onConsecutiveCalls(true, false))
		;

		$this->assertTrue($blueprint->canCreate($file, $io));
		$this->assertFalse($blueprint->canCreate($file, $io));
	}

	/**
	 * @return void
	 */
	public function testCanUpdateAlways(): void {
		$blueprint = $this->createBaseBlueprint('path', null, 'always');

		$file = $this->createStub(File::class);
		$io = $this->createStub(IOInterface::class);

		$this->assertTrue($blueprint->canUpdate($file, $io));
	}

	/**
	 * @return void
	 */
	public function testCanUpdateNever(): void {
		$blueprint = $this->createBaseBlueprint('path', null, 'never');

		$file = $this->createStub(File::class);
		$io = $this->createStub(IOInterface::class);

		$this->assertFalse($blueprint->canUpdate($file, $io));
	}

	/**
	 * @return void
	 */
	public function testCanUpdateAsk(): void {
		$blueprint = $this->createBaseBlueprint('path', null, 'ask');

		$file = $this->createStub(File::class);
		$file = $this->createStub(File::class);

		$io = $this->createStub(IOInterface::class);

		$io
			->method('isInteractive')
			->willReturn(true)
		;

		$io
			->method('askConfirmation')
			->will($this->onConsecutiveCalls(true, false))
		;

		$this->assertTrue($blueprint->canUpdate($file, $io));
		$this->assertFalse($blueprint->canUpdate($file, $io));
	}
}
