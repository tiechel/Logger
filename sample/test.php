<?php

require_once __DIR__.'/../bootstrap.php';

use Logger\Logger;

$logger = new Logger('FATAL', __DIR__.'/test.log');

$logger->debug('debug message');
$logger->info('info message');
$logger->warn('warn message');
$logger->error('error message');
$logger->fatal('fatal message');
