<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

class BooleanDataType extends DataType{

    public function getName() : string{
        return "bool";
    }

    public function getMySQLTypes() : array{
        return ["boolean"];
    }

    public function cast($var){
        if($this->checkType($var))
            return $var;

        return boolval($var);
    }

    public function checkType($var) : bool{
        return is_bool($var);
    }
}