<?php
require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Attribute;

$id=$_GET['id'];
$idEntity=$_GET['idEntity'];
Attribute::deleteAttribute($id,$idEntity);

?>
<SCRIPT LANGUAGE="JavaScript">
    alert('The attribute has been succefully deleted');
    document.location.href="index.php"
</SCRIPT>