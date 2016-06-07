<?php

$baseDir = dirname(__FILE__);

$classesDir  = array( "root" => $baseDir );

$slashDir = "/";

function __autoload( $nameClass ) {

    global $classesDir;
	global $slashDir;

	foreach( $classesDir as $dir ){
		$nameClass =  str_replace('\\', $slashDir, $nameClass);
		$dirClass = $dir . "/../" . $nameClass . '.php';

		if(is_file( $dirClass )){
			require_once $dirClass;
			break;
		}
		
	}
	
}