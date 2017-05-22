<?php 

function escape($string){
	return htmlentities($string, ENT_COMPAT, 'UTF-8');
}