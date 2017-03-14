<?php

class Logger {
    const ALL   = 9999;
    const DEBUG = 600;
    const TRACE = 500;
    const INFO  = 400;
    const WARN  = 300;
    const ERROR = 200;
    const FATAL = 100;
    const OFF   = -1;

    private static $level_lsit = array('ALL', 'DEBUG', 'TRACE', 'INFO', 'WARN', 'ERROR', 'FATAL', 'OFF');
    private static $level  = "ALL";    // ログレベル
    private static $append = true;     // 追記モード

    // ログフォルダ、ログファイル名
    private static $filepath;

    // ファイルハンドラ
    private static $fp;

    /******************************
        ログファイル出力先とログレベルの設定
    ******************************/
    public static function setting($filepath, $level){
        self::$filepath = $filepath;
        self::$level = in_array(ucwords($level), self::$level_lsit) ? 'ALL' : ucwords($level);
    }

    /******************************
        ログファイルを開く
    ******************************/
    private static function openFile(){
        if (!is_file(self::$filepath)) {
            $dir = dirname(self::$filepath);
            if (!is_dir($dir)) {
                $success = mkdir($dir, 0777, true);
                if ($succcess === false) {
                    self::sendWarn("Failed creating target directory [$dir].");
                    return false;
                }
            }
        }

        $mode = self::$append ? 'a' : 'w';
        self::$fp = fopen(self::$filepath, $mode);
        if (self::$fp === false) {
            self::sendWarn("Failed to open a log file.");
            self::$fp = null;
            return false;
        }
        fseek(self::$fp, 0, SEEK_END);
    }

    /******************************
        書き込み
    ******************************/
    protected static function write($str){
        if (!isset(self::$fp)) {
            if(self::openFile() === false) return;
        }
        if (fwrite(self::$fp, $str) === false) {
            self::sendWarn("Failed to write. ");
        };
    }

    /******************************
        ログ出力
    ******************************/
    public static function fatal($str){ if(self::getLevel() > self::FATAL) self::write(self::logformat("FATAL", $str)); }
    public static function error($str){ if(self::getLevel() > self::ERROR) self::write(self::logformat("ERROR", $str)); }
    public static function warn($str) { if(self::getLevel() > self::WARN ) self::write(self::logformat("WARN ", $str)); }
    public static function info($str) { if(self::getLevel() > self::INFO ) self::write(self::logformat("INFO ", $str)); }
    public static function trace($str){ if(self::getLevel() > self::TRACE) self::write(self::logformat("TRACE", $str)); }
    public static function debug($str){ if(self::getLevel() > self::DEBUG) self::write(self::logformat("DEBUG", $str)); }

    /******************************
        出力レベル設定値取得
    ******************************/
    protected static function getLevel() {
        switch (strtoupper(self::$level)) {
            case "ALL"  : return self::ALL;
            case "DEBUG": return self::DEBUG;
            case "TRACE": return self::TRACE;
            case "INFO" : return self::INFO;
            case "WARN" : return self::WARN;
            case "ERROR": return self::ERROR;
            case "FATAL": return self::FATAL;
            case "OFF"  : return self::OFF;
            default: return self::ALL;
        }
    }
    /******************************
        WARNINGを飛ばす
    ******************************/
    protected static function sendWarn($message) {
        throw new LoggerException2("LogError: $message");
    }

    /******************************
        ログフォーマット
    ******************************/
    public static function logformat($level, $str) {
        $str = rtrim($str, "\n");
        $logstr = sprintf("%s [%s] %s\n", date("Y-m-d H:i:s"), $level, $str);
        return $logstr;
    }
}

class LoggerException extends Exception{
}

Logger::setting('path/to/logfile.log', 'TRACE');
Logger::trace("warning");
Logger::debug("warning");
Logger::info("warning");
Logger::warn("warning");
Logger::errror("warning");
Logger::fatal("warning");
