<?php

declare(strict_types=1);

class Column{

    /**
     * @column TABLE_NAME
     * @var string
     */
    private $tableName;

    /**
     * @column COLUMN_NAME
     * @var string
     */
    private $name;

    /**
     * @column DATA_TYPE
     * @column_type varchar
     * @var xisrapilx\mysqlo\entity\types\DataType
     */
    private $type;

    /**
     * @column IS_NULLABLE
     * @var string
     */
    private $isNullable;

    /**
     * @return string
     */
    public function getTableName() : string{
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName) : void{
        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) : void{
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIsNullable() : string{
        return $this->isNullable;
    }

    /**
     * @param string $isNullable
     */
    public function setIsNullable(string $isNullable) : void{
        $this->isNullable = $isNullable;
    }

    /**
     * @return xisrapilx\mysqlo\entity\types\DataType
     */
    public function getType() : xisrapilx\mysqlo\entity\types\DataType{
        return $this->type;
    }

    /**
     * @param xisrapilx\mysqlo\entity\types\DataType $type
     */
    public function setType(xisrapilx\mysqlo\entity\types\DataType $type) : void{
        $this->type = $type;
    }
}