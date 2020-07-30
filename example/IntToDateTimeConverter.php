<?php
declare(strict_types=1);

use xisrapilx\mysqlo\entity\DataConverter;

class IntToDateTimeConverter implements DataConverter{
    public function convert($data){
        return date("Y-m-d H:i:s", intval($data));
    }
}