# Filters

Use filters in conjunction with the [FilterStep](workflow.md#filterstep).

## DateTimeThresholdFilter

This filter is useful if you want to do incremental imports. Specify a threshold
`DateTime` instance, a column name (defaults to `updated_at`), and a
`DateTimeValueConverter` that will be used to convert values read from the
filtered items. The item strictly older than the threshold will be discarded.

```php
<?php

use Port\Filter\DateTimeThresholdFilter;
use Port\ValueConverter\DateTimeValueConverter;

new DateTimeThresholdFilter(
    new DateTimeValueConverter(),
    new \DateTime('yesterday')
);
```

## OffsetFilter

OffsetFilter allows you to

* skip a certain amount of items from the beginning
* process only specified amount of items (and skip the rest)

You can combine these two parameters to process a slice from the middle of the
data, like rows 5-7 of a CSV file with ten rows.

OffsetFilter is configured by its constructor:
`new OffsetFilter($offset = 0, $limit = null)`. Note: `$offset` is a 0-based index.

```php
<?php

use Port\Filter\OffsetFilter;

// Default implementation is to start from the beginning without maximum count
$filter = new OffsetFilter(0, null);
$filter = new OffsetFilter(); // You can omit both parameters

// Start from the third item, process to the end
$filter = new OffsetFilter(2, null);
$filter = new OffsetFilter(2); // You can omit the second parameter

// Start from the first item, process max three items
$filter = new OffsetFilter(0, 3);

// Start from the third item, process max five items (items 3 - 7)
$filter = new OffsetFilter(2, 5);
```

## ValidatorFilter

It’s a common use case to validate the data before you save it to the database.
This is exactly what the ValidatorFilter does. To use it, include Symfony’s 
Validator component in your project:

```bash
$ compose require symfony/validator
```

The ValidatorFilter works as follows:

```php
<?php

use Port\Filter\ValidatorFilter;
use Symfony\Component\Validator\Constraints as Assert;

// $validator is a Symfony Validator
$filter = new ValidatorFilter($validator);
$filter->add('email', new Assert\Email());
$filter->add('sku', new Assert\NotBlank());
```

Here we add the validation assertions manually. Alternatively, you can choose to 
read assertions from annotations on the objects that you want to validate (or 
any other metadata source). To do so, use a validator that is pre-configured 
with [metadata resources](http://symfony.com/doc/current/components/validator/resources.html).

So, for instance in a Symfony project:

```php
<?php

use Port\Filter\ValidatorFilter;

// Pre-configured for looking at object annotations etc.
$validator = $container->get('validator'); 
$filter = new ValidatorFilter($validator);

// No need to manually add assertions now
```

The default behaviour for the validator is to collect all violations and skip 
each invalid row. If you want to stop on the first failing row you can call 
`ValidatorFilter::throwExceptions()`, which throws a ValidationException 
containing the line number and the violation list.
