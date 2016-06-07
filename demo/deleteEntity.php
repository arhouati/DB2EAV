<?php
require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Entity;

$id=$_GET['id'];
Entity::deleteEntity($id);

?>
<SCRIPT LANGUAGE="JavaScript">
    alert('The entity has been succefully deleted');
    document.location.href="index.php"
</SCRIPT>