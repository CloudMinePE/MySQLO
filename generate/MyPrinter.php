<?php

declare(strict_types=1);

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class MyPrinter extends PsrPrinter{

    public function printClass(ClassType $class, PhpNamespace $namespace = null) : string{
        return str_replace("\n{", "{\n", parent::printClass($class, $namespace));
    }

    public function printMethod(Method $method, PhpNamespace $namespace = null) : string{
        return str_replace("\n{", "{", parent::printMethod($method, $namespace));
    }

    protected function printReturnType($function, ?PhpNamespace $namespace) : string{
        return " ".parent::printReturnType($function, $namespace);
    }
}