<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\result\ResultSet;

/** Can execute sql */
interface Executable{

    /**
     * Execute update(CREATE, UPDATE, DELETE and etc...) query
     *
     * @return int
     */
    public function executeUpdate() : int;

    /**
     * @return ResultSet[]
     */
    public function executeSelect() : array;

    /**
     * @return ResultSet|null
     */
    public function executeSelectSingle() : ?ResultSet;

    /**
     * @param string $objectToMap
     * @param string[] $objectsToMap
     *
     * @return object[]|object[][]
     */
    public function executeSelectAndMap(string $objectToMap, string ...$objectsToMap) : array;

    /**
     * @param string $objectToMap
     * @param string ...$objectsToMap
     *
     * @return object|object[]|null
     */
    public function executeSelectAndMapSingle(string $objectToMap, string ...$objectsToMap);
}