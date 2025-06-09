<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * LoggerFactory Class
 *
 * @package App\Infrastructure
 */
class LoggerFactory
{
    /**
     * Creata a logger instance
     *
     * @param string $channel
     */
    public static function create(string $channel = 'app'): Logger
    {
        $logFileName = !empty($_ENV["LOG_FILE_NAME"]) ? $_ENV["LOG_FILE_NAME"] : "application.log";
        $logLevel = !empty($_ENV["LOG_LEVEL"]) ? $_ENV["LOG_LEVEL"] : "debug";
        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../Storage/Log/" . $logFileName, $logLevel));

        return $logger;
    }
}
