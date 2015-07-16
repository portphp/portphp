<?php

namespace Port\Exception;

use Port\Exception;

class UnsupportedDatabaseTypeException extends \Exception implements Exception
{
    /**
     * @param array $duplicates
     */
    public function __construct($objectManager)
    {
        $message = sprintf(
            'Unknown Object Manager type. Expected \Doctrine\ORM\EntityManager or \Doctrine\ODM\MongoDB\DocumentManager, %s given',
            get_class($objectManager)
        );

        parent::__construct($message);
    }
}
