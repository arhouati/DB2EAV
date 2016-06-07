<?php
require_once '../src/db2eav/autoload.php';

use db2eav\Classes\Content;

$id=$_GET['id'];
Content::deleteContent($id);

?>
<SCRIPT LANGUAGE="JavaScript">
    alert('The content has been succefully deleted');
    document.location.href="index.php"
</SCRIPT>