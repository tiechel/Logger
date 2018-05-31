<?php

require_once __DIR__.'/../bootstrap.php';

use Logger\Logger;

$loglevel = 'FATAL';
$filename = __DIR__.'/test.log';

$logger = new Logger($loglevel, $filename);

$logger->debug('debug message');
$logger->info('info message');
$logger->warn('warn message');
$logger->error('error message');
$logger->fatal('fatal message');
