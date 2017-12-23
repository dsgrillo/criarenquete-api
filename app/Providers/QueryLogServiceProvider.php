<?php

namespace App\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Database\Events\QueryExecuted;
use App\Log\Facades\QueryLog;

class QueryLogServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $events) 
    {
        $events->listen(QueryExecuted::class, function (QueryExecuted $queryExecuted) {
            $sql = $queryExecuted->sql;
            $bindings = $queryExecuted->bindings;
            $time = $queryExecuted->time;
            $configTime = config('database.slow-query-time');
            
            if ($time < $configTime) 
            {
                return;
            }
            
            try 
            {
                foreach ($bindings as $val) 
                {
                    $sql = preg_replace('/\?/', "'{$val}'", $sql, 1);
                }
                
                
                QueryLog::write("{$sql} tooks {$time}ms configtime={$configTime}");
            } catch (Exception $ex) {
                //shhh
            }
        });
    }
    
    public function register()
    {
        $this->app->singleton('query-log', \App\Log\QueryLog::class);
    }
}
