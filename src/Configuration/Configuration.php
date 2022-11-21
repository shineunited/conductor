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

namespace ShineUnited\Conductor\Configuration;

use ShineUnited\Conductor\Capability\ParameterProvider as ParameterProviderCapability;
use ShineUnited\Conductor\Configuration\Parameter\ParameterInterface;
use ShineUnited\Conductor\Configuration\Parameter\PathParameter;
use ShineUnited\Conductor\Exception\Configuration\InvalidPackageExtraException;
use ShineUnited\Conductor\Exception\Configuration\InvalidParameterException;
use ShineUnited\Conductor\Exception\Configuration\InvalidReflectionArgumentException;
use ShineUnited\Conductor\Exception\Configuration\InvalidReflectionFunctionException;
use ShineUnited\Conductor\Exception\Configuration\UnknownParameterException;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use ArrayAccess;
use Closure;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Conductor Configuration
 */
class Configuration implements ArrayAccess {
	private Composer $composer;
	private IOInterface $io;

	protected array $packageExtra = [];
	protected array $parameters = [];
	protected array $loadedPlugins = [];

	/**
	 * Initializes the configuration
	 *
	 * @param Composer    $composer Composer object.
	 * @param IOInterface $io       Composer IO interface.
	 */
	public function __construct(Composer $composer, IOInterface $io) {
		$this->composer = $composer;
		$this->io = $io;

		$extra = $this->composer->getPackage()->getExtra();
		if (is_array($extra)) {
			$this->importPackageExtra($extra);
		}
		$this->loadParameters(true);
	}

	private function importPackageExtra(array $values, ?string $namespace = null): void {
		$prefix = '';
		if (!is_null($namespace)) {
			$prefix = $namespace . '.';
		}

		foreach ($values as $name => $value) {
			if (str_contains($name, '.')) {
				// name contains a period, ignore, invalid for our purposes
				continue;
			}

			$fullname = $prefix . strtolower(trim($name));

			if (isset($this->packageExtra[$fullname])) {
				// the only way this happens is due to array keys being case sensitive, skip
				throw new InvalidPackageExtraException('Duplicate key for ' . $fullname);
			}

			if (is_array($value)) {
				$this->importPackageExtra($value, $fullname);
				continue;
			}

			if (is_scalar($value)) {
				$this->packageExtra[$fullname] = $value;
				continue;
			}

			throw new InvalidPackageExtraException('Invalid type for "' . $fullname . '": ' . gettype($value));
		}
	}

	private function loadParameters(bool $loadDefaults = false): void {
		$newParameters = [];

		if ($loadDefaults) {
			$newParameters[] = new PathParameter('working-dir', function () {
				return getcwd();
			}, [], true);

			$newParameters[] = new PathParameter('vendor-dir', function () {
				return $this->composer->getConfig()->get('vendor-dir');
			}, [], true);
		}

		$pluginManager = $this->composer->getPluginManager();

		foreach ($pluginManager->getPlugins() as $plugin) {
			if (in_array($plugin::class, $this->loadedPlugins)) {
				continue;
			}
			$this->loadedPlugins[] = $plugin::class;

			if (!$plugin instanceof Capable) {
				continue;
			}

			$parameterProvider = $pluginManager->getPluginCapability($plugin, ParameterProviderCapability::class, [
				'composer' => $this->composer,
				'io'       => $this->io
			]);

			if (is_null($parameterProvider)) {
				continue;
			}

			foreach ($parameterProvider->getParameters() as $parameter) {
				if (!$parameter instanceof ParameterInterface) {
					throw new InvalidParameterException($parameter);
				}

				$newParameters[] = $parameter;
			}
		}

		foreach ($newParameters as $parameter) {
			$name = $parameter->getName();
			$name = strtolower(trim($name));

			$value = $parameter->getDefault($this);
			if (!$parameter->isLocked() && isset($this->packageExtra[$name])) {
				$parameter->validateValue($this->packageExtra[$name], $this);
				$value = $this->packageExtra[$name];
			}

			$this->parameters[$name] = $parameter->normalizeValue($value, $this);
		}
	}

	/**
	 * Process a value.
	 *
	 * @param mixed   $value     The value to process.
	 * @param mixed[] $arguments Additional arguments to use.
	 *
	 * @return mixed The processed value.
	 */
	public function processValue(mixed $value, array $arguments = []): mixed {
		if (is_callable($value)) {
			return $this->processCallableValue($value, $arguments);
		}

		if (is_array($value)) {
			return $this->processArrayValue($value, $arguments);
		}

		if (is_string($value)) {
			return $this->processStringValue($value, $arguments);
		}

		return $value;
	}

	/**
	 * Process an array.
	 *
	 * @param mixed[] $array     The array to process.
	 * @param mixed[] $arguments Additional arguments to use.
	 *
	 * @return mixed[] The processed array.
	 */
	public function processArrayValue(array $array, array $arguments = []): array {
		$output = [];
		foreach ($array as $key => $value) {
			$output[$key] = $this->processValue($value);
		}

		return $output;
	}

