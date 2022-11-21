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

namespace ShineUnited\Conductor\Filesystem;

use ShineUnited\Conductor\Configuration\Configuration;
use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Path;

/**
 * File
 */
class File {
	private string $fullpath;
	private ?string $contents;

	/**
	 * Initializes the file object.
	 *
	 * @param string $fullpath Path to file.
	 * @param string $contents Contents of the file.
	 */
	public function __construct(string $fullpath, ?string $contents = null) {
		$fullpath = Path::canonicalize($fullpath);
		if (!Path::isAbsolute($fullpath)) {
			$fullpath = Path::makeAbsolute($fullpath, getcwd());
		}

		$this->fullpath = $fullpath;
		$this->contents = $contents;
	}

	/**
	 * Get the full path to the file.
	 *
	 * @return string File fullpath.
	 */
	public function getFullpath(): string {
		return $this->fullpath;
	}

	/**
	 * Get the name of the file (basename)
	 *
	 * @return string The file name without directory path.
	 */
	public function getFilename(): string {
		return basename($this->fullpath);
	}

	/**
	 * Get the path to the directory containing the file (dirname).
	 *
	 * @return string The file directory path.
	 */
	public function getDirectory(): string {
		return dirname($this->fullpath);
	}

	/**
	 * Check if the file already exists.
	 *
	 * @return boolean True if the file already exists.
	 */
	public function alreadyExists(): bool {
		if (file_exists($this->getFullpath())) {
			return true;
		}

		return false;
	}

	/**
	 * Get the contents of the file.
	 *
	 * @return string The current contents of the file.
	 */
	public function getContents(): string {
		if (is_null($this->contents)) {
			if (file_exists($this->getFullpath())) {
				return file_get_contents($this->getFullpath());
			}

			return '';
		}

		return $this->contents;
	}

	/**
	 * Set the contents of the file.
	 *
	 * @param string $contents The new contents of the file.
	 *
	 * @return void
	 */
	public function setContents(string $contents = ''): void {
		$this->contents = $contents;
	}
}
