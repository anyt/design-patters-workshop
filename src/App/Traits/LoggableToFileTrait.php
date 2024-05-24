<?php

namespace App\Traits;

trait LoggableToFileTrait
{
    const LOG_FILE_PATH = '/../../../var/log/my_logs.log';

    public function log(string $messsage, array $context = [])
    {
        $logMessage = sprintf('[%s] %s %s', date('Y-m-d H:i:s'), $messsage, json_encode($context));
        file_put_contents(__DIR__ . self::LOG_FILE_PATH, $logMessage . PHP_EOL, FILE_APPEND);
    }
}
