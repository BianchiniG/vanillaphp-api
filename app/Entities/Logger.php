<?php


class Logger {
    const ERRORFILENAME = 'error.log';
    const INFOFILENAME = 'info.log';
    const DEBUGFILENAME = 'debug.log';

    public static function write($level, $line) {
        $file = Logger::getLogFileResource($level);

        if ($file) {
            Logger::writeFile($file, $line);
            fclose($file);
        } else {
            throw new Exception("The logger file could not be opened.");
        }
    }

    private static function writeFile($file, $line) {
        fwrite($file, Logger::formatLine($line));
    }

    private static function formatLine($line) {
        return "[".date("Y-m-d h:m:s", strtotime("now"))."] - ".$line."\n";
    }

    private static function getLogfileResource($level) {
        switch ($level) {
            case 'error':
                $file = dirname(__FILE__, 2).'/logs/'.Logger::ERRORFILENAME;
                break;
            case 'debug':
                $file = dirname(__FILE__, 2).'/logs/'.Logger::DEBUGFILENAME;
                break;
            case 'info':
                $file = dirname(__FILE__, 2).'/logs/'.Logger::INFOFILENAME;
                break;
            default:
                break;
        }

        if ($file) {
            if (file_exists($file)) {
                $fd = fopen($file, 'a');
            } else {
                $fd = fopen($file, 'w');
            }
    
            return $fd;
        } else {
            throw new Exception("Error level must be specified (error, info, debug).");
        }
    }
}