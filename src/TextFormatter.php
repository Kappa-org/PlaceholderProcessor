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
	private $processors = [];

	/** @var bool */
	private $strictMode = false;

	/**
	 * TextFormatter constructor.
	 * @param IPlaceholderProcessor[] $processors
	 */
	public function __construct(array $processors = [])
	{
		$this->setProcessors($processors);
	}

	/**
	 * @param bool $strict
	 * @return $this
	 */
	public function setStrictMode($strict)
	{
		$this->strictMode = $strict;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isStrictMode()
	{
		return $this->strictMode;
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
					if (!array_key_exists($name, $this->processors)) {
						if ($this->strictMode) {
							throw new MissingPlaceholderProcessorException('Missing placeholder for \'' . $name . '\' placeholder');
						}
					} else {
						$input = str_replace("%{$name}%", $this->process($this->processors[$name], $externalSources), $input);
					}
				}
			}
		}

		return $input;
	}

	/**
	 * @param IPlaceholderProcessor $processor
	 * @param array $sources
	 * @return string
	 */
	private function process(IPlaceholderProcessor $processor, array $sources)
	{
		$concreteSources = [];
		foreach ($processor->getExternalSources() as $sourceName) {
			if (array_key_exists($sourceName, $sources)) {
				$concreteSources[$sourceName] = $sources[$sourceName];
			} else {
				throw new MissingExternalSourceException("Invalid external source: {$processor->getName()}");
			}
		}

		return $processor->run($concreteSources);
	}
}
