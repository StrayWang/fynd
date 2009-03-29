<?php
require_once ('Fynd/View.php');
class Fynd_View_Download extends Fynd_View
{
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