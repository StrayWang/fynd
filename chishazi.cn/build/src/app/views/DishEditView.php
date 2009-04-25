<?php
require_once ('Fynd/View/Html.php');
class DishEditView extends Fynd_View_Html
{
    function __construct($path = '')
    {
        if(empty($path))
        {
            $path = Fynd_Env::getViewPath() . "DishEditView.html";
        }
        parent::__construct($path);
    }
}
?>