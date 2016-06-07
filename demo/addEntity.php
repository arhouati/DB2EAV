<?php

require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Entity;

if (isset($_POST['add'])){
    $entityName=$_POST['entityName'];
    Entity::addEntity($entityName);

    echo "<SCRIPT LANGUAGE=\"JavaScript\">
                alert('The entity has been succefully added');
                document.location.href=\"index.php\";
        </SCRIPT>";

}

?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>

    <div class="container">
	<h2> Please enter a name for the entity </h2>
	<form method="Post" action="addEntity.php" class="form-inline well" role="form">
	<input type="textfield" name= "entityName"  required />
	<input type="submit" name= "add" value="Add entity" class="btn btn-primary pullright" />
	</form>
	
	
	</div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
