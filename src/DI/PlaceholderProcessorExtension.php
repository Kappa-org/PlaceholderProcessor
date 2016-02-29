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

use Nette\DI\CompilerExtension;

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
			$processorName = "placeholderProcessor." . md5($processor);
			$builder->addDefinition($this->prefix($processorName))
				->setClass($processor)
				->setAutowired(false);
			$processors[] = $this->prefix("@" . $processorName);
		}

		$builder->addDefinition($this->prefix('textFormatter'))
			->setClass('Kappa\PlaceholderProcessor\TextFormatter', [$processors]);
	}

}
