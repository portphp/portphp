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

* Workflow became an interface.
* The default workflow implementation is the [StepAggregator](workflow.md).

CSV
---

* For CSV reading and writing, you now need the port/csv package: 
  `$ composer require port/csv`. See the [docs](https://portphp.readthedocs.io) 
  for more information.

Excel
-----

* For Excel reading and writing, you now need the port/excel package: 
  `$ composer require port/excel`. See the [docs](https://portphp.readthedocs.io) 
  for more information.
