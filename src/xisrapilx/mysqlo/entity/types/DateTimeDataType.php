<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

use DateTime;
use Exception;

class DateTimeDataType extends DataType{

    public function getName() : string{
        return "\DateTime";
    }

    public function getMySQLTypes() : array{
        return [
            "date",
            "datetime",
            "timestamp",
            "year"
        ];
    }

    /**
     * @param $var
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function cast($var){
        if($this->checkType($var))
            return $var;

        return new DateTime($var);
    }

    public function checkType($var) : bool{
        return $var instanceof DateTime;
    }
}