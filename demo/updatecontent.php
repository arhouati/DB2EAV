<?php

require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Content;
use db2eav\Classes\Entity;

if (isset($_POST['update'])) {
    $nameContent = $_POST['nameContent'];
    $idEntity = $_POST['entity'];
    $idContent = $_GET['id'];
    $entity = new Entity($idEntity);
    Content::updateContentIdentifier($idEntity, $nameContent, $idContent);

    foreach ($entity->attributes as $attribute) {
        $idAttribute = $attribute->id;
        $value = $_POST[$attribute->identifier];
        $attributeType = $_POST["attributeType_" . $attribute->identifier];
        Content::updateValues($idAttribute, $value, $idContent, $attributeType);
    }

    echo "<SCRIPT LANGUAGE=\"JavaScript\">
            alert('The content has been succefully updated');
            document.location.href=\"index.php\"
           </SCRIPT>";

}

$idContent=$_GET['id'];
Content::__init();
$content = Content::getContentByIdAndEntity($_GET['id'], $_GET['entity']);

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
	<h2> Update content #id=<?php echo $idContent; ?>, #identifier = <?php echo $content->identifier; ?>  et #entity = <?php echo $content->entity->id; ?>  </h2>
	<form method="Post" action="updatecontent.php?id=<?php echo $idContent;?>"  class="form-inline well">
	<div class="input-append">
	Content Name: &nbsp;&nbsp;<input type="textfield" name="nameContent" value="<?php echo $content->identifier; ?>"/>
							  <input type="hidden" name="entity" value=" <?php echo $content->entity->id; ?>"/>
	<table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>attribute</th>
                  <th>type</th>
                  <th>value</th>
                </tr>
              </thead>
              <tbody>
              	
				<?php foreach($content->entity->attributes as $attribute): ?>
                <tr>
                  <td><?php echo $attribute->id ?></td>
                  <td><?php echo $attribute->identifier ?></td>
                  <td><?php echo $attribute->type ?></td>
				  <?php
				  $content->attributes[$attribute->identifier];
				  ?>
				  <input type="hidden" name="attributeType_<?php echo $attribute->identifier?>" value="<?php echo $attribute->type?>" />
                  <td><input name="<?php echo $attribute->identifier?>" value= "<?php echo $content->attributes[$attribute->identifier]; ?>" /></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
	
	
	
	
	</br></br></br>
	<input type="submit" name= "update" value="Update"  class="btn btn-lg btn-primary"/>
	</div>
	</form>
	</div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
