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

namespace ShineUnited\Conductor\Filesystem\Blueprint;

/**
 * Blueprint class for simple files
 */
class FileBlueprint extends BaseBlueprint implements FileBlueprintInterface {
	private mixed $contents;

	/**
	 * @param string|callable $contents    File contents.
	 * @param string          $allowCreate Expects always|ask|never.
	 * @param string          $allowUpdate Expects always|ask|never.
	 */
	public function __construct(string|callable $path, string|callable $contents, ?string $allowCreate = null, ?string $allowUpdate = null) {
		parent::__construct($path, $allowCreate, $allowUpdate);

		$this->contents = $contents;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getContents(): string|callable {
		return $this->contents;
	}
}
