<?php
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */

declare(strict_types=1);

use xisrapilx\mysqlo\credentials\Credentials;
use xisrapilx\mysqlo\exception\ConnectionException;
use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\MySQL;

/** @noinspection PhpIncludeInspection */
require_once dirname(__FILE__, 2)."/vendor/autoload.php";

$mysql = new MySQL(new Credentials(
    "127.0.0.1",
    "root",
    "root",
    "test"
));
try{
    $mysql->connect();

    // Инициализируем сущности до запроса
    try{
        $mysql->getEntityManager()->createAndAdd(User::class);
        $mysql->getEntityManager()->createAndAdd(Region::class);
    }catch(Exception $exception){
        echo "Cannot auto init entities!!!".PHP_EOL;
        echo $exception->getMessage().PHP_EOL;
    }

    try{
        /** @var User[]|Region[] $result */
        $result = $mysql->prepare("SELECT * FROM `stats` LEFT JOIN `regions` ON regions.owner=stats.user LIMIT :limit")
            ->setInt("limit", 1)
            ->executeSingle(User::class, Region::class);

        var_dump($result[0]->getUser(), $result[1]->getOwner());
    }catch(QueryException $exception){
        echo "Query error!!!".PHP_EOL;
        echo "[".$exception->getCode()."] ".$exception->getMessage();
    }
}catch(ConnectionException $exception){
    echo "Connection error!!!".PHP_EOL;
    echo "[".$exception->getCode()."] ".$exception->getMessage().PHP_EOL;
}