<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo;

use xisrapilx\mysqlo\result\ResultSet;

/** Can execute sql */
interface Executable{

    /**
     * Execute update(CREATE, UPDATE, DELETE and etc...) query
     *
     * @param string $query
     *
     * @return int
     */
    public function executeUpdate(string $query) : int;

    /**
     * @param string $query
     *
     * @return ResultSet[]
     */
    public function executeSelect(string $query) : array;

    /**
     * @param string $query
     *
     * @return ResultSet|null
     */
    public function executeSelectSingle(string $query) : ?ResultSet;

    /**
     * @param string $query
     * @param string $objectToMap
     * @param string[] $objectsToMap
     *
     * @return object[]|object[][]
     */
    public function executeSelectAndMap(string $query, string $objectToMap, string ...$objectsToMap) : array;

    /**
     * @param string $query
     * @param string $objectToMap
     * @param string ...$objectsToMap
     *
     * @return object|object[]|null
     */
    public function executeSelectAndMapSingle(string $query, string $objectToMap, string ...$objectsToMap);
}