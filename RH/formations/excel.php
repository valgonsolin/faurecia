<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('admin');
if($_SESSION['admin']){
?>


<a href="export.php"><button type="button" class="btn btn-default btn-lg btn-block">Exporter tout le dossier formation </button></a>
<br><br>



<?php
}else{echo " Vous n'avez pas les droits pour exportes les formations.";}
drawFooter();
 ?>
