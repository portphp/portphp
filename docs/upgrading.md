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
* The default workflow implementation is the [StepAggregator](stepaggregator.md).
