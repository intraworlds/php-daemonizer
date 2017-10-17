<?php
namespace IW;

require __DIR__ . '/../vendor/autoload.php';

Daemonizer::daemonize('printf', ['stdout' => 'dots', 'stderr' => 'dots']);

while (true) {
    echo '.';
    error_log('.');
    sleep(1);
}
