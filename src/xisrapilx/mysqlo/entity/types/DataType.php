<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity\types;

abstract class DataType{

    /**
     * @var string|null
     */
    private $currentMySQLType = null;

    /**
     * Return php type name
     *
     * @return string
     */
    public abstract function getName() : string;

    /**
     * Return mysql types
     *
     * @return array
     */
    public abstract function getMySQLTypes() : array;

    /**
     * Check if data type valid
     *
     * @param $var
     * @return bool
     */
    public abstract function checkType($var) : bool;

    /**
     * @param $var
     * @return mixed
     */
    public abstract function cast($var);

    /**
     * @return string|null
     */
    public function getCurrentMySQLType() : ?string{
        return $this->currentMySQLType;
    }

    /**
     * @param string|null $currentMySQLType
     */
    public function setCurrentMySQLType(?string $currentMySQLType) : void{
        $this->currentMySQLType = $currentMySQLType;
    }
}