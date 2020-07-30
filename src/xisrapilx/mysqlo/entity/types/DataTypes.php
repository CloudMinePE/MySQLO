<?php
declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

final class DataTypes{

    private static $TYPES = [
        "string" => StringDataType::class,
        "int" => IntDataType::class,
        "float" => FloatDataType::class,
        "boolean" => BooleanDataType::class,
        "DateTime" => DateTimeDataType::class
    ];

    public static function getByName(string $name) : ?DataType{
        if(isset(self::$TYPES[$name])){
            $type = self::$TYPES[$name];

            return new $type;
        }

        return null;
    }

    public static function add(DataType $type) : void{
        self::$TYPES[$type->getName()] = get_class($type);
    }

    public static function searchByMySQLDataType(string $needle) : ?DataType{
        foreach(self::getAll() as $type){
            if(in_array($needle, $type->getMySQLTypes())){
                return $type;
            }
        }

        return null;
    }

    /**
     * @return DataType[]
     */
    public static function getAll() : array{
        $types = [];
        foreach(self::$TYPES as $type){
            $types[] = new $type;
        }

        return $types;
    }
}