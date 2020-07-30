<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use xisrapilx\mysqlo\entity\types\DataType;
use xisrapilx\mysqlo\entity\types\DataTypes;
use xisrapilx\mysqlo\entity\types\StringDataType;
use xisrapilx\mysqlo\exception\EntityException;
use xisrapilx\mysqlo\utils\Utils;

/**
 * Use EntityManager::createAndAdd to create and configure this
 */
final class Entity{

    /** @var string */
    private $tableName;

    /** @var EntityColumn[] */
    private $columns = [];

    /**
     * Entity constructor.
     *
     * @param string $className Original entity class name
     * @param bool $autoInit Create default table schema by class
     *
     * @throws EntityException
     */
    public function __construct(string $className, bool $autoInit = true){
        if($autoInit)
            $this->initDefaultSchema($className);
    }

    /**
     * Hellllllll
     *
     * @param string $className
     * @throws EntityException
     */
    private function initDefaultSchema(string $className){
        try{
            $reflectionClass = new ReflectionClass($className);
            $docComment = $reflectionClass->getDocComment();
            $docCommentData = [];

            if($docComment !== false){
                try{
                    $docCommentData = Utils::parseDocComment($reflectionClass->getDocComment());
                }catch(Exception $exception){
                    throw new EntityException("Cannot parse ".$reflectionClass->getName()." doc comment!", 0, $exception);
                }
            }

            $this->tableName = isset($docCommentData["table"]) && $docCommentData["table"] !== ""
                ? $docCommentData["table"] : $reflectionClass->getShortName();

            foreach($reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE) as $reflectionProperty){
                if(!$reflectionProperty->isStatic()){
                    $docComment = $reflectionProperty->getDocComment();

                    if($docComment)
                        try{
                            $docCommentData = Utils::parseDocComment($reflectionProperty->getDocComment());
                        }catch(Exception $e){
                            throw new EntityException("Cannot parse ".$reflectionProperty->getName()." doc comment!");
                        }
                    else
                        $docCommentData = [];

                    $columnName = $reflectionProperty->getName();
                    if(isset($docCommentData["column"]) && $docCommentData["column"] !== ""){
                        $columnName = $docCommentData["column"];
                    }

                    $dataType = isset($docCommentData["var"]) ? DataTypes::getByName($docCommentData["var"]) : null;

                    if($dataType === null){
                        $dataType = new StringDataType();
                    }
                    $mySQLDataType = isset($docCommentData["column_type"]) ? $docCommentData["column_type"] : null;
                    if($mySQLDataType !== null){
                        $dataType->setCurrentMySQLType($mySQLDataType);
                    }

                    $dataConverter = isset($docCommentData["converter"]) ? $docCommentData["converter"] : null;
                    if($dataConverter !== null){
                        $dataConverter = new $dataConverter;
                        if(!$dataConverter instanceof DataConverter){
                            $dataConverter = null;
                        }
                    }

                    $this->columns[$reflectionProperty->getName()] = new EntityColumn($columnName, $dataType, isset($docCommentData["nullable"]), $dataConverter);
                }
            }
        }catch(ReflectionException $exception){
            throw new EntityException("Cannot parse ".$className, 0, $exception);
        }
    }

    /**
     * @return string[]
     */
    public function getColumns() : array{
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getTableName() : string{
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return Entity
     */
    public function setTableName(string $tableName) : self{
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Add column binding to property
     *
     * @param string $propertyName Class property name
     * @param string $columnName Table column name
     * @param DataType $dataType Php datatype
     * @param string $mysqlDataType MySQL column type
     * @param bool $nullable
     */
    public function addColumn(string $propertyName, string $columnName, DataType $dataType, string $mysqlDataType, bool $nullable = true){
        $dataType->setCurrentMySQLType(strtolower($mysqlDataType));
        $this->columns[$propertyName] = new EntityColumn($columnName, $dataType, $nullable);
    }
}