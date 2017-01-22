# PortPHP

This is the documentation for [PortPHP](https://github.com/portphp/portphp),
an awesome data import/export pipeline:

```php
<?php
use Port\Reader\CsvReader;
use Port\Writer\DbalWriter;

$reader = new CsvReader('input.csv');
$writer = new DbalWriter();

$writer->prepare();

// Iterate over the reader and write each row to the database
foreach ($reader as $row) {
    $writer->write($row);
}
$writer->finish();
```

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this package:

```bash
$ composer require portphp/steps
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

# Components

Port consists of several components to aid you in processing data.

1. [Readers](readers.md) read data of different kinds from different sources.
1. [Writers](writers.md) write data to a database, Excel or CSV file.
1. [Value converters](converters.md) transform your input data and clean it up.
1. Each of these components can be used standalone. However, you can structure
   the components around a [Workflow](workflow.md) for better re-usability.
