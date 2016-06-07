<?php
require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Entity;

$allEntityOfCMS = Entity::getAllEntities( "drupal" );

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
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-12">
       	 <a class="btn btn-danger" href="search.php">Lancer une recherche</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a class="btn btn-danger" href="addEntity.php">Add entity</a>
          <h2>Liste des types de contenu</h2>
			<table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>type</th>
                  <th>--</th>
                  <th>--</th>
                </tr>
              </thead>
              <tbody>
				<?php foreach($allEntityOfCMS as $entity): ?>
                <tr>
                  <td><?php echo $entity->id ?></td>
                  <td><?php   ?></td>
                  <?php foreach($entity->other as $chmp => $value): ?>
                 	 <td><?php echo $chmp . ' : ' . $value ?></td>
                  <?php endforeach;?>
                  <td><a href="listcontent.php?id=<?php echo $entity->id?>">Contents</a></td>
                  <td><a href="attributes.php?id=<?php echo $entity->id?>">Attributes</a></td>
                  <td><a href="addAttributeToEntity.php?id=<?php echo $entity->id?>">Add attributes to entity</a></td>
				  <td><a href="addContentToEntity.php?id=<?php echo $entity->id?>">Add content to entity</a></td>
				  <td><a href="deleteEntity.php?id=<?php echo $entity->id?>" onclick="if(!confirm('Do you want to delete this entity?')) return false;">Delete entity</a>
				  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
        </div>
      </div>
      

      <hr>

      <footer>
        <p>EAV CMS Connector &copy; Company 2016</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
