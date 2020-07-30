<?php

declare(strict_types=1);

use xisrapilx\mysqlo\entity\types\DataType;
use xisrapilx\mysqlo\entity\types\DataTypes;

class DataTypeDataType extends DataType{

    public function getName() : string{
        return DataType::class;
    }

    public function getMySQLTypes() : array{
        return [];
    }

    public function checkType($var) : bool{
        return $var instanceof self;
    }

    public function cast($var){
        $type = DataTypes::searchByMySQLDataType($var);
        $type->setCurrentMySQLType($var);

        return $type;
    }
}