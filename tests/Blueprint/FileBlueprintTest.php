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
use ShineUnited\Conductor\Filesystem\Blueprint\BlueprintInterface;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprint;
use ShineUnited\Conductor\Filesystem\Blueprint\FileBlueprintInterface;

/**
 * File Blueprint Test
 */
class FileBlueprintTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$blueprint = new FileBlueprint('path', 'contents');

		$this->assertInstanceOf(BlueprintInterface::class, $blueprint);
		$this->assertInstanceOf(FileBlueprintInterface::class, $blueprint);
	}

	/**
	 * @return void
	 */
	public function testGetContentsString(): void {
		$stringContents = 'string contents';

		$blueprint = new FileBlueprint('path', $stringContents);

		$this->assertSame($stringContents, $blueprint->getContents());
	}

	/**
	 * @return void
	 */
	public function testGetContentsCallable(): void {
		$callableContents = function () {
			return 'callable contents';
		};

		$blueprint = new FileBlueprint('path', $callableContents);

		$this->assertSame($callableContents, $blueprint->getContents());
	}
}
