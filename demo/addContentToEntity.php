<?php

require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Entity;
use db2eav\Classes\Content;

$idEntity=$_GET['id'];


$entity = new Entity( $idEntity );


if (isset($_POST['add'])) {
    $nameContent = $_POST['nameContent'];

    Content::addContentToEntity($idEntity, $nameContent);

    $idContent = Content::getIdContentByIdentifierAndEntity($nameContent, $idEntity);


    foreach ($entity->attributes as $attribute) {
        $idAttribute = $attribute->id;
        $value = $_POST[$attribute->identifier];
        $attributeType = $_POST["attributeType_" . $attribute->identifier];
        Content::addValueToContent($idAttribute, $value, $idContent, $attributeType);
    }

    echo "<SCRIPT LANGUAGE=\"JavaScript\">
                    alert('The content has been succefully added');
                    document.location.href=\"index.php\"
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
	<h2> Please enter a name and of content </h2>
	<form method="Post" action="addContentToEntity.php?id=<?php echo $idEntity?>"  class="form-inline well">
	
	<div class="input-append">
	<table>
	<tr><input type="textfield" name= "nameContent" placeholder="Content Name" required /></tr>
	<?php
				
				foreach( $entity->attributes as $attribute ): ?>
	                  <td><label><br/>Attribute :<?php echo $attribute->identifier?></label></td>
					  <td><br/><input type="text" name="<?php echo $attribute->identifier?>" /> </td>
					  <td><input type="hidden" name="attributeType_<?php echo $attribute->identifier?>" value="<?php echo $attribute->type?>" /> </td>
					  </tr>
                <?php endforeach; ?>
	</table>			
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
