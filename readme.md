# Document Object Model

 Life improving enhancements to the standard PHP DOM.

[![Build Status](https://secure.travis-ci.org/stoatally/dom.png?branch=master)](http://travis-ci.org/stoatally/dom)


## Install

The recommended way to install Text Expressions is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "stoatally/dom": "0.*"
    }
}
```


## Usage

Create a document factory:

```php
use Stoatally\Dom\DocumentFactory;

$factory = new DocumentFactory();
```

Set the text content of a node:

```php
$document = $factory->createFromString('<p/>');
$document->select('p')->setContents('PHP <3');
// > <p>PHP &lt;3</p>
```

For more comprehensive examples see the [examples](./examples) and [tests](./tests) directories.
