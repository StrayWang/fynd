<?php
require_once ('Fynd/View.php');
class Fynd_View_JSON extends Fynd_View
{
    /**
     * @override
     * @return string
     */
    public function render()
    {
        $this->setMimeType('text/plain');
        echo Fynd_JSON::encode($this->_data);
    }
}
?>