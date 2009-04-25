<?php
require_once ('Fynd/View/Html.php');
class FoodEditView extends Fynd_View_Html
{
    function __construct($htmlFile = '')
    {
        if(empty($htmlFile))
        {
            $htmlFile = Fynd_Env::getViewPath() . 'FoodEditView.html';
        }
        parent::__construct($htmlFile);
    }
}
?>