<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\MySQL;

final class NamedPreparedStatement extends Statement{

    /** @var string */
    private $query;

    private $params = [];

    /**
     * NamedPreparedStatement constructor.
     *
     * @param string $query
     * @param MySQL $connection
     */
    public function __construct(string $query, MySQL $connection){
        parent::__construct($connection);

        $this->query = $query;
    }

    /**
     * @param string $name
     * @param string $value
     * @param bool $escape
     *
     * @return NamedPreparedStatement
     */
    public function setString(string $name, string $value, bool $escape = true) : self{
        $this->params[":".$name] = $escape ? $this->connection->escapeString($value) : $value;

        return $this;
    }

    /**
     * @param string $name
     * @param int $value
     *
     * @return NamedPreparedStatement
     */
    public function setInt(string $name, int $value) : self{
        $this->params[":".$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param float $value
     *
     * @return NamedPreparedStatement
     */
    public function setFloat(string $name, float $value) : self{
        $this->params[":".$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param bool $value
     *
     * @return NamedPreparedStatement
     */
    public function setBoolean(string $name, bool $value) : self{
        $this->params[":".$name] = "b'".($value ? 1 : 0)."'";

        return $this;
    }

    /**
     * @return string
     */
    public function getFinalQuery() : string{
        return str_replace(array_keys($this->params), array_values($this->params), $this->query);
    }
}