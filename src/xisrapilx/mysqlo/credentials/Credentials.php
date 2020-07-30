<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\credentials;

class Credentials{

    /** @var string */
    private $hostname;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $schema;

    /** @var int */
    private $port;

    /** @var string|null */
    private $socket;

    /**
     * Credentials constructor. @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $schema
     * @param int|null $port
     * @param string|null $socket
     * @see \mysqli::__construct
     *
     */
    public function __construct(string $hostname, string $username, string $password, string $schema, ?int $port = null, string $socket = null){
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->schema = $schema;
        $this->port = $port;
        $this->socket = $socket;
    }

    /**
     * @return string
     */
    public function getHostname() : string{
        return $this->hostname;
    }

    /**
     * @param string $hostname
     *
     * @return Credentials
     */
    public function setHostname(string $hostname) : self{
        return new self($hostname, $this->username, $this->password, $this->schema, $this->port);
    }

    /**
     * @return string
     */
    public function getUsername() : string{
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return Credentials
     */
    public function setUsername(string $username) : self{
        return new self($this->hostname, $username, $this->password, $this->schema, $this->port);
    }

    /**
     * @return string
     */
    public function getPassword() : string{
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password) : self{
        return new self($this->hostname, $this->username, $password, $this->schema, $this->port);
    }

    /**
     * @return string
     */
    public function getSchema() : ?string{
        return $this->schema;
    }

    /**
     * @param string $schema
     *
     * @return $this
     */
    public function setSchema(string $schema) : self{
        return new self($this->hostname, $this->username, $this->password, $schema, $this->port);
    }

    /**
     * @return int
     */
    public function getPort() : ?int{
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return Credentials
     */
    public function setPort(int $port) : self{
        return new self($this->hostname, $this->username, $this->password, $this->schema, $port);
    }
}