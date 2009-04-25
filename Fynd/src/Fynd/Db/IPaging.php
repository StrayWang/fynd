<?php
require_once 'Fynd/Db/ISQLBuilder.php';
interface Fynd_Db_IPaging extends Fynd_Db_ISQLBuilder
{
    /**
     * Get the page size
     * 
     * @return int
     */
    public function getPageSize();
    /**
     * Set the page size
     *
     * @param int $pageSize
     */
    public function setPageSize($pageSize);
    /**
     * Gets the start offset in the result set.
     *
     * @return int
     */
    public function getStartOffset();
    /**
     * Sets the start offset in the result set.
     *
     * @param int $offset
     */
    public function setStartOffset($offset);
    /**
     * Get the database connection object's type
     * 
     * @return Fynd_Type
     *
     */
    public function getConnectionObjectType();
    /**
     * Set the database connection object's type,
     * determine the paging sql how to be written.
     *
     * @param Fynd_Type $type
     */
    public function setConnectionObjectType(Fynd_Type $type);
    /**
     * Get the original sql
     *
     * @return string
     */
    public function getOrginSQL();
    /**
     * Set the original sql
     *
     * @param string $sql
     */
    public function setOrginSQL($sql);
}
?>