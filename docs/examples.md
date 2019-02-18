# Examples

## Import CSV file and write to database

This example shows how you can read data from a CSV file and write that to the
database.

Assume we have the following CSV file:

```csv
event;beginDate;endDate
Christmas;20131225;20131226
New Year;20131231;20140101
```

And we want to write this data to a Doctrine entity:

```php
<?php

namespace MyApp;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Event
{
    /**
     * @ORM\Column()
     */
    protected $event;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $beginDate;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endDate;

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function setBeginDate($date)
    {
        $this->beginDate = $date;
    }

    public function setEndDate($date)
    {
        $this->endDate = $date;
    }

    // And some getters
}
```

First make sure to include the dependencies:

```bash
$ composer require portphp/csv portphp/doctrine portphp/steps
```

Then you can import the CSV and save it as your entity in the following way.

```php
<?php

use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\StepAggregator;
use Port\Steps\Step\ValueConverterStep;
use Port\ValueConverter\DateTimeValueConverter;

// Create and configure the reader
$file = new \SplFileObject('input.csv');
$csvReader = new CsvReader($file);

// Tell the reader that the first row in the CSV file contains column headers
$csvReader->setHeaderRowNumber(0);

// Create the workflow from the reader
$workflow = new StepAggregator($csvReader);

// Create a writer: you need Doctrine’s EntityManager.
$doctrineWriter = new DoctrineWriter($entityManager, 'MyApp:Event');
$workflow->addWriter($doctrineWriter);

// Add a converter to the workflow that will convert `beginDate` and `endDate`
// to \DateTime objects
$dateTimeConverter = new DateTimeValueConverter('Ymd');
$converterStep = new ValueConverterStep();
$converterStep
    ->add('beginDate', $dateTimeConverter)
    ->add('endDate', $dateTimeConverter);
$workflow->addStep($converterStep);

// Process the workflow
$workflow->process();
```

## Export to CSV file

This example shows how you can export data to a CSV file.

```php
<?php

use Port\Csv\CsvWriter;
use Port\Reader\ArrayReader;
use Port\Steps\StepAggregator;
use Port\Steps\Step\ValueConverterStep;

// Your input data
$reader = new ArrayReader(array(
    array(
        'first',        // This is for the CSV header
        'last',
        array(
            'first' => 'james',
            'last'  => 'Bond'
        ),
        array(
            'first' => 'hugo',
            'last'  => 'Drax'
        )
    ))
);

// Create the workflow from the reader
$workflow = new StepAggregator($reader);

// Add the writer to the workflow
$workflow->addWriter(new CsvWriter(STDOUT));

// As you can see in the input data, the first names are not capitalized
// correctly. Let's fix that with a value converter:
$valueConverterStep = new ValueConverterStep();
$valueConverterStep->add('first', function ($value) {
    return ucfirst($value);
});
$workflow->addStep($valueConverterStep);

// Process the workflow
$workflow->process();
```

This will write a CSV file `output.csv` where the first names are capitalized:

```csv
first;last
James;Bond
Hugo;Drax
```
