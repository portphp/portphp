1.3.0 - 2.x.x

 * Added beberlei/assert as dependency.
 * Bump php version requirement to 7.0 to handle generators efficiently.

__ReaderInterface__

[BC Break] The `Reader` interface has been changed. It now has one
method `getItems` which must return an `Generator`. All other methods has been removed.

__AppendReader__

[BC Break] The `AppendReader` does not longer extend from the `AppendIterator`.
Please use the `addReader` method or the constructor to add `Reader` instances.

__ArrayReader__

[BC Break] The `ArrayReader` does not longer extend from the `ArrayIterator`.
It now accepts all `Traversable` instances in the constructor, which makes them
more flexible.

__CountableIteratorReader__

[BC Break] This class has been removed. You can use the `ArrayReader` to replace
it.

__IteratorReader__

[BC Break] This class has been removed. You can use the `ArrayReader` to replace
it.
