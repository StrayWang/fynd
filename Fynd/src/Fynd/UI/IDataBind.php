<?php
interface Fynd_UI_IDataBind
{
    /**
     * Sets the datasource of the ui component
     *
     * @param Fynd_IList $dataSource
     */
    public function setDataSource(Fynd_IList $dataSource);
    /**
     * Binds the datasource to ui component
     *
     */
    public function bindData();
}
?>