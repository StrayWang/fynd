<?php
final class Fynd_Db_DataType
{
    const STRING     = 20;
    const NUMBER     = 21;
    const DATETIME   = 22;
    
    const STRING_STR     = "string";
    const NUMBER_STR     = "number";
    const DATETIME_STR   = "datetime";
    
    private function __construct()
    {}
    /**
     * Gets the data type enumermation value by the data type name.
     *
     * @param string $dataTypeName
     * @return int
     */
    public static function getDataTypeEnum($dataTypeName)
    {
        if(self::STRING_STR == $dataTypeName)
        {
            return self::STRING;
        }
        if(self::NUMBER_STR == $dataTypeName)
        {
            return self::NUMBER;
        }
        if(self::DATETIME_STR == $dataTypeName)
        {
            return self::DATETIME;
        }
    }
}
?>