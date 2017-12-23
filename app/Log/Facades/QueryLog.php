<?php

namespace App\Log\Facades;


class QueryLog extends \Illuminate\Support\Facades\Facade {
    protected static function getFacadeAccessor() {
        return 'query-log';
    }
}
