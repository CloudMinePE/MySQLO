<?php

declare(strict_types=1);

namespace xisrapilx\mysqlo\entity;

interface DataConverter{

    /**
     * Convert data
     *
     * @param $data
     *
     * @return mixed
     */
    public function convert($data);
}