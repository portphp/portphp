# Writers

Writers output the data to  
take the data from the [readers](readers.md)

## ArrayWriter

Resembles the [ArrayReader](#arrayreader). Probably most useful for testing
your workflow.

## CsvWriter

Writes CSV files.

{!include/csv.md!}

Then use the writer:

```php
use Port\Csv\CsvWriter;

$writer = new CsvWriter();
$writer->setStream(fopen('output.csv', 'w'));

// Write column headers:
$writer->writeItem(['first', 'last']);

// Write some data
$writer->writeItem(['James', 'Bond']);
$writer->writeItem(['Auric', 'Goldfinger'])

$writer->finish();
```

## DoctrineWriter

Writes data through the [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html)
and [ODM](http://docs.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/).

{!include/doctrine.md!}

```php
use Port\Doctrine\DoctrineWriter;

$writer = new DoctrineWriter($objectManager, 'YourNamespace:Employee');
$writer->prepare();
$writer->writeItem(
    [
        'first' => 'James',
        'last'  => 'Bond'
    ]
);
$writer->finish();
```

By default, DoctrineWriter will truncate your data before running the workflow.
Call `disableTruncate()` if you don't want this.

If you are not truncating data, DoctrineWriter will try to find an entity having it's primary key set to the value of
the first column of the item. If it finds one, the entity will be updated, otherwise it's inserted.
You can tell DoctrineWriter to lookup the entity using different columns of your item by passing a third parameter to
it's constructor.

```php
$writer = new DoctrineWriter($entityManager, 'YourNamespace:Employee', 'columnName');
```

or

```php
$writer = new DoctrineWriter($entityManager, 'YourNamespace:Employee', ['column1', 'column2', 'column3']);
```

The DoctrineWriter will also search out associations automatically and link them by an entity reference. For example
suppose you have a Product entity that you are importing and must be associated to a Category. If there is a field in
the import file named 'Category' with an id, the writer will use metadata to get the association class and create a
reference so that it can be associated properly. The DoctrineWriter will skip any association fields that are already
objects in cases where a converter was used to retrieve the association.

## ExcelWriter

Writes data to an Excel file. 

{!include/excel.md!}

Then construct an ExcelWriter:

```php
use Port\Excel\ExcelWriter;

$file = new \SplFileObject('data.xlsx', 'w');
$writer = new ExcelWriter($file);

$writer->prepare();
$writer->writeItem(['first', 'last'])
$writer->writeItem(['first' => 'James', 'last' => 'Bond'])
$writer->finish();
```

You can specify the name of the sheet to write to:

```php
$writer = new ExcelWriter($file, 'My sheet');
```

You can open an already existing file and add a sheet to it:

```php
$file = new \SplFileObject('data.xlsx', 'a');   // Open file with append mode
$writer = new ExcelWriter($file, 'New sheet');
```

If you wish to overwrite an existing sheet instead, specify the name of the
existing sheet:

```php
$writer = new ExcelWriter($file, 'Old sheet');
```

## PdoWriter

Use the PDO writer for importing data into a relational database (such as
MySQL, SQLite or MS SQL) without using Doctrine.

```php
use Port\Writer\PdoWriter;

$pdo = new \PDO('sqlite::memory:');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$writer = new PdoWriter($pdo, 'my_table');
```

## Symfony Console

### TableWriter

This writer displays items as table on console output for debug purposes
when you start the workflow from the command-line.

{!include/symfony-console.md!}

```php
use Port\Reader;
use Port\Steps\StepAggregator as Workflow;
use Port\SymfonyConsole\TableWriter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\Table;

$reader = new Reader\...;
$output = new ConsoleOutput(...);

$table = new Table($output);

// Make some manipulations, e.g. set table style
$table->setStyle('compact');

$workflow = new Workflow($reader);
$workflow->addWriter(new TableWriter($output, $table));
```

### ProgressWriter

This writer displays import progress when you start the workflow from the
command-line.

```php
use Port\SymfonyConsole\ProgressWriter;
use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput(...);
$progressWriter = new ProgressWriter($output, $reader);

// Most useful when added to a workflow
$workflow->addWriter($progressWriter);
```

There are various optional arguments you can pass to the `ConsoleProgressWriter`.
These include the output format and
the redraw frequency. You can read more about the options [here](http://symfony.com/doc/current/components/console/helpers/progressbar.html).

You might want to set the redraw rate higher than the default as it can slow
down the import/export process quite a bit as it will update the console text 
after every record has been processed by the Workflow.

```php
$output = new ConsoleOutput(...);
$progressWriter = new ProgressWriter($output, $reader, 'debug', 100);
```

Above we set the output format to 'debug' and the redraw rate to 100. This will
only re-draw the console progress text
after every 100 records.

The `debug` format is default as it displays ETA's and Memory Usage. You can use
a more simple formatter if you wish:

```php
$output = new ConsoleOutput(...);
$progressWriter = new ProgressWriter($output, $reader, 'normal', 100);
```

## StreamMergeWriter

Suppose you have two stream writers handling fields differently according to 
one of the fields. You should then use `StreamMergeWriter` to call the 
appropriate Writer for you.

The default field name is `discr` and can be changed with the
`setDiscriminantField()` method.

```php
<?php

use Port\Writer\StreamMergeWriter;

$writer = new StreamMergeWriter();

$writer->addWriter('first writer', new MyStreamWriter());
$writer->addWriter('second writer', new MyStreamWriter());
```

## XmlWriter

Writes XML files.

{!include/xml.md!}

First construct PHP’s built-in XMLWriter, then wrap it in `Port\Xml\XmlWriter',
additionally passing the filename to write to:

```php
<?php

use Port\Xml\XmlWriter;

$phpXmlWriter = new \XMLWriter();
$writer = new XmlWriter($phpXmlWriter, 'output-file.xml');
```

Simply pass the writer to a [workflow](workflow.md) or use the writer on its own:

```php
<?php

$writer->prepare();

foreach ($data as $item) {
    $writer->writeItem($item);
}

$writer->finish();
```

Pass the root and item elements as the third and fourth arguments:

```php
<?php

$writer = new XmlWriter(
    $phpXmlWriter,
    'output-file.xml',
    'things', // root item
    'thing'   // element item
);
```


## Create a writer

Build your own writer by implementing the Writer interface.

### AbstractStreamWriter

Instead of implementing your own writer from scratch, you can use
AbstractStreamWriter as a basis. Just implement `writeItem()`:

```php
<?php

use Port\Writer\AbstractStreamWriter;

class MyStreamWriter extends AbstractStreamWriter
{
    public function writeItem(array $item)
    {
        fputs($this->getStream(), implode(',', $item));
    }
}

$writer = new MyStreamWriter(fopen('php://temp', 'r+'));
$writer->setCloseStreamOnFinish(false);

$workflow->addWriter(new MyStreamWriter());
$workflow->process();

$stream = $writer->getStream();
rewind($stream);

echo stream_get_contents($stream);
```

### CallbackWriter

You can also use the quick solution the CallbackWriter offers:

```php
<?php

use Port\Writer\CallbackWriter;

$workflow->addWriter(new CallbackWriter(function ($row) use ($storage) {
    $storage->store($row);
}));
```
