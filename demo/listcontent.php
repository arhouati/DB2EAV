<?php

require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Content;

$listContentByEntity = Content::getAllContentByEntity( $_GET["id"] );

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
              <a class="btn btn-primary btn-large" href="index.php">Retourner</a>
              <h2>Liste des contenus du type #id = <?php echo $_GET['id'] ?></h2>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>identifier</th>
                      <th>--</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($listContentByEntity as $content): ?>
                    <tr>
                      <td><?php echo $content->id ?></td>
                      <td><?php echo $content->identifier ?></td>
                      <td><a href="detailcontent.php?id=<?php echo $content->id ?>&entity=<?php echo $_GET["id"]?>">Details</a></td>
                      <td><a href="updatecontent.php?id=<?php echo $content->id ?>&entity=<?php echo $_GET["id"]?>">Update content</a></td>
                      <td><a href="deletecontent.php?id=<?php echo $content->id ?>&entity=<?php echo $_GET["id"]?>" onclick="if(!confirm('Do you want to delete this content?')) return false;" >Delete Content</a></td>
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
        </div> <!-- /container -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
