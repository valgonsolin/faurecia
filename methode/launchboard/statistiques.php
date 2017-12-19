<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

if(!isset($_GET['id'])){ ?>
  <h2>LaunchBoard</h2>
  <h4>OUPS... Votre session est inconnu.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard"> Retourner au LaunchBoard</a>
<?php }else{
?>
<h2>Projet</h2>
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu" style="margin-right:20%">Projet</a>
  <a href="statistiques.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu bouton_nav_selected" >Statistiques</a>
</div>


<?php
}
drawFooter();
 ?>
