<?php
require_once 'Fynd/Db/ISequence.php';
require_once ('Fynd/Object.php');
class Fynd_Db_Sequence extends Fynd_Object implements Fynd_Db_ISequence
{
    protected $_objectName = null;
    /**
     * @var Fynd_Db_IConnection
     */
    protected $_conn = null;
    
    /**
     * The command of getting sequence value. 
     *
     * @var Fynd_Db_ICommand
     */
    protected $_command = null;    
    
    
    /**
     * @see Fynd_Db_ISequence::setObject()
     *
     * @param string $obj
     */
    public function setObject($objectName)
    {
        $this->_objectName = $objectName;
    }
    /**
     * Sets the database connection object using by this sequence.
     *
     * @param Fynd_Db_IConnection $conn
     */
    public function setDbConnection(Fynd_Db_IConnection $conn)
    {
        $this->_conn = $conn;
    }
    /**
     * @see Fynd_Db_ISequence::getNextValue()
     *
     * @return scalar
     */
    public function getNextValue()
    {
        if(is_null($this->_command))
        {
            //TODO:SET parameter "p_val" direction to OUT
            $sql = "UPDATE sequence SET last_value = :p_val := last_value + 1 WHERE name = :p_field"; 
            $sql = "SELECT s.last_value + 1 FROM sequence AS s WHERE s.field_name = :p_field";
            $this->_command->$this->_conn->createCommand($sql);
            $p1 = new Fynd_Db_Parameter();
            $p1->name = ':p_val';
            $p1->direction = Fynd_Db_Parameter::OUT;
            $p1->dataType = Fynd_Db_DataType::NUMBER;
            $this->_command->addParameter($p1);
            $p2 = new Fynd_Db_Parameter();
            $p2->name = 'p_field';
            $p2->value = $this->_objectName;
            $p2->dataType = Fynd_Db_DataType::NUMBER;
            $this->_command->addParameter($p2);
        }
        
        $isConnOpen = false;
        try
        {
            $isConnOpen = $this->_conn->open();
            $this->_command->excuteNonQuery();
        }
        catch (Exception $e)
        {
            
        }
        $this->open();
        $this->setFetchMode(PDO::FETCH_ASSOC);
        $seq = $this->query($sql,array($p1));
        if($seq)
        {
            $sqlUpdate = "Update `sequence` As s Set s.sequence = :v_sequence Where s.field_name = :p_field";
            
            $this->excute($sqlUpdate,array($p1,$p2));
        }
        $this->close();
        return $seq;
    }

}
?>