<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('admin');

if(empty($_SESSION['login']))
{ ?>
  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/formations/ajout.php"><button class="btn btn-default">Se connecter</button></a>
<?php
}

?>



<?php
drawFooter();
?>
