# Money bundle

[![Total Downloads](https://poser.pugx.org/garak/money-bundle/downloads.png)](https://packagist.org/packages/garak/money-bundle)
[![Build](https://github.com/garak/money-bundle/actions/workflows/build.yaml/badge.svg)](https://github.com/garak/money-bundle/actions/workflows/build.yaml)
[![Maintainability](https://api.codeclimate.com/v1/badges/1cb65549a1492cb0abcc/maintainability)](https://codeclimate.com/github/garak/money-bundle/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1cb65549a1492cb0abcc/test_coverage)](https://codeclimate.com/github/garak/money-bundle/test_coverage)


The purpose of this bundle is very simple: using the `moneyphp/money` library with a single currency.

The typical use is when you need to map an amount of money to a database field.
You could embed the Money object in your entity, but this is not perfectly working, because Doctrine
doesn't support nullable embeddable objects.

Moreover, you probably want to use a single currency in your project, so you don't want to use
a useless column for the currency itself.

With this bundle, you can just use "money" as type. It's up to you to choose if your property is
nullable or not (as for any other Doctrine mapping, the default option is being not nullable).
You're done: your property will be mapped to a Money EUR object (see below for customizations).

## Installation

Execute `composer require garak/money-bundle`.

## Mapping example

```php
<?php

namespace App\Entity;

use Money\Money;

class Foo
{
    public __construct(
        public int $id, 
        public \DateTimeImmutable $date, 
        public ?string $notes, 
        public Money $payment,
    ) {
    }
}


```

```xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping
    xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="https://doctrine-project.org/schemas/orm/doctrine-mapping https://doctrine-project.org/schemas/orm/doctrine-mapping.xsd"
>
    <entity name="App\Entity\Foo">
        <id name="id"/>
        <field name="date" type="date_immutable"/>
        <field name="notes" type="text" length="65535" nullable="true"/>
        <field name="payment" type="money"/>
    </entity>
</doctrine-mapping>
```

## Form type extension

This bundle configures a form type extension for the Symfony MoneyType, which does two things:

* set the default option of `divisor` to `100` (which should be the value to use with most currencties)
* cast the submitted value to integer, which is the format expected by `moneyphp/money`

You don't need to do anything to use this extension, which is automatically applied.

## Twig extension

This bundle exposes a Twig filter called "money". It's useful to format the value of your money property.

Example:

```twig
{# if the value in the database is 5099, this will display "€50,99" #}
{{ foo.payment|money }}
```

## Customizations

You can use a currency different from EUR.
Also, you can customize the separators used by the Twig extension, and have the symbol after the amount:

```yaml
money:
    currency: CHF   # default "EUR"
    decimal: "."    # default ","
    thousands: ","  # default "."
    after: true     # default false
```
