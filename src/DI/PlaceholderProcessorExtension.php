<?php
/**
 * This file is part of the Kappa\PlaceholderProcessor package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\PlaceholderProcessor\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;

/**
 * Class PlaceholderProcessorExtension
 * @package Kappa\PlaceholderProcessor\DI
 */
class PlaceholderProcessorExtension extends CompilerExtension
{
	private $defaultConfig = [
		'processors' => [],
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$builder = $this->getContainerBuilder();

		$processors = [];
		foreach ($config['processors'] as $processor) {
			if (is_string($processor)) {
				$processorName = "placeholderProcessor." . md5($processor);
			} else {
				$processorName = "placeholderProcessor." . md5($processor->value);
			}
			$def = $builder->addDefinition($this->prefix($processorName));
			list($def->factory) = Compiler::filterArguments(array(
				is_string($processor) ? new Statement($processor) : $processor
			));
			if (class_exists($def->factory->entity)) {
				$def->class = $def->factory->entity;
			}
			$def->setAutowired(false);
			$processors[] = $this->prefix("@" . $processorName);
		}

		$builder->addDefinition($this->prefix('textFormatter'))
			->setClass('Kappa\PlaceholderProcessor\TextFormatter', [$processors]);
	}

}
