<?php
set_include_path('.' . PATH_SEPARATOR . dirname(__FILE__) . '/../lib/' 
	 . PATH_SEPARATOR . dirname(__FILE__) . '/../app/'
	. PATH_SEPARATOR . get_include_path()); 
include 'Application.php';
$app = Fynd_Application::getInstance();
$app->run();
?>