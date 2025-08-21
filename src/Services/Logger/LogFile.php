<?php namespace Dpay\Services\Logger;
// Pauldro Minicli
use Pauldro\Minicli\v2\Logging\LogFileInterface;

enum LogFile : string implements LogFileInterface 
{
    case REQUEST = 'request';
    case RESPONSE = 'response';
    case DEBUG    = 'debug';
}