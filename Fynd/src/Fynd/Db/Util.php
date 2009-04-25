<?php
final class Fynd_Db_Util
{
    private function __construct()
    {}
    /**
     * Convert simple filter string to parameterized sql.
     *
     * @param string $sql
     * @return Fynd_Dictionary
     */
    public static function toParameterizedSQL($sql)
    {
        $matches = array();
        $result = new Fynd_Dictionary();
        $params = new Fynd_List();
        $num = preg_match_all('/(\w+)\s*=\s*(\'?)("?)(\d+|[^\3\2]+?)\3\2/s', $sql, $matches, (PREG_OFFSET_CAPTURE + PREG_SET_ORDER));
        if(false !== $num)
        {
            for($i = 1; $i <= $num; $i ++)
            {
                $p = new Fynd_Db_Parameter();
                if(empty($matches[$i][2][0]) && empty($matches[$i][3][0]))
                {
                    $p->dataType = Fynd_Db_DataType::NUMBER;
                }
                else
                {
                    $p->dataType = Fynd_Db_DataType::STRING;
                }
                $p->name = "p_" . $matches[$i][1][0];
                $p->value = $matches[$i][4][0];
                $params->add($p);
                
                $sql = substr_replace($sql,':' . $p->name,$matches[$i][4][1],strlen($p->value));
            }
        }
        $result->add('sql',$sql);
        $result->add('parameters',$params);
        return $result;
    }
}
?>