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

namespace ShineUnited\Conductor\Tests\Filesystem\Installer;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Filesystem\Installer\InstallerInterface;
use ShineUnited\Conductor\Filesystem\Installer\PathInstaller;

/**
 * Path Installer Test
 */
class PathInstallerTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$installer = new PathInstaller([], 'path', 1);

		$this->assertInstanceOf(PathInstaller::class, $installer);
		$this->assertInstanceOf(InstallerInterface::class, $installer);
	}

	/**
	 * @return void
	 */
	public function testGetPriority(): void {
		for ($priority = 1; $priority < 10; $priority++) {
			$installer = new PathInstaller([], 'path', $priority);

			$this->assertSame($priority, $installer->getPriority());
		}
	}

	/**
	 * @return void
	 */
	public function testGetSupportedTypesString(): void {
		$type = 'string';

		$installer = new PathInstaller($type, 'path');

		$this->assertSame([$type], $installer->getSupportedTypes());
	}

	/**
	 * @return void
	 */
	public function testGetSupportedTypesArray(): void {
		$types = [
			'type1',
			'type2',
			'type3'
		];

		$installer = new PathInstaller($types, 'path');

		$this->assertSame($types, $installer->getSupportedTypes());
	}

	/**
	 * @return void
	 */
	public function testGetInstallPath(): void {
		$this->toDo();
	}
}
