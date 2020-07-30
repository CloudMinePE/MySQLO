<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

class FloatDataType extends DataType{

    public function getName() : string{
        return "float";
    }

    public function getMySQLTypes() : array{
        return [
            "decimal",
            "float",
            "double",
            "real"
        ];
    }

    public function cast($var){
        if($this->checkType($var))
            return $var;

        return floatval($var);
    }

    public function checkType($var) : bool{
        return is_float($var);
    }
}