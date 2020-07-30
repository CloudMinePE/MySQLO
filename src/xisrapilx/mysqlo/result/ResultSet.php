<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\result;

class ResultSet{

    private $data;

    /**
     * ResultSet constructor.
     *
     * @param array $data
     */
    public function __construct(array $data){
        $this->data = $data;
    }

    /**
     * @param string $name
     * @param string|null $default
     *
     * @return string|null
     */
    public function getString(string $name, ?string $default = null) : ?string{
        return isset($this->data[$name]) ? strval($this->data[$name]) : $default;
    }

    /**
     * @param string $name
     * @param int|null $default
     *
     * @return int|null
     */
    public function getInt(string $name, ?int $default = null) : ?int{
        return isset($this->data[$name]) ? intval($this->data[$name]) : $default;
    }

    /**
     * @param string $name
     * @param float|null $default
     *
     * @return float|null
     */
    public function getFloat(string $name, ?float $default = null) : ?float{
        return isset($this->data[$name]) ? floatval($this->data[$name]) : $default;
    }

    /**
     * @param string $name
     * @param bool|null $default
     *
     * @return bool|null
     */
    public function getBoolean(string $name, ?bool $default = null) : ?bool{
        return isset($this->data[$name]) ? boolval($this->data[$name]) : $default;
    }

    /**
     * @param string $name
     * @param string|null $expected
     * @param object|null $default
     *
     * @return object|null
     */
    public function getObject(string $name, string $expected = null, ?object $default = null) : ?object{
        return isset($this->data[$name]) && ($expected !== null && $this->data[$name] instanceof $expected) ? $this->data[$name] : $default;
    }
}