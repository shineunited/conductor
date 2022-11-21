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

namespace ShineUnited\Conductor\Tests\Filesystem;

use ShineUnited\Conductor\Tests\TestCase;
use ShineUnited\Conductor\Filesystem\File;

/**
 * File Test
 */
class FileTest extends TestCase {

	/**
	 * @return void
	 */
	public function testConstructor(): void {
		$file = new File('/full/path/to/file');

		$this->assertInstanceOf(File::class, $file);
	}

	/**
	 * @return void
	 */
	public function testGetFullpath(): void {
		$filename = 'file';
		$directory = '/full/path/to';
		$fullpath = $directory . '/' . $filename;

		$file = new File($fullpath);

		$this->assertSame($fullpath, $file->getFullpath());
	}

	/**
	 * @return void
	 */
	public function testGetFilename(): void {
		$filename = 'file';
		$directory = '/full/path/to';
		$fullpath = $directory . '/' . $filename;

		$file = new File($fullpath);

		$this->assertSame($filename, $file->getFilename());
	}

	/**
	 * @return void
	 */
	public function testGetDirectory(): void {
		$filename = 'file';
		$directory = '/full/path/to';
		$fullpath = $directory . '/' . $filename;

		$file = new File($fullpath);

		$this->assertSame($directory, $file->getDirectory());
	}

	/**
	 * @return void
	 */
	public function alreadyExists(): void {
		$this->toDo();
	}

	/**
	 * @return void
	 */
	public function testGetContents(): void {
		$this->toDo();
	}

	/**
	 * @return void
	 */
	public function testSetContents(): void {
		$this->toDo();
	}
}
