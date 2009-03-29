<?php
require_once 'Fynd/Object.php';
final class Fynd_Model_State extends Fynd_Object 
{
	const ADDED    = 1;
	const MODIFIED = 2;
	const DELETED  = 3;
	const NONE     = 0;
	
	private function __construct()
	{}
}
?>