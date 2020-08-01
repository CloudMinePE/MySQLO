<?php /** @noinspection PhpDocSignatureInspection */

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\MySQL;
use xisrapilx\mysqlo\result\ResultSet;

abstract class ExecutableStatement extends Statement implements Executable{

    /** @var MySQL */
    protected $connection;

    public function __construct(MySQL $connection){
        $this->connection = $connection;
    }

    /**
     * @throws QueryException
     * @see Executable::executeUpdate()
     */
    public function executeUpdate() : int{
        return $this->connection->executeUpdate($this->getFinalQuery());
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelect()
     */
    public function executeSelect() : array{
        return $this->connection->executeSelect($this->getFinalQuery());
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectSingle()
     */
    public function executeSelectSingle() : ?ResultSet{
        return $this->connection->executeSelectSingle($this->getFinalQuery());
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectAndMap()
     */
    public function executeSelectAndMap(string $objectToMap, string ...$objectsToMap) : array{
        return $this->connection->executeSelectAndMap($this->getFinalQuery(), $objectToMap, ...$objectsToMap);
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectAndMapSingle()
     */
    public function executeSelectAndMapSingle(string $objectToMap, string ...$objectsToMap){
        return $this->connection->executeSelectAndMapSingle($this->getFinalQuery(), $objectToMap, ...$objectsToMap);
    }
}