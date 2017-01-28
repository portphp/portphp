# Converters

Value converters take an element from the input data and transform it. You can
use them in conjunction with the [ConverterStep](workflow.md#valueconverterstep).

## ArrayValueConverterMap

{!include/todo.md!}

## CharsetValueConverter

{!include/todo.md!}

## DateTimeToStringValueConverter

The main use of DateTimeToStringValueConverter is to convert DateTime object 
into its string representation in proper format. The default format is 
`Y-m-d H:i:s`.

```php
<?php

use Port\ValueConverter\DateTimeToStringValueConverter;

$converter = new DateTimeToStringValueConverter;
call_user_func($converter, new \DateTime('2010-01-01 01:00:00'));  // will return string '2010-01-01 01:00:00'
```

## DateTimeValueConverter

There are two uses for the DateTimeValueConverter:

1. Convert a date representation in a format you specify into a `\DateTime` object.
2. Convert a date representation in a format you specify into a different format.

### Convert a date into a `\DateTime` object.

```php
<?php

use Port\Steps\StepAggregator;
use Port\Steps\Step\ConverterStep;
use Port\ValueConverter\DateTimeValueConverter;

$converter = new DateTimeValueConverter('d/m/Y H:i:s');

$converterStep = new ConverterStep();
$converterStep->add($converter);

$workflow = new StepAggregator();
$workflow->addStep($converterStep);
```

If your date string is in a [standard format](http://www.php.net/manual/en/datetime.formats.date.php) 
then you can omit the format parameter:

```php
<?php

use Port\ValueConverter\DateTimeValueConverter;

$converter = new DateTimeValueConverter();
// ...
```

### Convert a date string into a differently formatted date string.

```php
<?php

use Port\ValueConverter\DateTimeValueConverter;

$converter = new DateTimeValueConverter('d/m/Y H:i:s', 'd-M-Y');
// ...
```

If your date is in a [standard format](http://www.php.net/manual/en/datetime.formats.date.php)
 you can pass `null` as the first argument:

```php
<?php

use Port\ValueConverter\DateTimeValueConverter;

$converter = new DateTimeValueConverter(null, 'd-M-Y');
// ...
```

## MappingValueConverter

Looks for a key in a hash you must provide in the constructor:

```php
use Ddeboer\DataImport\ValueConverter\MappingValueConverter;

$converter = new MappingValueConverter(array(
    'source' => 'destination'
));

$converter->convert('source'); // destination
$converter->convert('unexpected value'); // throws an UnexpectedValueException
```

## ObjectConverter

Converts an object into a scalar value. 

### Using __toString()

If your object has a `__toString()` method, that value will be used:

```php
<?php

use Port\ValueConverter\ObjectConverter;

class SecretAgent
{
    public function __toString()
    {
        return '007';
    }
}

$converter = new ObjectConverter();
$string = call_user_func($converter, new SecretAgent());   // $string will be '007'
```

#### Using object accessors

If your object has no `__toString()` method, its accessors will be called
instead:

```php
<?php

class Villain
{
    public function getName()
    {
        return 'Bad Guy';
    }
}

class Organization
{
    public function getVillain()
    {
        return new Villain();
    }
}

use Port\ValueConverter\ObjectConverter;

$converter = new ObjectConverter('villain.name');
$string = call_user_func($converter, new Organization());   // $string will be 'Bad Guy'
```

## StringToObjectConverter

Looks up an object in the database based on a string value:

```php
<?php

use Port\Steps\Step\ValueConverterStep;
use Port\ValueConverter\StringToObjectConverter;

// $repository is a Doctrine ORM or ODM object repository
$converter = new StringToObjectConverter($repository, 'name');

$step = new ValueConverterStep();
$step->add('input_name', $converter);
```
