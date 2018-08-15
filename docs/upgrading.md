Upgrading to 1.0
================

This describes how to upgrade to PortPHP 1.0 from 
[ddeboer/data-import](https://github.com/ddeboer/data-import) 0.20.0.

General
-------

* The single ddeboer/data-import repository was split into several packages to
  improve separation of concerns and dependency management.
* The namespace changed from `Ddeboer\DataImport\` to `Port\`.

Workflow
--------

* The Workflow class became an interface.
* The default workflow implementation is the [StepAggregator](workflow.md).

Step
----

 * The `Step` interface has changed from `public function process(&$item);` to `public function process($item, callable $next);`

Readers
-------

* The `Reader::getFields()` method was removed.
* For CSV reading, you now need the CSV package: 
  `$ composer require portphp/csv`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
* For Excel reading, you now need the Excel package: 
  `$ composer require portphp/excel`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
* For Spreadsheet reading, you now need the Spreadsheet package: 
  `$ composer require portphp/spreadsheet`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
  
Writers
-------

* For CSV writing, you now need the CSV package: 
  `$ composer require portphp/csv`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
* For Excel writing, you now need the Excel package: 
  `$ composer require portphp/excel`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
* For Spreadsheet writing, you now need the Spreadsheet package: 
  `$ composer require portphp/spreadsheet`. See the [docs](https://portphp.readthedocs.io) 
  for more information.

Converters
----------

* All item converters (MappingItemConverter, NestedMappingItemConverter, CallbackItemConverter) 
  were removed.

Filters
-------

* CallbackFilter was removed.
