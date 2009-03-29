<?php
require_once ('Fynd/Object.php');
class Fynd_StringUtil extends Fynd_Object
{
    private function __construct()
    {}
    /**
     * Capitalize the string
     *
     * @param string $str
     * @return string
     */
    public static function capitalize($str)
    {
        $part1 = substr($str, 0, 1);
        $part1 = strtoupper($part1);
        $part2 = substr($str, 1);
        $r = $part1 . $part2;
        return $r;
    }
    /**
     * Remove $count chars at the end of the string
     *
     * @param string $str
     * @param int $count Default value is 1.
     * @return string
     */
    public static function removeEnd($str, $count = 1)
    {
        $string = substr($str, 0, strlen($str) - $count);
        return $string;
    }
    /**
     * Check if does the string start with a special string or not
     *
     * @param string $string
     * @param string $with
     * @return bool
     */
    public static function startWith($string, $with)
    {
        if(substr_compare($string, $with, 0, strlen($with)) === 0)
        {
            return true;
        }
        return false;
    }
    /**
     * Check if deos the string end with a special string or not
     *
     * @param string $string
     * @param string $with
     * @return bool
     */
    public static function endWith($string, $with)
    {
        if(substr_compare($string, $with, (0 - strlen($with))) === 0)
        {
            return true;
        }
        return false;
    }
}
?>