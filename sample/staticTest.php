<?php

require_once __DIR__.'/../bootstrap.php';

use Logger\StaticLogger;

$loglevel = 'debug';
$filename = __DIR__.'/staticTest.log';

StaticLogger::config($loglevel, $filename);

StaticLogger::debug('debug message');
StaticLogger::info('info message');
StaticLogger::warn('warn message');
StaticLogger::error('error message');
StaticLogger::fatal('fatal message');
