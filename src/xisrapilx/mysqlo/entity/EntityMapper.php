<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity;

use xisrapilx\mysqlo\exception\EntityMappingException;

final class EntityMapper{

    private function __construct(){
        //NOOP
    }

    /**
     * @param object $object
     * @param Entity $entity
     * @param array $data
     *
     * @throws EntityMappingException
     */
    public static function map(object $object, Entity $entity, array $data) : void{
        foreach($entity->getColumns() as $property => $column){
            $setterMethod = "set".ucfirst($property);
            if(method_exists($object, $setterMethod)){
                if(isset($data[$column->getName()]) && ($data[$column->getName()] !== null || $column->isNullable())){
                    $var = $data[$column->getName()];
                    if($column->getDataConverter() !== null){
                        $var = $column->getDataConverter()->convert($var);
                    }

                    call_user_func([$object, $setterMethod], $column->getType()->cast($var));
                }else{
                    if(!$column->isNullable()){
                        throw new EntityMappingException($property." is not nullable!");
                    }
                }
            }else{
                throw new EntityMappingException("Method ".$setterMethod." doesn't not exists!");
            }
        }
    }
}