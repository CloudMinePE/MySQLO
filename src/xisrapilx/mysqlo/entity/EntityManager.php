<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity;

use xisrapilx\mysqlo\exception\EntityException;
use xisrapilx\mysqlo\exception\EntityMappingException;

/**
 * Entity schemas store
 */
class EntityManager{

    /** @var Entity[] */
    private $entities;

    /**
     * EntityManager constructor.
     *
     * @param array $entities
     */
    public function __construct($entities = []){
        $this->entities = $entities;
    }

    /**
     * Remove entity from entities list
     *
     * @param string $className
     *
     * @return bool
     */
    public function remove(string $className) : bool{
        if(isset($this->entities[$className])){
            unset($this->entities[$className]);

            return true;
        }

        return false;
    }

    /**
     * @param object $object
     * @param array $data
     * @throws EntityMappingException
     * @throws EntityException
     */
    public function map(object $object, array $data) : void{
        EntityMapper::map($object, $this->get(get_class($object), true), $data);
    }

    /**
     * @param string $className
     * @param bool $create Create if not exists
     *
     * @return Entity|null
     * @throws EntityException
     */
    public function get(string $className, bool $create = true) : ?Entity{
        if(isset($this->entities[$className])){
            return $this->entities[$className];
        }elseif($create){
            return $this->createAndAdd($className);
        }

        return null;
    }

    /**
     * Create entity scheme and add to entities list
     *
     * @param string $className
     * @param bool $autoInit
     *
     * @return Entity
     * @throws EntityException
     */
    public function createAndAdd(string $className, bool $autoInit = true) : Entity{
        $entity = new Entity($className, $autoInit);
        $this->entities[$className] = $entity;

        return $entity;
    }
}