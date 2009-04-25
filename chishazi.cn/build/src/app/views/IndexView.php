<?php
require_once ('Fynd/View.php');
class IndexView extends Fynd_View
{
    function __construct()
    {}
    /**
     * @see Fynd_View::render()
     *
     */
    public function render()
    {
        readfile('IndexView.html');
    }

}
?>