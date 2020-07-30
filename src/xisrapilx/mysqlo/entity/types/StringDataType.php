<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

class StringDataType extends DataType{

    public function getName() : string{
        return "string";
    }

    public function getMySQLTypes() : array{
        return [
            "char",
            "varchar",
            "tinytext",
            "mediumtext",
            "text",
            "longtext",
            "binary",
            "varbinary",
            "blob",
            "tinyblob",
            "mediumblob",
            "longblob",
            "enum",
            "set",
            "json"
        ];
    }

    public function cast($var){
        if($this->checkType($var))
            return $var;

        return strval($var);
    }

    public function checkType($var) : bool{
        return is_string($var);
    }
}