<?php

namespace App\Strategies;

use App\Services\AbstractService;

interface AbstractStrategy
{
    public function run(string $action): AbstractService;
}