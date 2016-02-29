<?php
/**
 * This file is part of the Kappa\PlaceholderProcessor package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\PlaceholderProcessor;

/**
 * Class TextFormatter
 * @package Kappa\PlaceholderProcessor
 */
class TextFormatter
{
	const MASK = "~%(\w+)%~";

	/** @var IPlaceholderProcessor[] */
	private $processors;

	/**
	 * TextFormatter constructor.
	 * @param array $processors
	 */
	public function __construct(array $processors = [])
	{
		$this->setProcessors($processors);
	}

	/**
	 * @param IPlaceholderProcessor $processor
	 * @return $this
	 */
	public function addProcessor(IPlaceholderProcessor $processor)
	{
		$processor->configure();
		$this->processors[$processor->getName()] = $processor;

		return $this;
	}

	/**
	 * @param array $processors
	 * @return $this
	 */
	public function setProcessors(array $processors)
	{
		foreach ($processors as $processor) {
			$this->addProcessor($processor);
		}

		return $this;
	}

	/**
	 * @param string $input
	 * @param array $externalSources
	 * @return string
	 */
	public function format($input, array $externalSources = [])
	{
		if (preg_match_all(self::MASK, $input, $matches)) {
			if ($matches[1]) {
				foreach ($matches[1] as $name) {
					if (array_key_exists($name, $this->processors)) {
						$input = str_replace("%{$name}%", $this->process($name, $externalSources), $input);
					}
				}
			}
		}

		return $input;
	}

	/**
	 * @param string $name
	 * @param array $sources
	 * @return string
	 */
	private function process($name, array $sources)
	{
		$processor = $this->processors[$name];
		$concreteSources = [];
		foreach ($processor->getExternalSources() as $sourceName) {
			if (array_key_exists($sourceName, $sources)) {
				$concreteSources[$sourceName] = $sources[$sourceName];
			} else {
				throw new InvalidStateException("Invalid external source: {$name}");
			}
		}

		return $processor->run($concreteSources);
	}
}
