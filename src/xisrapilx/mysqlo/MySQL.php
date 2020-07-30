<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo;

use Exception;
use mysqli;
use mysqli_result;
use xisrapilx\mysqlo\credentials\Credentials;
use xisrapilx\mysqlo\entity\EntityManager;
use xisrapilx\mysqlo\exception\ConnectionException;
use xisrapilx\mysqlo\exception\MySQLOException;
use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\result\ResultSet;
use xisrapilx\mysqlo\statement\NamedPreparedStatement;

class MySQL{

    /** @var mysqli */
    private $mysqli;

    /** @var Credentials */
    private $credentials;

    /** @var ?MySQLOException */
    private $lastException;

    /** @var EntityManager */
    private $entityManager;

    public function __construct(Credentials $credentials){
        $this->credentials = $credentials;
        $this->entityManager = new EntityManager();
    }

    /**
     * Initialize connection using \mysqli class
     *
     * @throws ConnectionException
     */
    public function connect(){
        try{
            $this->mysqli = new mysqli(
                $this->credentials->getHostname(),
                $this->credentials->getUsername(),
                $this->credentials->getPassword(),
                $this->credentials->getSchema()
            );

            if($this->mysqli->connect_errno){
                $this->lastException = new ConnectionException($this->mysqli->connect_error, $this->mysqli->connect_errno);
                throw $this->lastException;
            }
        }catch(Exception $exception){
            $this->lastException = new ConnectionException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
            throw $this->lastException;
        }
    }

    /**
     * @param string $query
     *
     * @return NamedPreparedStatement
     */
    public function prepare(string $query) : NamedPreparedStatement{
        return new NamedPreparedStatement($query, $this);
    }

    /**
     * Execute update queries(INSERT, UPDATE, DELETE, CREATE, etc...)
     *
     * @param string $query
     *
     * @return int
     * @throws QueryException
     */
    public function executeUpdate(string $query) : int{
        if($this->mysqli !== null){
            $result = $this->mysqli->query($query);
            if($this->mysqli->errno){
                throw new QueryException(
                    $this->mysqli->error,
                    $this->mysqli->errno
                );
            }

            if($result instanceof mysqli_result)
                $result->free();

            return $this->mysqli->affected_rows;
        }else{
            throw new QueryException("No connection!");
        }
    }

    /**
     * @param string $query
     * @param string $objectToMap
     * @param string ...$objectsToMap
     *
     * @return ResultSet[]|object[]|object[][]
     * @throws QueryException
     */
    public function execute(string $query, ?string $objectToMap = null, string ...$objectsToMap) : array{
        if($this->mysqli !== null){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $resultSet = [];
                if($objectToMap !== null){
                    if(!empty($objectsToMap)){
                        while(($data = $result->fetch_assoc()) !== null){
                            $mappedObjects = [];

                            $obj = new $objectToMap;
                            $mappedObjects[] = $obj;

                            $this->entityManager->map($obj, $data);
                            foreach($objectsToMap as $obj){
                                $obj = new $obj;

                                $mappedObjects[] = $obj;
                                $this->entityManager->map($obj, $data);
                            }

                            $resultSet[] = $mappedObjects;
                        }
                    }else{
                        while(($data = $result->fetch_assoc()) !== null){
                            $obj = new $objectToMap;
                            $resultSet[] = $obj;

                            $this->entityManager->map($obj, $data);
                        }
                    }
                }else{
                    $resultSet = new ResultSet($result->fetch_assoc());
                }

                $result->free();
                return $resultSet;
            }else{
                throw new QueryException(
                    $this->mysqli->error,
                    $this->mysqli->errno
                );
            }
        }else{
            throw new QueryException("No connection!");
        }
    }

    /**
     * @param string $query
     * @param string $objectToMap
     * @param string ...$objectsToMap
     *
     * @return ResultSet|object|object[]
     * @throws QueryException
     */
    public function executeSingle(string $query, string $objectToMap = null, string ...$objectsToMap){
        if($this->isConnected()){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $resultData = null;
                if($objectToMap !== null){
                    if(!empty($objectsToMap)){
                        $obj = new $objectToMap;
                        $mappedObjects[] = $obj;

                        $data = $result->fetch_assoc();
                        $this->entityManager->map($obj, $data);
                        foreach($objectsToMap as $obj){
                            $obj = new $obj;

                            $mappedObjects[] = $obj;
                            $this->entityManager->map($obj, $data);
                        }

                        $resultData = $mappedObjects;
                    }else{
                        $resultData = new $objectToMap;
                        $this->entityManager->map($resultData, $result->fetch_assoc());
                    }
                }else{
                    $resultData = new ResultSet($result->fetch_assoc());
                }

                $result->free();
                return $resultData;
            }else{
                throw new QueryException(
                    $this->mysqli->error,
                    $this->mysqli->errno
                );
            }
        }else{
            throw new QueryException("No connection!");
        }
    }

    /**
     * @return bool
     */
    public function isConnected() : bool{
        return $this->mysqli !== null;
    }

    public function escapeString(string $value) : string{
        return $this->mysqli->real_escape_string($value);
    }

    /**
     * @return mysqli
     */
    public function getDriverClass() : mysqli{
        return $this->mysqli;
    }

    /**
     * @return Credentials
     */
    public function getCredentials() : Credentials{
        return $this->credentials;
    }

    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials) : void{
        $this->credentials = $credentials;
    }

    /**
     * @return MySQLOException|null
     */
    public function getLastException() : ?MySQLOException{
        return $this->lastException;
    }

    /**
     * @param mixed $lastException
     */
    public function setLastException($lastException) : void{
        $this->lastException = $lastException;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() : EntityManager{
        return $this->entityManager;
    }
}