## Standalone reader and writer

If you want to do a quick, one-off import, you can use the [readers](readers.md)
and [writers](writers.md) on their own. Just create a reader and a writer and
iterate over the reader:

```php
use Port\Reader\Csv;
use Port\Writer\Dbal;

$reader = new Csv('input.csv');
$writer = new Dbal();

$writer->prepare();

// Iterate over the reader and write each row to the database
foreach ($reader as $row) {
    $writer->write($row);
}
$writer->finish();
```

## Workflow

If you’re building re-usable import processes, it’s better to use the
[workflow](workflow.md).
