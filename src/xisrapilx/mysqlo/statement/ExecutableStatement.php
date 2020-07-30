<?php /** @noinspection PhpDocSignatureInspection */

declare(strict_types=1);

namespace xisrapilx\mysqlo\statement;

use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\Executable;
use xisrapilx\mysqlo\result\ResultSet;

abstract class ExecutableStatement extends Statement implements Executable{

    /**
     * @throws QueryException
     * @see Executable::executeUpdate()
     *
     */
    public function executeUpdate(string $query) : int{
        return $this->connection->executeUpdate($query);
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelect()
     *
     */
    public function executeSelect(string $query) : array{
        return $this->connection->executeSelect($query);
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectSingle()
     *
     */
    public function executeSelectSingle(string $query) : ?ResultSet{
        return $this->connection->executeSelectSingle($query);
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectAndMap()
     *
     */
    public function executeSelectAndMap(string $query, string $objectToMap, string ...$objectsToMap) : array{
        return $this->connection->executeSelectAndMap($query, $objectToMap, ...$objectsToMap);
    }

    /**
     * @throws QueryException
     * @see Executable::executeSelectAndMapSingle()
     *
     */
    public function executeSelectAndMapSingle(string $query, string $objectToMap, string ...$objectsToMap){
        return $this->connection->executeSelectAndMapSingle($query, $objectToMap, ...$objectsToMap);
    }
}