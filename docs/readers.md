Readers read data that will be imported by iterating over it. This library
includes a handful of readers. Additionally, you can easily
[implement your own](#create-a-reader).

Readers are optimised to use as little memory as possible

You can use readers on their own, or construct a workflow from them:

```php
$workflow = new Workflow($reader);
```
## Arrays

Reads arrays. Most useful for testing your workflow.

## CsvReader

Install the [CSV adapter](https://github.com/portphp/csv-adapter):

```bash
$ composer require portphp/csv
```

Then use the CsvReader to iterate over CSV files:

```php
use Port\Csv\CsvReader;

$file = new \SplFileObject('/path/to/csv_file.csv');
$reader = new CsvReader($file);
```

You can pass the CSV delimiter, enclosure and escape character as arguments:

```php
// These are the defaults:
$delimiter = ';';
$enclosure = '"';
$escape = '\\';

$reader = new CsvReader($file, $delimiter, $enclosure, $escape);
```

Then iterate over the CSV file:

```php
foreach ($reader as $row) {
    // $row will be an array containing the comma-separated elements of the line:
    // [
    //   0 => 'James',
    //   1 => 'Bond'
    //   etc...
    // ]
}
```

##### Column headers

If one of your rows contains column headers, you can read them to make the rows
associative arrays:

```php
$reader->setHeaderRowNumber(0);

foreach ($reader as $row) {
    // $row will now be an associative array:
    // [
    //   'firstName' => 'James',
    //   'lastName'  => 'Bond'
    //   etc...
    // ]
}
```

### Strict mode

The CSV reader operates in strict mode by default. If the reader encounters a
row where the number of values differs from the number of column headers, an
error is logged and the row is skipped. Retrieve the errors with `getErrors()`.

To disable strict mode, set `$reader->setStrict(false)` after you instantiate
the reader.

Disabling strict mode means:

1. Any rows that contain fewer values than the column headers are simply
   padded with null values.
2. Any additional values in a row that contain more values than the
   column headers are ignored.

Examples where this is useful:

- **Outlook 2010:** which omits trailing blank values
- **Google Contacts:** which exports more values than there are column headers

### Duplicate headers

Sometimes a CSV file contains duplicate column headers, for instance:

id  | details  | details
--- | -------- | --------
1   | bla      | more bla

By default, a `DuplicateHeadersException` will be thrown if you call
`setHeaderRowNumber(0)` on this file. You can handle duplicate columns in
one of three ways:
* call `setColumnHeaders(['id', 'details', 'details_2'])` to specify your own
  headers
* call `setHeaderRowNumber` with the `CsvReader::DUPLICATE_HEADERS_INCREMENT`
  flag to generate incremented headers; in this case: `id`, `details` and
  `details1`
* call `setHeaderRowNumber` with the `CsvReader::DUPLICATE_HEADERS_MERGE` flag
  to merge duplicate values into arrays; in this case, the first row’s values
  will become: `[ 'id' => 1, 'details' => [ 'bla', 'more bla' ] ]`.

## Doctrine DBAL

Reads data through [Doctrine’s DBAL](http://www.doctrine-project.org/projects/dbal.html).
Your project should include Doctrine’s DBAL package:

```bash
$ composer require doctrine/dbal
```

```php
use Port\Reader\Dbal\DbalReader;

$reader = new DbalReader(
    $connection, // Instance of \Doctrine\DBAL\Connection
    'SELECT u.id, u.username, g.name FROM `user` u INNER JOIN groups g ON u.group_id = g.id'
);
```

## Doctrine ORM/ODM

Reads data through the [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html).

Install the [Doctrine adapter](https://github.com/portphp/doctrine-adapter):

```bash
$ composer require portphp/doctrine-adaoter
```

Then use the reader:

```php
use Port\Doctrine\DoctrineReader;

$reader = new DoctrineReader($entityManager, 'Your\Namespace\Entity\User');
```

## Excel

An adapter for the [PHPExcel library](http://phpexcel.codeplex.com/). Install
the Excel adapter:

```bash
$ composer require portphp/excel-adapter
```

Then use the reader to open an Excel file:

```php
use Port\Reader\Excel\ExcelReader;

$file = new \SplFileObject('path/to/ecxel_file.xls');
$reader = new ExcelReader($file);
```

To set the row number that headers will be read from, pass a number as the second
argument.

```php
$reader = new ExcelReader($file, 2);
```

To read the specific sheet:

```php
$reader = new ExcelReader($file, null, 3);
```

### OneToManyReader

Allows for merging of two data sources (using existing readers), for example you have one CSV with orders and another with order items.

Imagine two CSV's like the following:

```
OrderId,Price
1,30
2,15
```

```
OrderId,Name
1,"Super Cool Item 1"
1,"Super Cool Item 2"
2,"Super Cool Item 3"
```

You want to associate the items to the order. Using the OneToMany reader we can nest these rows in the order using a key
which you specify in the OneToManyReader.

The code would look something like:

```php
$orderFile = new \SplFileObject("orders.csv");
$orderReader = new CsvReader($file, $orderFile);
$orderReader->setHeaderRowNumber(0);

$orderItemFile = new \SplFileObject("order_items.csv");
$orderItemReader = new CsvReader($file, $orderFile);
$orderItemReader->setHeaderRowNumber(0);

$oneToManyReader = new OneToManyReader($orderReader, $orderItemReader, 'items', 'OrderId', 'OrderId');
```

The third parameter is the key which the order item data will be nested under. This will be an array of order items.
The fourth and fifth parameters are "primary" and "foreign" keys of the data. The OneToMany reader will try to match the data using these keys.
Take for example the CSV's given above, you would expect that Order "1" has the first 2 Order Items associated to it due to their Order Id's also
being "1".

Note: You can omit the last parameter, if both files have the same field. Eg if parameter 4 is 'OrderId' and you don't specify
parameter 5, the reader will look for the foreign key using 'OrderId'

The resulting data will look like:

```php
//Row 1
[
    'OrderId' => 1,
    'Price' => 30,
    'items' => [
        [
            'OrderId' => 1,
            'Name' => 'Super Cool Item 1',
        ],
        [
            'OrderId' => 1,
            'Name' => 'Super Cool Item 2',
        ],
    ],
];

//Row2
[
    'OrderId' => 2,
    'Price' => 15,
    'items' => [
        [
            'OrderId' => 2,
            'Name' => 'Super Cool Item 1',
        ],
    ]
];
```

## Create a reader

You can create your own data reader by implementing the
[Reader](/../src/Reader.php) interface, which extends the PHP
[Iterator interface](http://php.net/manual/en/class.iterator.php). To get an
idea, have a look at the [readers included in this library](https://github.com/portphp/portphp/tree/master/src/Reader).
