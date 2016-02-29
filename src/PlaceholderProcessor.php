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
 * Class PlaceholderProcessor
 * @package Kappa\PlaceholderProcessor
 */
abstract class PlaceholderProcessor implements IPlaceholderProcessor
{
	/** @var string */
	private $name;

	/** @var array */
	private $externalSources = [];

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @param array $sources
	 * @return $this
	 */
	public function setExternalSources(array $sources)
	{
		$this->externalSources = $sources;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getExternalSources()
	{
		return $this->externalSources;
	}

	/**
	 * @return void
	 */
	public function configure() {}

	/**
	 * @param array $externalSources
	 * @return string
	 */
	public function run(array $externalSources = [])
	{
		return "";
	}
}
