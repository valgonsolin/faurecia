<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('excel');

if($_SESSION['idees']){

?>
<a href="export2.php"><button type="button" class="btn btn-default btn-lg btn-block">Exporter toutes les idées du mois </button></a>

<br>

<a href="export.php"><button type="button" class="btn btn-default btn-lg btn-block">Exporter toutes les idées </button></a>


<?php 
}else{ echo " Vous n'avez pas le droit d'exporter les IA";}
drawFooter();
 ?>