	/**
	 * Process a string value against the configuration.
	 *
	 * Replaces variables with syntax {$parameter} with parameter value.
	 *
	 * @param string  $string    The string to parse.
	 * @param mixed[] $arguments Additional arguments to use.
	 *
	 * @return string The processed string.
	 */
	public function processStringValue(string $string, array $arguments = []): string {
		// parse string
		$pattern = '@\{\$([A-Za-z]+[A-Za-z0-9._-]*[A-Za-z0-9]+)\}@i';
		preg_match_all($pattern, $string, $matches);

		if (empty($matches[1])) {
			return $string;
		}

		foreach ($matches[1] as $varname) {
			$search = '{$' . $varname . '}';
			$replace = false;
			if ($this->hasParameter($varname)) {
				$replace = (string) $this->getParameter($varname);
			} elseif (isset($arguments[$varname])) {
				$replace = (string) $arguments[$varname];
			}

			if ($replace) {
				$string = str_replace($search, $replace, $string);
			}
		}

		return $string;
	}

	/**
	 * Process a callable value.
	 *
	 * Provides requested parameters to the callback via reflection.
	 *
	 * @param callable $callable  The callable to process.
	 * @param mixed[]  $arguments Additional arguments to use.
	 *
	 * @return mixed The processed callable.
	 */
	public function processCallableValue(callable $callable, array $arguments = []): mixed {
		$reflection = $this->getCallableReflectionFunction($callable);

		$reflectionArguments = [];
		foreach ($reflection->getParameters() as $parameter) {
			$reflectionArguments[] = $this->getReflectionArgument($parameter, $arguments);
		}

		return call_user_func_array($callable, $reflectionArguments);
	}

	/**
	 * @param callable $callable The callable to build reflection function for.
	 *
	 * @throws InvalidReflectionFunctionException If callable is invalid reflection type.
	 *
	 * @return ReflectionFunctionAbstract
	 */
	private function getCallableReflectionFunction(callable $callable): ReflectionFunctionAbstract {
		if (is_array($callable)) {
			return new ReflectionMethod($callable[0], $callable[1]);
		}

		if ($callable instanceof Closure) {
			// closure
			return new ReflectionFunction($callable);
		}

		if (is_object($callable)) {
			return new ReflectionMethod($callable, '__invoke');
		}

		if (is_string($callable)) {
			// either global function or static (class::method)
			$parts = explode('::', $callable);
			if (count($parts) > 1) {
				return new ReflectionMethod($parts[0], $parts[1]);
			} else {
				return new ReflectionFunction($callable);
			}
		}

		throw new InvalidReflectionFunctionException('Unknown callable type');
	}

	/**
	 * @param ReflectionParameter $parameter Reflection parameter object.
	 * @param mixed[]             $arguments Additional arguments to use.
	 *
	 * @throws InvalidReflectionArgumentException For unknown parameter.
	 */
	private function getReflectionArgument(ReflectionParameter $parameter, array $arguments = []): mixed {
		$type = $parameter->getType();

		$objects = [
			// ShineUnited\Conductor\Configuration\Configuration
			$this,

			// Composer\Composer
			$this->composer,

			// Composer\IO\IOInterface
			$this->io,

			// Composer\Repository\RepositoryManager
			$this->composer->getRepositoryManager(),

			// Composer\Installer\InstallationManager
			$this->composer->getInstallationManager(),

			// Composer\Plugin\PluginManager
			$this->composer->getPluginManager(),
		];

		if ($type instanceof ReflectionNamedType && class_exists($type->getName())) {
			$classname = $type->getName();
			foreach ($objects as $object) {
				if ($object instanceof $classname) {
					return $object;
				}
			}
		}

		if (isset($arguments[$parameter->getName()])) {
			return $arguments[$parameter->getName()];
		}

		if ($parameter->isDefaultValueAvailable()) {
			return $parameter->getDefaultValue();
		}

		throw new InvalidReflectionArgumentException('Unknown parameter: ' . $parameter->getName());
	}

	/**
	 * Check if a parameter is defined.
	 *
	 * @param string $name The name of the parameter.
	 *
	 * @return boolean True if the parameter is defined.
	 */
	protected function hasParameter(string $name): bool {
		$name = strtolower(trim($name));
		if (isset($this->parameters[$name])) {
			return true;
		}

		$this->loadParameters();
		if (isset($this->parameters[$name])) {
			return true;
		}

		return false;
	}

	/**
	 * Get a parameter's value.
	 *
	 * @param string $name The name of the parameter.
	 *
	 * @throws UnknownParameterException If parameter does not exist.
	 *
	 * @return mixed The parameter value.
	 */
	protected function getParameter(string $name): mixed {
		$name = strtolower(trim($name));
		if (!$this->hasParameter($name)) {
			throw new UnknownParameterException('Unknown parameter: ' . $name);
		}

		return $this->parameters[$name];
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetExists(mixed $offset): bool {
		return $this->hasParameter($offset);
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetGet(mixed $offset): mixed {
		return $this->getParameter($offset);
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		// do nothing
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetUnset(mixed $offset): void {
		// do nothing
	}
}
