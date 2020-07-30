<?php
/** @noinspection SqlDialectInspection */
/** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;
use xisrapilx\mysqlo\credentials\Credentials;
use xisrapilx\mysqlo\entity\types\DataTypes;
use xisrapilx\mysqlo\exception\ConnectionException;
use xisrapilx\mysqlo\exception\QueryException;
use xisrapilx\mysqlo\MySQL;

/** @noinspection PhpIncludeInspection */
require_once dirname(__FILE__, 2)."/vendor/autoload.php";

// Register custom type
DataTypes::add(new DataTypeDataType());

array_shift($argv); //ignore filename
$namespace = array_shift($argv);
$credentials = new Credentials(
    array_shift($argv),
    array_shift($argv),
    array_shift($argv),
    "information_schema",
);

try{
    $mysql = new MySQL($credentials);
    $mysql->connect();

    try{
        /** @var Column[] $result */
        $result = $mysql->prepare("SELECT COLUMNS.IS_NULLABLE, COLUMNS.TABLE_NAME, COLUMNS.COLUMN_NAME, COLUMNS.DATA_TYPE FROM COLUMNS WHERE COLUMNS.TABLE_SCHEMA=':schema' ORDER BY COLUMNS.TABLE_NAME DESC;")
            ->setString("schema", array_shift($argv), false)
            ->execute(Column::class);

        function write(Printer $printer, PhpFile $phpFile, string $filename) : void{
            if(!is_dir(__DIR__."/out"))
                mkdir(__DIR__."/out");

            file_put_contents(__DIR__."/out/".$filename, $printer->printFile($phpFile));
        }

        $printer = new MyPrinter();

        /** @var PhpFile */
        $file = null;
        /** @var ClassType $class */
        $class = null;

        foreach($result as $column){
            if($class !== null && strtolower($class->getName()) !== strtolower($column->getTableName())){
                write($printer, $file, $class->getName().".php");

                $file = null;
                $class = null;
            }

            if($class === null){
                $file = new PhpFile();
                $file->setStrictTypes(true);

                $class = $file->addNamespace($namespace)->addClass(ucfirst($column->getTableName()));
                $class->addComment("Autogenerated!");
                $class->addComment("");
                $class->addComment("@table ".$column->getTableName());
            }

            $type = DataTypes::searchByMySQLDataType($column->getType()->getCurrentMySQLType() ?? "varchar")->getName();
            $property = $class->addProperty(strtolower($column->getName()));
            $property->setVisibility(ClassType::VISIBILITY_PRIVATE);
            $property->addComment("@column ".$column->getName());
            $property->addComment("@column_type ".$column->getType()->getCurrentMySQLType());
            if($column->getIsNullable() === "YES")
                $property->addComment("@nullable");
            $property->addComment("@var ".$type);

            $method = $class->addMethod("get".ucfirst($property->getName()));
            $method->setReturnType($type);
            $method->setBody('return $this->'.$property->getName().";");
            $method->addComment("@result ".$type);

            $method = $class->addMethod("set".ucfirst($property->getName()));
            $parameter = $method->addParameter($property->getName());
            $parameter->setNullable($column->getIsNullable() === "TRUE");
            $parameter->setTypeHint($type);
            $method->setReturnType($type);
            $method->setReturnType("void");
            $method->setBody('$this->'.$property->getName().' = $'.$parameter->getName().";");
            $method->addComment("@param ".$type." $".$parameter->getName());
            $method->addComment("");
            $method->addComment("@return void");
        }

        write($printer, $file, $class->getName().".php");
    }catch(QueryException $exception){
        echo "Query error!".PHP_EOL;
        echo "[".$exception->getCode()."] ".$exception->getMessage();
    }
}catch(ConnectionException $exception){
    echo "Cannot connect to MySQL server!".PHP_EOL;
    echo "[".$exception->getCode()."] ".$exception->getMessage();
}