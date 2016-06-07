<?php
require_once '../autoload.php';

use EAVConnecteur\Classes\Entity;
use EAVConnecteur\Classes\Attribute;
if (isset($_POST['add'])){ 
$nameAttribute=$_POST['nameAttribute'];
$typeAttribute=$_POST['typeAttribute'];
$idEntity=$_GET['id'];
Attribute::addAttributeToEntity($idEntity, $nameAttribute, $typeAttribute);
?>
<SCRIPT LANGUAGE="JavaScript">
alert('The attribute has been succefully added');
document.location.href="index.php"
</SCRIPT>
<?php
}
else {
$idEntity=$_GET['id'];
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
	<h2> Please enter a name and a type for the attribute </h2>
	<form method="Post" action="addAttributeToEntity.php?id=<?php echo $idEntity?>"  class="form-inline well">
	<div class="input-append">
	<input type="textfield" name= "nameAttribute" placeholder="Attribute Name" required />
	<select name="typeAttribute" required><!--Combobox For attribute types-->
	<?php 
	   foreach(Attribute::getTypesAttributes() as $type_att):
	?>
	<option value="<?php echo $type_att?>"><?php echo $type_att?></option>
	<?php endforeach;?>
	</select>
	 <button class="btn btn-primary" type="button">Add an other attribute</button>

	</div>
	</br></br></br>
	<input type="submit" name= "add" value="Submit"  class="btn btn-lg btn-primary"/>
	</form>
	
	
	</div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
<?php
}
?>