# PortPHP

This is the documentation for [PortPHP](https://github.com/portphp/portphp),
an awesome data import/export pipeline:

```php
<?php

use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;

$reader = new CsvReader('input.csv');
$writer = new DoctrineWriter($entityManager, 'YourApp:Person');

$writer->prepare();

// Iterate over the reader and write each row to the database
foreach ($reader as $row) {
    $writer->write($row);
}

$writer->finish();
```

## Overview

PortPHP offers a clear and simple API that abstracts from specific data sources
and targets, including Excel and CSV files, SQL and other databases, and
streams. This abstraction enables you to freely exchange data between these
media.

Broadly speaking, you can use PortPHP in two ways:

1. organize your import/export pipeline around a [workflow](workflow.md); or
2. use one or more of the components on their own, such as [readers](readers.md),
   [writers](writers.md) or [converters](converters.md).
   
## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this package:

```bash
$ composer require portphp/steps
```

This command requires you to have 
[Composer installed globally](https://getcomposer.org/doc/00-intro.md).

Then include Composer’s autoloader in your project:

```php
require_once 'vendor/autoload.php';
```

## Components

Port consists of several [components](https://packagist.org/packages/portphp/) 
to aid you in processing data:

1. [Readers](readers.md) read data of different kinds from different sources.
1. [Writers](writers.md) write data to a database, Excel or CSV file.
1. [Value converters](converters.md) transform your input data and clean it up.
1. Each of these components can be used standalone. However, you can structure
   the components around a [Workflow](workflow.md) for better re-usability.
