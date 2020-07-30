<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

class IntDataType extends DataType{

    public function getName() : string{
        return "int";
    }

    public function getMySQLTypes() : array{
        return [
            "int",
            "tinyint",
            "smallint",
            "bigint",
            "bit",
            "serial"
        ];
    }

    public function cast($var){
        if($this->checkType($var))
            return $var;

        return intval($var);
    }

    public function checkType($var) : bool{
        return is_int($var);
    }
}