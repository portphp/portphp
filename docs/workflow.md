# Workflow

The workflow streamlines your data imports/exports. Using the workflow, you can
re-usable pipelines for processing your data. Each pipeline takes place along 
the following lines:

1. Construct a [reader](readers.md).
2. Construct a workflow, passing the reader to it. Optionally set a logger on 
   the workflow. 
3. Add at least on [writer](writers.md) to the workflow. Optionally, customize 
   the workflow by adding filters, converters, mappers, etc.
4. Process the workflow. This will read data from the reader, filters and 
   convert the data, and write the output to each of the writers. At the end of
   the process, a [Result](#the-workflow-result) object is returned which 
   contains counts and information about (failed) reads and writes.
   
In other words, the workflow acts as a *mediator* between a reader and one or 
more writers, filters and converters. Schematically:

```php
use Port\Steps\StepAggregator as Workflow;
use Port\Reader;
use Port\Writer;

$reader = new Reader\...;
$workflow = new Workflow($reader);
$result = $workflow
    ->addWriter(new Writer\...())
    ->addWriter(new Writer\...())
    ->process()
;
```

## The workflow result

The Workflow `Result` object exposes various methods which you can use to 
decide what to do after an import. It is automatically created and populated by
the Workflow. It will be returned to you after calling the `process()` method on 
the Workflow. Examples use cases include:

- send an e-mail with the results of the import pipeline
- send a text alert if a particular file failed
- move an import file to a failed directory if there were any errors
- log and monitor how long imports are taking.

The Result provides the following methods:

```php
// The name of the import - which is an optional 3rd parameter to the Workflow
// class. Returns null by default.
public function getName();

// DateTime instance created at the start of the import.
public function getStartTime();

// DateTime instance created at the end of the import.
public function getEndTime();

// DateInterval instance. Diff off the start + end times.
public function getElapsed();

// Count of exceptions which caught by the Workflow.
public function getErrorCount();

// Count of processed items minus the count of exceptions caught.
public function getSuccessCount();

// Count of items processed
//This will not include any filtered items or items which fail conversion.
public function getTotalProcessedCount();

// bool to indicate whether any exceptions were caught.
public function hasErrors();

// An array of exceptions caught by the Workflow.
public function getExceptions();
```

# StepAggregator

Currently, PortPHP ships with one Workflow, the StepAggregator, which is 
flexible enough to cover a wide array of data processing workflows.

The StepAggregator is an import/export workflow that is divided into steps.
Each step performs a specific task, such as filtering, converting or mapping
your data. The steps can be combined in any order you like.

{!include/steps.md!} 

```php
use Port\Steps\StepAggregator;
use Port\Steps\Step\ConverterStep;

$stepAggregator = new StepAggregator($reader);

$converterStep = new ConverterStep([
    function($item) { return ['name' => $item->name]; }
]);
```

Port ships with a handful of steps. You can easily add your own. You can use
each step by itself, but they become most useful when used together in a
StepAggregator workflow (see above).

## ConverterStep

Converts your input data. Construct a `ConverterStep` and add one or more 
callables to it that do the conversion:

```php
use Port\Steps\Step\ConverterStep;

$step = new ConverterStep();
$step
    ->add(function($input) { return 'ding'; })
    ->add(function($input) { return 'dong'; })
;

$output = $step->process('some data');   // 'dong'
```

## FilterStep

The filter step determines whether the input data should be processed further.
If any of the callables in the step returns false, the data will be skipped 
from processing:

```php
<?php

use Port\Reader\ArrayReader;
use Port\Writer\ArrayWriter;
use Port\Steps\StepAggregator as Workflow;
use Port\Steps\Step\FilterStep;

$step = new FilterStep();
$step->add(function ($input) { return $input >= 3; });
$step->add(function ($input) { return $input < 7; });

$reader = new ArrayReader(range(0, 10));
$workflow = new Workflow($reader);
$workflow->addWriter(new ArrayWriter($output));
$workflow->addStep($step);

$workflow->process();

var_dump($output);   // [3, 4, 5, 6]
```

You can also use any of the supplied [filters](filters.md):

```php
<?php

use Port\Filter\OffsetFilter;
use Port\Steps\Step\FilterStep;

// ...

$step = new FilterStep();
$step->add(new OffsetFilter(5, 10));

// ...
```

## MappingStep

Use the MappingItemConverter to add mappings to your workflow. Your keys from
the input data will be renamed according to these mappings. 

Say you have input data:
  
```php
$data = [
    'foo' => 'bar',
];
```

```php
use Port\Steps\StepAggregator as Workflow;
use Port\Steps\Step\MappingStep;

$mappingStep = new MappingStep();

                  // from  // to
$mappingStep->map('[foo]', '[baz]');

$workflow = new Workflow();
$workflow->addStep($mappingStep);

$workflow->process();
```

Your output data will now be:

```php
[
    'baz' => 'bar'
];
```

## ValidatorStep

Validate data items, only process them if they pass validation and collect all
validation failures.

```php

```

## ArrayCheckStep

{!include/todo.md!}

## ValueConverterStep

Value converters are used to convert specific fields (e.g., columns in database).

You can also one of the many supplied [converters.md](converters.md).

## Writing your own steps

This is easy. Create a class that implements the `\Port\Steps\Step` interface:

```php
use Port\Steps\Step;

class SpecialAdditionStep implements Step
{
    public function process(&$item)
    {
        // This property will be added to your data
        $item['extra_property'] = 'ooh this is custom';
    }
}
```

To create a filtering step, to reject the data:

```php
use Port\Steps\Step;

class SpecialFilteringStep implements Step
{
    public function process(&$item)
    {
        // Only accept items whose ding is dong.
        if ($item['ding'] == 'dong') {
            return true;
        }

        // All other items will not be processed any further by the workflow.
        return false;
    }
}
```

## Create your own workflow

If you even more flexibility, you can implement your own 
[Workflow](https://github.com/portphp/portphp/blob/master/src/Workflow.php).

{!include/todo.md!}
