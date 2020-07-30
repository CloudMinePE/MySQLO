<?php /** @noinspection PhpDocSignatureInspection */

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

class MySQL implements Executable{

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
     * @throws QueryException
     * @see Executable::executeUpdate()
     *
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
     * @see Executable::executeSelect()
     *
     * @throws QueryException
     */
    public function executeSelect(string $query) : array{
        if($this->mysqli !== null){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $results = [];
                while(($data = $result->fetch_assoc()) !== null){
                    $results[] = new ResultSet($data);
                }

                $result->free();
                return $results;
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
     * @throws QueryException
     * @see Executable::executeSelectSingle()
     *
     */
    public function executeSelectSingle(string $query) : ?ResultSet{
        if($this->mysqli !== null){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $data = $result->fetch_assoc();
                $result->free();
                if($data !== null)
                    return new ResultSet($data);

                return null;
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
     * @throws QueryException
     * @see Executable::executeSelectAndMap()
     *
     */
    public function executeSelectAndMap(string $query, string $objectToMap, string ...$objectsToMap) : array{
        if($this->isConnected()){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $resultData = [];

                while(($data = $result->fetch_assoc())){
                    if(!empty($objectsToMap)){
                        $resultObjects = [];
                        $resultObjects[] = new $objectToMap;
                        $this->entityManager->map($resultObjects[0], $data);
                        foreach($objectsToMap as $object){
                            $object = new $object;
                            $this->entityManager->map($object, $data);

                            $resultObjects[] = $object;
                        }

                        $resultData[] = $resultObjects;
                    }else{
                        $object = new $objectToMap;
                        $this->entityManager->map($object, $data);

                        $resultData[] = $object;
                    }
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
     * @throws QueryException
     * @see Executable::executeSelectAndMapSingle()
     *
     */
    public function executeSelectAndMapSingle(string $query, string $objectToMap, string ...$objectsToMap){
        if($this->isConnected()){
            $result = $this->mysqli->query($query);
            if(!$this->mysqli->errno){
                $data = $result->fetch_assoc();
                $result->free();

                if(!empty($objectsToMap)){
                    $resultObjects = [];
                    $resultObjects[] = new $objectToMap;
                    $this->entityManager->map($resultObjects[0], $data);
                    foreach($objectsToMap as $object){
                        $object = new $object;
                        $this->entityManager->map($object, $data);

                        $resultObjects[] = $object;
                    }

                    return $resultObjects;
                }else{
                    $object = new $objectToMap;
                    $this->entityManager->map($object, $data);

                    return $object;
                }
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