<?php namespace Dpay\Services;
// Pauldro Minincli
use Pauldro\Minicli\v2\Logging\Logger as ParentLogger;
// Lib
use Dpay\Services\Logger\LogFile;

class Logger extends ParentLogger {
    /**
     * @param  string $message
     * @param  array<mixed> $context
     * @return void
     */
    public function request(string $message, array $context = []) : void
    {
        $this->log($message, $context, LogFile::REQUEST);
    }

    /**
     * @param  string $message
     * @param  array<mixed> $context
     * @return void
     */
    public function response(string $message, array $context = []) : void
    {
        $this->log($message, $context, LogFile::RESPONSE);
    }

    /**
     * @param  string $message
     * @param  array<mixed> $context
     * @return void
     */
    public function debug(string $message, array $context = []) : void
    {
        $this->log($message, $context, LogFile::RESPONSE);
    }
}