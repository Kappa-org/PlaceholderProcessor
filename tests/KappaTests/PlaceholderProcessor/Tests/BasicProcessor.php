<?php
/**
 * This file is part of the Kappa\PlaceholderProcessor package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\PlaceholderProcessor\Tests;

use Kappa\PlaceholderProcessor\PlaceholderProcessor;

class BasicProcessor extends PlaceholderProcessor
{
	public function configure()
	{
		$this->setName("placeholder");
		$this->setExternalSources(['name']);
	}

	public function run(array $sources)
	{
		return $sources['name'];
	}
}
