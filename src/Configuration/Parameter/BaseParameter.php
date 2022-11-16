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

namespace ShineUnited\Conductor\Configuration\Parameter;

use ShineUnited\Conductor\Configuration\Configuration;

/**
 * Abstract base parameter class
 */
abstract class BaseParameter implements ParameterInterface {
	private string $name;
	private mixed $default;
	private array $options;
	private bool $locked;

	/**
	 * Initializes the parameter.
	 *
	 * @param string  $name    The name of the parameter.
	 * @param mixed   $default The default value, expects to be processed.
	 * @param mixed[] $options Array of options, expects to be processed.
	 * @param boolean $locked  True if parameter is locked to default.
	 */
	public function __construct(string $name, mixed $default, array $options = [], bool $locked = false) {
		$this->name = $name;
		$this->default = $default;
		$this->options = $options;
		$this->locked = $locked;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefault(Configuration $config): mixed {
		return $config->processValue($this->default);
	}

	/**
	 * {@inheritDoc}
	 */
	public function isLocked(): bool {
		return $this->locked;
	}

	/**
	 * Checks if option was defined.
	 *
	 * @param string        $name   The option name.
	 * @param Configuration $config Conductor configuration.
	 *
	 * @return boolean True if the option exists.
	 */
	protected function hasOption(string $name, Configuration $config): bool {
		$name = strtolower(trim($name));
		if (isset($this->options[$name])) {
			return true;
		}

		return false;
	}

	/**
	 * Gets the value of an option. Processed against configuration.
	 *
	 * @param string        $name   The option name.
	 * @param Configuration $config Conductor configuration.
	 *
	 * @throws \Exception If specified option does not exist.
	 *
	 * @return mixed The requested option value.
	 */
	protected function getOption(string $name, Configuration $config): mixed {
		$name = strtolower(trim($name));
		if (!$this->hasOption($name, $config)) {
			throw new \Exception('Unknown option: ' . $name);
		}

		return $config->processValue($this->options[$name]);
	}
}
