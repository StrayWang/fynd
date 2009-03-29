<?php
require_once ('Fynd/Object.php');
class Fynd_JSON extends Fynd_Object
{
    /**
     * @var Fynd_Log
     */
    private static $_log = null;
    /**
     * @return Fynd_Log
     */
    private static function _getLogger()
    {
        if(is_null(self::$_log))
        {
            self::$_log = Fynd_Application::getLogger('Fynd_JSON');
        }
        return self::$_log;
    }
    /**
     * Decode the JSON encoded string
     *
     * @param string $json
     * @return mixed
     */
    public static function decode($json)
    {
//       $decoder = new Fynd_JSON_Decoder($json);
//        return $decoder->decode();
        return json_decode($json);
    }
    /**
     * Encode the string using JSON encoding rules.
     *
     * @param mixed $a
     * @return string
     */
    public static function encode($a = false)
    {
        //first try to encode datum(scalar)
        if(is_null($a))
        {
            return 'null';
        }
        if($a === false)
        {
            return 'false';
        }
        if($a === true)
        {
            return 'true';
        }
        if(is_scalar($a))
        {
            if(is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }
            if(is_string($a))
            {
                static $jsonReplaces = array(
                        array('\\' , "/" , "\n" , "\t" , "\r" , "\b" , "\f" , '"') , 
                        array('\\\\' , '\\/' , '\\n' , '\\t' , '\\r' , '\\b' , '\\f' , '\"'));
                $string = str_replace($jsonReplaces[0], $jsonReplaces[1], $a);
                $string = str_replace(array(chr(0x08), chr(0x0C)), array('\b', '\f'), $string);     
                return '"' . $string . '"';
            }
            else
            {
                return $a;
            }
        }
        //here,it is not scalar.
        $isList = true;//whether or not is it a numberic indexed array.
        for($i = 0, reset($a); $i < count($a); $i ++, next($a))
        {
            if(key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        //but if it implements the Iterator interface,it always be treated to a numberic indexed array.
        if(is_object($a) === true)
        {
            $ref = new ReflectionObject($a);
            if($ref->implementsInterface('Iterator'))
            {
                $isList = true;
            }
        }
        //ok,begin to encode the numberic indexed array.
        $result = array();
        if($isList)
        {
            foreach($a as $v)
            {
                $result[] = self::encode($v);
            }
            return '[' . join(',', $result) . ']';
        }
        else
        {
            //endcode the object,or associated array,to be encoded as json object.
            foreach($a as $k => $v)
            {
                self::_getLogger()->logInfo($k);
                $result[] = self::encode($k) . ':' . self::encode($v);
            }
            return '{' . join(',', $result) . '}';
        }
    }
}
?>