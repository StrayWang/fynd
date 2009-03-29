<?php
require_once ('Fynd/Object.php');
class Fynd_JSON_Decoder extends Fynd_Object
{
    /**
     * Parse tokens used to decode the JSON object. These are not
     * for public consumption, they are just used internally to the
     * class.
     */
    const EOF = 0;
    const SCALAR = 1; //scalar
    const LBRACE = 2; //{
    const LBRACKET = 3; //[
    const RBRACE = 4; //}
    const RBRACKET = 5; //]
    const COMMA = 6; //,
    const COLON = 7; //:
    protected $_json;
    protected $_jsonLength;
    protected $_offset;
    protected $_currentToken;
    protected $_currentValue;

    public function __construct($json)
    {
        $this->_json = $json;
        $this->_jsonLength = strlen($json);
        $this->_offset = 0;
        $this->_currentToken = self::EOF;
        $this->_fetchNextToken();
    }
    /**
     * Decodes the json string
     *
     * @return mixed
     */
    public function decode()
    {
        switch($this->_currentToken)
        {
            case self::SCALAR:
                $result = $this->_currentValue;
                $this->_fetchNextToken();
                return ($result);
                break;
            case self::LBRACE:
                return ($this->_decodeObject());
                break;
            case self::LBRACKET:
                return ($this->_decodeArray());
                break;
            default:
                return null;
                break;
        }
    }
    /**
     * Deocodes the json string to an instance of StdClass
     *
     * @return StdClass
     */
    protected function _decodeObject()
    {
        $result = new StdClass();
        $token = $this->_fetchNextToken();
        while($token && $token != self::RBRACE)
        {
            if($token != self::SCALAR || ! is_string($this->_currentValue))
            {
                Fynd_Object::throwException("Fynd_JSON_Exception", 'Missing key in object encoding: ' . $this->_json);
            }
            $key = $this->_currentValue;
            $token = $this->_fetchNextToken();
            if($token != self::COLON)
            {
                Fynd_Object::throwException("Fynd_JSON_Exception", 'Missing ":" in object encoding: ' . $this->_json);
            }
            $token = $this->_fetchNextToken();
            $result->$key = $this->decode();
            $token = $this->_currentToken;
            if($token == self::RBRACE)
            {
                break;
            }
            if($token != self::COMMA)
            {
                Fynd_Object::throwException("Fynd_JSON_Exception", 'Missing "," in object encoding: ' . $this->_json);
            }
            $token = $this->_fetchNextToken();
        }
        $this->_fetchNextToken();
        return $result;
    }
    /**
     * Decodes json string to array.
     *
     * @return array
     */
    protected function _decodeArray()
    {
        $result = array();
        $token = $this->_fetchNextToken(); // Move past the '['
        $index = 0;
        while($token && $token != self::RBRACKET)
        {
            $result[$index ++] = $this->decode();
            $token = $this->_currentToken;
            if($token == self::RBRACKET || ! $token)
            {
                break;
            }
            if($token != self::COMMA)
            {
                Fynd_Object::throwException("Fynd_JSON_Exception",'Missing "," in array encoding: ' . $this->_json);
            }
            $token = $this->_fetchNextToken();
        }
        $this->_fetchNextToken();
        
        return $result;
    }
    /**
     * Removes the whitespace like \t \b \f \n \r and real space from json string.
     *  
     */
    protected function _removeWhitepace()
    {
        $matches = array();
        if(preg_match('/([\t\b\f\n\r ])*/s', $this->_json, $matches, PREG_OFFSET_CAPTURE, $this->_offset) && $matches[0][1] == $this->_offset)
        {
            $this->_offset += strlen($matches[0][0]);
        }
    }
    /**
     * Fetchs the next token,if it is a scalar,then sets the current token value.
     *
     * @return string
     */
    protected function _fetchNextToken()
    {
        $this->_currentToken = self::EOF;
        $this->_currentValue = null;
        $this->_removeWhitepace();
        if($this->_offset >= $this->_jsonLength)
        {
            return (self::EOF);
        }
        $str = $this->_json;
        $strLength = $this->_jsonLength;
        $i = $this->_offset;
        $start = $i;
        switch($str{$i})
        {
            case '{':
                $this->_currentToken = self::LBRACE;
                break;
            case '}':
                $this->_currentToken = self::RBRACE;
                break;
            case '[':
                $this->_currentToken = self::LBRACKET;
                break;
            case ']':
                $this->_currentToken = self::RBRACKET;
                break;
            case ',':
                $this->_currentToken = self::COMMA;
                break;
            case ':':
                $this->_currentToken = self::COLON;
                break;
            case '"':
                $result = '';
                do
                {
                    $i ++;
                    if($i >= $strLength)
                    {
                        break;
                    }
                    $chr = $str{$i};
                    if($chr == '\\')
                    {
                        $i ++;
                        if($i >= $strLength)
                        {
                            break;
                        }
                        $chr = $str{$i};
                        switch($chr)
                        {
                            case '"':
                                $result .= '"';
                                break;
                            case '\\':
                                $result .= '\\';
                                break;
                            case '/':
                                $result .= '/';
                                break;
                            case 'b':
                                $result .= chr(8);
                                break;
                            case 'f':
                                $result .= chr(12);
                                break;
                            case 'n':
                                $result .= chr(10);
                                break;
                            case 'r':
                                $result .= chr(13);
                                break;
                            case 't':
                                $result .= chr(9);
                                break;
                            case '\'':
                                $result .= '\'';
                                break;
                            default:
                                Fynd_Object::throwException("Fynd_JSON_Exception", "Illegal escape " . "sequence '" . $chr . "'");
                        }
                    }
                    elseif($chr == '"')
                    {
                        break;
                    }
                    else
                    {
                        $result .= $chr;
                    }
                }
                while($i < $strLength);
                $this->_currentToken = self::SCALAR;
                $this->_currentValue = $result;
                break;
            case 't':
                if(($i + 3) < $strLength && substr($str, $start, 4) == "true")
                {
                    $this->_currentToken = self::SCALAR;
                }
                $this->_currentValue = true;
                $i += 3;
                break;
            case 'f':
                if(($i + 4) < $strLength && substr($str, $start, 5) == "false")
                {
                    $this->_currentToken = self::SCALAR;
                }
                $this->_currentValue = false;
                $i += 4;
                break;
            case 'n':
                if(($i + 3) < $strLength && substr($str, $start, 4) == "null")
                {
                    $this->_currentToken = self::SCALAR;
                }
                $this->_currentValue = NULL;
                $i += 3;
                break;
        }
        if($this->_currentToken != self::EOF)
        {
            $this->_offset = $i + 1;
            return ($this->_currentToken);
        }
        //here,the next token maybe a number.
        $chr = $str{$i};
        if($chr == '-' || $chr == '.' || ($chr >= '0' && $chr <= '9'))
        {
            $matches = array();
            $scalar = null;
            if(preg_match('/-?([0-9])*(\.[0-9]*)?((e|E)((-|\+)?)[0-9]+)?/s', $str, $matches, PREG_OFFSET_CAPTURE, $start) && $matches[0][1] == $start)
            {
                $scalar = $matches[0][0];
                if(is_numeric($scalar))
                {
                    if(preg_match('/^0\d+$/', $scalar))
                    {
                        Fynd_Object::throwException("Fynd_JSON_Exception", "Octal notation not supported by JSON (value: $scalar)");
                    }
                    else
                    {
                        $val = intval($scalar);
                        $fVal = floatval($scalar);
                        //if float value equls the integer value,that means it's integer,otherwise,it's float.
                        $this->_currentValue = ($val == $fVal ? $val : $fVal);
                    }
                }
                else
                {
                    Fynd_Object::throwException("Fynd_JSON_Exception", "Illegal number format: $scalar");
                }
                $this->_currentToken = self::SCALAR;
                $this->_offset = $start + strlen($scalar);
            }
        }
        else
        {
            Fynd_Object::throwException("Fynd_JSON_Exception", 'Illegal Token:' . $chr);
        }
        return $this->_currentToken;
    }
}
?>