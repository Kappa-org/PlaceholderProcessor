<?php
/**
 * This file is part of the PlaceholderProcessor package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\PlaceholderProcessor\DI;

use Kappa\PlaceholderProcessor\TextFormatter;
use Nette\DI\Container;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class PlaceholderProcessorExtensionTest
 * @package Kappa\PlaceholderProcessor\DI
 */
class PlaceholderProcessorExtensionTest extends TestCase
{
	/** @var Container */
	private $container;

	protected function setUp()
	{
		$this->container = getContainer();
	}

	public function testTextFormatter()
	{
		$type = 'Kappa\PlaceholderProcessor\TextFormatter';
		/** @var TextFormatter $service */
		$service = $this->container->getByType($type);
		Assert::type($type, $this->container->getByType($type));
		Assert::same("Hello test", $service->format("Hello %placeholder%", ['name' => 'test']));
		Assert::true($service->isStrictMode());
	}
}

run(new PlaceholderProcessorExtensionTest());
