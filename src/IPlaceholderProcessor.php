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
 * Interface IPlaceholderProcessor
 * @package Kappa\PlaceholderProcessor
 */
interface IPlaceholderProcessor
{
	/**
	 * Configure processor
	 * @return void
	 */
	public function configure();

	/**
	 * Returns placeholder name
	 * @return string
	 */
	public function getName();

	/**
	 * Returns list of required external sources
	 * @return array
	 */
	public function getExternalSources();

	/**
	 * Returns parsed text by sources
	 * @param array $sources
	 * @return string
	 */
	public function run(array $sources);
}
