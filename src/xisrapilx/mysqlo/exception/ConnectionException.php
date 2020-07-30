<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\exception;

class ConnectionException extends MySQLOException{

    private $stackTraceAsString = "";

    /**
     * @return mixed
     */
    public function getStackTraceAsString(){
        if($this->getTraceAsString() == "")
            return $this->stackTraceAsString;

        return $this->getTraceAsString();
    }

    /**
     * @param mixed $stackTraceAsString
     */
    public function setStackTraceAsString($stackTraceAsString) : self{
        $this->stackTraceAsString = $stackTraceAsString;

        return $this;
    }
}