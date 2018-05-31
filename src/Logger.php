<?php

namespace Logger;

class Logger {
    /**
     * ログレベル定数
     */
    const OFF     = 0;
    const ALL     = 1024;

    const FATAL   = 1;
    const ERROR   = 3;
    const WARN    = 7;
    const INFO    = 15;
    const DEBUG   = 31;

    /**
     * ログ出力レベル
     */
    private $priority;

    /**
     * ログ出力ファイル
     */
    private $filename;

    /**
     * コンストラクタ
     * @param string $priority ログ出力レベル
     * @param string $filename ログファイル名
     */
    public function __construct(string $priority, string $filename)
    {
        if (!defined('self::'.strtoupper($priority))) {
            trigger_error('Invalid log level. Available levels are ALL, DEBUG, INFO, WARN, ERROR, FATAL and OFF.', E_USER_ERROR);
        }

        $this->priority = $priority;
        $this->filename = $filename;
    }

    /**
     * ログ出力レベルを数値化
     * @return int
     */
    private function priority(): int
    {
        return constant('self::'.strtoupper($this->priority));
    }

    /**
     * fatalログを出力
     */
    public function fatal($message): bool
    {
        if ($this->priority() >= self::FATAL) {
            return $this->log(__FUNCTION__, $message);
        }
        return false;
    }

    /**
     * errorログを出力
     */
    public function error($message): bool
    {
        if ($this->priority() >= self::ERROR) {
            return $this->log(__FUNCTION__, $message);
        }
        return false;
    }

    /**
     * warnログを出力
     */
    public function warn($message): bool
    {
        if ($this->priority() >= self::WARN) {
            return $this->log(__FUNCTION__, $message);
        }
        return false;
    }

    /**
     * infoログを出力
     */
    public function info($message): bool
    {
        if ($this->priority() >= self::INFO) {
            return $this->log(__FUNCTION__, $message);
        }
        return false;
    }

    /**
     * debugログを出力
     */
    public function debug($message): bool
    {
        if ($this->priority() >= self::DEBUG) {
            return $this->log(__FUNCTION__, $message);
        }
        return false;
    }

    /**
     * ログを出力
     * @param string $level
     * @param mixed  $message
     */
    public function log(string $level, $message): bool
    {
        return error_log($this->format($level, $message), 3, $this->filename);
    }

    /**
     * ログの書式に変換した文字列を返す
     * @param string $level
     * @param mixed  $message
     */
    public function format(string $level, $message): string
    {
        return sprintf("%s [%s] %s", date('Y-m-d H:i:s'), strtoupper($level), $this->string($message)).PHP_EOL;
    }

    /**
     * 入力変数を文字列にして返す
     * @param  mixed    $value
     * @return string
     */
    public function string($value): string
    {
        if (is_string($value)) {
            return rtrim($value);
        }

        if (is_array($value)) {
            return var_export($value, true);
        }

        if (is_object($value)) {
            $rc = new ReflectionClass(get_class($value));
            if ($rc->hasMethod('__toString')) {
                return (string)$value;
            }
            return var_export($value, true);
        }

        return (string)$value;
    }
}
