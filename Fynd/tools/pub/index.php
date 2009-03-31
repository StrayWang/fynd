<?php
//set_include_path('.' . PATH_SEPARATOR . dirname(__FILE__) . '/../app/lib/' 
//	. PATH_SEPARATOR . get_include_path()); 
require 'Fynd/Application.php';
$app = Fynd_Application::getInstance();
$app->start();
?>