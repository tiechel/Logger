<?php

namespace Logger;

class StaticLogger
{
    static private $logger;

    static public function config(string $priority, string $filename)
    {
        self::$logger = new Logger($priority, $filename);
    }

    static public function __callStatic($method, $args)
    {
        if (is_null(self::$logger)) {
            trigger_error("Set log config via 'StaticLogger::config()' before call log functions.", E_USER_WARNING);
        }

        if (method_exists(self::$logger, $method)) {
            call_user_func_array([self::$logger, $method], $args);
        }
    }
}
