<?php
/**
 * Describes the controller which handles the list request.
 */
interface Fynd_Controller_IList
{
    /**
     * Handle the paging list request.
     *
     * @param Fynd_Request $request Contains follow parameters in GET or POST:
     * dir : sort derection,the value will be 'asc' or 'desc'
     * results : the page size.
     * sort : which property or field used to sort.
     * startIndex : start offset in the whole result set.
     */
    public function pagingListAct(Fynd_Request $request);
}
?>