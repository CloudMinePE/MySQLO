<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity;

use xisrapilx\mysqlo\entity\types\DataType;

class EntityColumn{

    /** @var string */
    private $name;

    /** @var DataType */
    private $type;

    /** @var bool */
    private $nullable;

    /** @var DataConverter|null */
    private $dataConverter;

    /**
     * EntityColumn constructor.
     * @param string $name
     * @param DataType $type
     * @param bool $nullable
     * @param DataConverter|null $dataConverter
     */
    public function __construct(string $name, DataType $type, bool $nullable, DataConverter $dataConverter = null){
        $this->name = $name;
        $this->type = $type;
        $this->nullable = $nullable;
        $this->dataConverter = $dataConverter;
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * @return DataType
     */
    public function getType() : DataType{
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNullable() : bool{
        return $this->nullable;
    }

    /**
     * @return DataConverter|null
     */
    public function getDataConverter() : ?DataConverter{
        return $this->dataConverter;
    }
}