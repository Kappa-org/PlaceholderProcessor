<?php
/**
 * This file is part of the Kappa\PlaceholderProcessor package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace KappaTests\PlaceholderProcessor;

use Kappa\PlaceholderProcessor\TextFormatter;
use KappaTests\PlaceholderProcessor\Tests\BasicProcessor;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class TextFormatterTest
 * @package KappaTests\PlaceholderProcessor
 */
class TextFormatterTest extends TestCase
{
	public function testAddProcessor()
	{
		$textFormatter = new TextFormatter();
		Assert::type(get_class($textFormatter), $textFormatter->addProcessor(new BasicProcessor()));
	}

	public function testSetProcessors()
	{
		$textFormatter = new TextFormatter();
		Assert::type(get_class($textFormatter), $textFormatter->setProcessors([new BasicProcessor()]));
		Assert::type(get_class($textFormatter), new TextFormatter([new BasicProcessor()]));
	}

	public function testValidPlaceholder()
	{
		$textFormatter = new TextFormatter([new BasicProcessor()]);
		$text = "Hello %placeholder% %next%";
		Assert::same("Hello test %next%", $textFormatter->format($text, ['name' => 'test']));
	}

	public function testInvalidPlaceholder()
	{
		$textFormatter = new TextFormatter([new BasicProcessor()]);
		$text = "Hello %placeholder% %next%";
		Assert::exception(function () use($textFormatter, $text) {
			$textFormatter->format($text);
		}, 'Kappa\PlaceholderProcessor\InvalidStateException');
	}
}

run(new TextFormatterTest());
