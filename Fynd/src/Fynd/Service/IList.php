<?php
/**
 * Desribes the service which provides the list accesses.
 */
interface Fynd_Service_IList
{
    /**
     * Gets the model list
     *
     * @param int $pageSize
     * @param int $startOffset
     * @param string $orderby;
     * @param string $direction 'asc' or 'desc'
     * @param string $filter
     * @return Fynd_ICollection
     */
    public function getModelList($pageSize,$pageNo,$orderby='',$direction='asc',$filter = '');
    /**
     * Gets the count of models which matches the filter.
     *
     * @param string $filter
     */
    public function getModelCount($filter = '');
}
?>