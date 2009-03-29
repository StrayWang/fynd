<?php
require_once 'Fynd/Object.php';
/**
 * 提供访问标准输入输出的方法
 *
 */
final class Fynd_Console extends Fynd_Object
{
    private function __construct()
    {}
    /**
     * 从标准输入读入一行
     *
     * @return string
     */
    public static function readLine()
    {
        $input = fgets(STDIN);
        $input = str_replace("\r","",$input);
        $input = str_replace("\n","",$input);
        return $input;       
    }
    /**
     * 写入新行到标准输出
     *
     * @param mixed $msg
     */
    public static function writeLine($msg)
    {
        self::write($msg."\n");
    }
    /**
     * 写入到标准输出
     *
     * @param mixed $msg
     */
    public static function write($msg)
    {
        fputs(STDOUT,$msg,strlen($msg));
    }
}
?>