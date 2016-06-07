<?php
require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Content;

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
          <!-- Example row of columns -->
          <div class="row">
            <div class="col-lg-12">
              <a class="btn btn-primary btn-large" href="index.php">Retourner</a>

              <h2>Detail du contenu #id = <?php echo $_GET['id'] ?>, #identifier = <?php echo $content->identifier ?>  et #entity = <?php echo $content->entity->id ?> </h2>
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
                      <td><p><?php echo $content->attributes[$attribute->identifier] ?></p></td>
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
        </div>
        <!-- /container -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
