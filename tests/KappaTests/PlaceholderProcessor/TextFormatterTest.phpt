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
		$textFormatter = new TextFormatter([], false);
		Assert::type(get_class($textFormatter), $textFormatter->setProcessors([new BasicProcessor()]));
		Assert::type(get_class($textFormatter), new TextFormatter([new BasicProcessor()]));
	}

	public function testValidPlaceholder()
	{
		$textFormatter = new TextFormatter([new BasicProcessor()]);
		$text = "Hello %placeholder% %next%";
		Assert::same("Hello test %next%", $textFormatter->format($text, ['name' => 'test']));
	}

	public function testMissingPlaceholderWithoutStrictMode()
	{
		$textFormatter = new TextFormatter();
		$textFormatter->setStrictMode(false);
		$text = 'Hello %placeholder%';
		Assert::same($text, $textFormatter->format($text));
	}

	public function testMissingPlaceholderWithStrictMode()
	{
		Assert::throws(function () {
			$textFormatter = new TextFormatter();
			$textFormatter->setStrictMode(true);
			$text = 'Hello %placeholder%';
			$textFormatter->format($text);
		}, '\Kappa\PlaceholderProcessor\MissingPlaceholderProcessorException');
	}

	public function testMissingExternalSource()
	{
		Assert::throws(function () {
			$textFormatter = new TextFormatter([new BasicProcessor()], false);
			$text = 'Hello %placeholder%';
			$textFormatter->format($text);
		}, '\Kappa\PlaceholderProcessor\MissingExternalSourceException');
	}

	public function testSetStrictMode()
	{
		$withoutStrict = new TextFormatter();
		$withoutStrict->setStrictMode(false);
		Assert::false($withoutStrict->isStrictMode());

		$withStrict = new TextFormatter();
		$withStrict->setStrictMode(true);
		Assert::true($withStrict->isStrictMode());
	}

	public function testDefaultScriptMode()
	{
		$textFormatter = new TextFormatter();
		Assert::false($textFormatter->isStrictMode());
	}
}

run(new TextFormatterTest());
