<?php defined('ACCESS') or die("No direct script access allowed");

function pre($_value) 
{ 
	if($_value === null || $_value === false || $_value === true) 
		var_dump($_value); 
	else
		echo "<pre>"; print_r($_value); echo "</pre>";
}

function predie($_value)
{ 
	pre($_value); exit(); 
}