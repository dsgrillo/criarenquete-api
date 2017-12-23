<?php

namespace App\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class BaseLog {
     /**
     *
     * @var Logger
     */
    protected $logger;
    
    public function __construct($name) 
    {
        $this->logger = new Logger($name);
        $handler = new \Monolog\Handler\StreamHandler(storage_path("logs/{$name}.log"), \Monolog\Logger::DEBUG);
        $formatter = new \Monolog\Formatter\LineFormatter();
        $formatter->includeStacktraces(true);
        $handler->setFormatter($formatter);
        $this->logger->pushHandler($handler);
    }
    
    public function write($message, $params = []) 
    {
        $this->logger->addInfo($message, $params);
    }
}
