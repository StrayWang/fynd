<?php
interface Fynd_Db_ISequence
{
    /**
     * Sets the object's name which object using this sequence.
     *
     * @param string $obj
     */
    public function setObject($objectName);
    /**
     * Get the next value of the sequence.
     * 
     * @return scalar
     *
     */
    public function getNextValue();
}
?>