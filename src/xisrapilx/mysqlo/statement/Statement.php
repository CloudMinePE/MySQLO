<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

abstract class Statement{

    /**
     * Return a final, formatted SQL query string
     *
     * @return string
     */
    public abstract function getFinalQuery() : string;
}