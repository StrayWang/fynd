<?php
require_once ('Fynd/View.php');
class DishListView extends Fynd_View
{
    function __construct()
    {}
    /**
     * @see Fynd_View::render()
     *
     */
    public function render()
    {
        readfile('DishListView.html');
    }
}
?>