<?php
class Fynd_Util
{
    /**
     * 将公共属性名转换成私有字段名
     * 公共属性如:FooBar
     * 私有字段如:_fooBar
     *
     * @param string $key
     * @return string
     */
    public static function convertToPrivateVar ($key)
    {
        $part1 = substr($key, 0, 1);
        $part1 = strtolower($part1);
        $part2 = substr($key, 1);
        $part2 = str_replace('_', '', $part2);
        $privateVar = '_' . $part1 . $part2;
        return $privateVar;
    }
    /**
     * 首字母大写
     *
     * @param string $str
     * @return string
     */
    public static function upperCaseFirstChar ($str)
    {
        $part1 = substr($str, 0, 1);
        $part1 = strtoupper($part1);
        $part2 = substr($str, 1);
        $r = $part1 . $part2;
        return $r;
    }
    /**
     * 从末尾$count个字符
     *
     * @param string $string
     * @param int $count
     * @return string
     */
    public static function stringRemoveEnd ($string, $count)
    {
        $string = substr($string, 0, strlen($string) - $count);
        return $string;
    }
    /**
     * 判断$string是不是以$with开头
     *
     * @param string $string
     * @param string $with
     * @return bool
     */
    public static function startWith ($string, $with)
    {
        $part1 = substr($string, 0, strlen($with));
        if ($part1 == $with)
        {
            return true;
        }
        return false;
    }
    public static function jsonEncode ($a = false)
    {
        if (is_null($a))
            return 'null';
        if ($a === false)
            return 'false';
        if ($a === true)
            return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }
            if (is_string($a))
            {
                static $jsonReplaces = array(array("\\" , "/" , "\n" , "\t" , "\r" , "\b" , "\f" , '"') , array('\\\\' , '\\/' , '\\n' , '\\t' , '\\r' , '\\b' , '\\f' , '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i ++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v)
                $result[] = self::jsonEncode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v)
                $result[] = self::jsonEncode($k) . ':' . self::jsonEncode($v);
            return '{' . join(',', $result) . '}';
        }
    }
}
?>