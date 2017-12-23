<?php

namespace App\Log;

class QueryLog extends BaseLog
{
    public function __construct() 
    {
        parent::__construct('query-log');
    }
}
