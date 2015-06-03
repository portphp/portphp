# StepAggregator

The StepAggregator is a [workflow](workflow.md) that is divided into steps.
Each step performs a specific task, such as filtering, converting or mapping
your data. The steps can be combined in any order you like.

```php
use Port\Steps\StepAggregator;

$stepAggregator = new StepAggregator();

$converterStep = new ConverterStep([
    function($item) { return array('name' => $item->name); }
]);


```

# Steps

Port ships with a handful of steps. You can easily add your own. You can use
each step by itself, but they become most ueseful when used together in a
StepAggregator workflow (see above).

## ConverterStep

Converts your input data. Construct a `ConverterStep` and add one or more callables
to it that do the conversion:

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
If any of the callables in the step returns false, the data will be skipped from
processing:

```php
use Port\Reader\ArrayReader;
use Port\Writer\ArrayWriter;
use Port\Steps\StepAggregator;
use Port\Steps\Step\FilterStep;

$step = new FilterStep();
$step->add(function ($input) { return $input >= 3; });
$step->add(function ($input) { return $input < 7; });

$data = new ArrayReader(range(0, 10));
$stepAggregator = new StepAggregator();
$stepAggregator->addWriter(new ArrayWriter($output));
$stepAggregator->addStep($step);

$stepAggregator->process();

var_dump($output);   // [3, 4, 5, 6]
```

## MappingStep

## ValidatorStep

## ValueConverterStep

## Writing your own steps

This is easy. Create a class that implements the `\Port\Steps\Step` interface:

```php
use Port\Steps\Step;

class AwesomeAdditionStep implements Step
{
    public function process(&$item)
    {
        // This property will be added to your data
        $item['extra_property'] = 'ooh this is custom';
    }
}
```

To create a filtering step, return false when you do not like the data:

```php
use Port\Steps\Step;

class AwesomeFilteringStep implements Step
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
