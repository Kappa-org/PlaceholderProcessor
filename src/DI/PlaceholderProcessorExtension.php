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
use Nette\DI\Helpers;
use Nette\DI\Statement;

/**
 * Class PlaceholderProcessorExtension
 * @package Kappa\PlaceholderProcessor\DI
 */
class PlaceholderProcessorExtension extends CompilerExtension
{
	const PROCESSOR_TAG = 'kappa.placeholder_processor';

	private $defaultConfig = [
		'strict' => false
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('textFormatter'))
			->setClass('Kappa\PlaceholderProcessor\TextFormatter')
			->addSetup('setStrictMode', [$config['strict']]);
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$textFormatter = $builder->getDefinition($this->prefix('textFormatter'));
		foreach (array_keys($builder->findByTag(self::PROCESSOR_TAG)) as $serviceName) {
			$textFormatter->addSetup('addProcessor', ['@' . $serviceName]);
		}
	}
}
