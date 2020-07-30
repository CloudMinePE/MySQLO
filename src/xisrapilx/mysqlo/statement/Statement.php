<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\MySQL;

abstract class Statement{

    /** @var MySQL */
    protected $connection;

    public function __construct(MySQL $connection){
        $this->connection = $connection;
    }

    /**
     * Return a final, formatted SQL query string
     *
     * @return string
     */
    public abstract function getFinalQuery() : string;
}