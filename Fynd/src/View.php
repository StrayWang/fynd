<?php
require_once 'IView.php';
abstract class Fynd_View extends Fynd_PublicPropertyClass implements Fynd_IView 
{
    public $isUseTemplateEngine = false;
    protected function setMimeType($mime)
    {
        header('Content-Type:'.$mime);
    }
}
?>