<?php
require_once ('Fynd/View.php');
class Fynd_View_Download extends Fynd_View
{
    public function setFileName($fileName)
    {
        $this->setHttpHeader("Content-Disposition","attachment; filename=" . $fileName);
    }
    /**
     * @override
     * @return string
     */
    public function render()
    {
        echo $this->_data;
    }
}
?>