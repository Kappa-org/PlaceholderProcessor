# Kappa\PlaceholderProcessor [![Build Status](https://travis-ci.org/Kappa-org/PlaceholderProcessor.svg?branch=master)](https://travis-ci.org/Kappa-org/PlaceholderProcessor)

Easy system for working with placeholders in texts

## Content

* [Requirements](#requirements)
* [Installation](#installation)
* [Usages](#usages)
    * [1. step - Prepare custom placeholder processors](#1-step---prepare-custom-placeholder-processors)
    * [2. step - Create a new instance](#2-step---create-a-new-instance)
    * [3. step - Usage](#3-step---usage)
    * [Strict mode](#strict-mode)

## Requirements

Full list of dependencies you can get from [Composer config file](https://github.com/Kappa-org/PlaceholderProcessor/blob/master/composer.json)

* PHP 5.4 or higher

## Installation

The best way to install Kappa\PlaceholderProcessor is using [Composer](https://getcomposer.org)

```shell
$ composer require kappa/placeholderprocessor
```

## Usages

### 1. step - Prepare custom placeholder processors

There is a few rules how to create a custom placeholder processors. Each you placeholder
processor must implements `\Kappa\PlaceholderProcessor\IPlaceholderProcessor` interface or
and it is **recommended** you can extend your class from 
`\Kappa\PlaceholderProcessor\PlaceholderProcessor` class which implements this interface and 
prepares logic for easy and quick usage.

For example:

```php
<?php
use Kappa\PlaceholderProcessor\PlaceholderProcessor;

class MySuperPlaceholderProcessor extends PlaceholderProcessor
{
	private $db;
	
	public function __construct(Database $db) 
	{
	    $this->db = $db;
	}
	
	public function configure()
	{
		$this->setName("mySuperPlaceholderProcessor");
		$this->setExternalSources(['user_id']);
	}

	public function run(array $sources = [])
	{
		return $this->db->find('users', $sources['user_id'])->getName();
	}
}

```

This placeholder will replace `%mySuperPlaceholderProcessor%` placeholder by user name by id
which is set as external source.

You must configure your processor in `configure` method. In this method you must set name 
of placeholder if format `%<name>%`. In this case this placeholder will be works with
`%mySuperPlaceholderProcessor%` placeholder.

The second important settings is list of external sources. This list will be used for automatic
compare with `$source` in `run` method. You can be sure that each of item from external source
will be in `$source` in `run` method. This sources will be given from 
`TextFormatter::format($text, $sources)`.

Returns from `run` method will be used for replacing placeholder.

### 2. step - Create a new instance

For translate placeholders you need instance of `Kappa\PlaceholderProcessor\TextFormatter`

You can create this instance manually
 
```php
$textFormatter = new TextFormatter([
    new MySuperPlaceholderProcessor()
]);
```

or you can use `setPlaceholders(array)` method

```php
$textFormatter = new TextFormatter();
$textFormatter->setProcessors([
  new MySuperPlaceholderProcessor()
]);
```

or when you use [Nette Framework](https://nette.org) you can 
register this package as extension

```neon
extensions: 
    placeholderProcessor: Kappa\PlaceholderProcessor\DI\PlaceholderProcessorExtension
    
placeholderProcessor:
    placeholders:
        - MySuperPlaceholderProcessor
```

```php
public function __construct(TextFormatter $textFormatter) {
    
}
```

of course you can add single processor `$textFormatter->addProcessor(new MySuperPlaceholderProcessor());`.

### 3. step - Usage

Now we have custom placeholder and instance of formatter and you can try translate this text
`Hello %mySuperPlaceholderProcessor%, %foo%`

```php
$textFormatter = new TextFormatter([
    new mySuperPlaceholderProcessor($db)
]);

$output = $textFormatter->format('Hello %mySuperPlaceholderProcessor%, %foo%', ['user_id' => 1]);
```

Formatter replace `%mySuperPlaceholderProcessor%` by logic in `MySuperPlaceholderProcessor` class.
This processor gets `user_id` from external source (second argument in `format` method), find
user in database and returns his name which can be used for replacing original placeholder.
Placeholder `%foo%` will be ignored because there isn't any processor with this name.

Output: `Hello Joe, %foo%`.

### Strict mode

Strict mode is turn off by default.

When you need strict mode which throws exception when in text is placeholder which hasn't any
processor (as `%foo%` in previous example). You can turn on (or off) by `setStrictMode(true)` 
method or when you use [Nette Framework](https://nette.org) in config file by `strict: true`

