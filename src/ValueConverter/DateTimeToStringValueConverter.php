<?php

namespace Port\ValueConverter;

use DateTime;
use Port\Exception\UnexpectedValueException;

/**
 * Convert an date time object into string
 */
class DateTimeToStringValueConverter
{
    /**
     * Date time format
     *
     * @var string
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    protected $outputFormat;

    /**
     * @param string $outputFormat
     */
    public function __construct($outputFormat = 'Y-m-d H:i:s')
    {
        $this->outputFormat = $outputFormat;
    }

    /**
     * Convert string to date time object
     * using specified format
     *
     * @param mixed $input
     * @return DateTime|string|null
     * @throws UnexpectedValueException
     */
    public function __invoke($input)
    {
        if (!$input) {
            return null;
        }

        if (!($input instanceof DateTime)) {
            throw new UnexpectedValueException('Input must be DateTime object.');
        }

        return $input->format($this->outputFormat);
    }
}
