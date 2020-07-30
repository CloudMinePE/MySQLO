<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\MySQL;
use xisrapilx\mysqlo\result\ResultSet;

abstract class Statement{

    /** @var MySQL */
    protected $connection;

    public function __construct(MySQL $connection){
        $this->connection = $connection;
    }

    /**
     * Execute update queries(INSERT, UPDATE, DELETE, CREATE, etc...)
     *
     * @return void
     * @throws QueryException
     */
    public function executeUpdate() : void{
        $this->connection->executeUpdate($this->getFinalQuery());
    }

    /**
     * Return a final, formatted SQL query string
     *
     * @return string
     */
    public abstract function getFinalQuery() : string;

    /**
     * @param string ...$objectsToMap
     *
     * @return ResultSet[]|object[]|ResultSet[][]|object[][]
     * @throws QueryException
     */
    public function execute(string ...$objectsToMap) : array{
        return $this->connection->execute($this->getFinalQuery(), ...$objectsToMap);
    }

    /**
     * @param string ...$objectsToMap
     *
     * @return ResultSet|object|object[]
     * @throws QueryException
     */
    public function executeSingle(string ...$objectsToMap){
        return $this->connection->executeSingle($this->getFinalQuery(), ...$objectsToMap);
    }
}