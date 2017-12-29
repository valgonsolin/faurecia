<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2>R&amp;R</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_qualite/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au R&amp;R</a>
<?php
}
else
{
  echo "<h2>R&amp;R</h2>";
  if(!$_SESSION['rr']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie.<a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{
  if(!(isset($_GET['id']))){?>
    <h4>OUPS... Votre session est inconnu.</h4>
    <a href="index.php"> Retourner au R&amp;R</a>
  <?php }else{
    ?>
      <h2>R&amp;R</h2>
      <style>
      .entree{
        background-color: #efefef;
        box-shadow: 2px 2px 4px grey;
        margin-top: 5px;
        margin-bottom: 5px;
        border-radius: 6px;
        position:relative;
      }
      </style>
      <div class="boutons_nav" style="display: flex; justify-content: center;">
        <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
        <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
      </div>
      <?php
    $query = $bdd -> prepare('SELECT * FROM qualite_RR_question WHERE id = ?');
    $query -> execute(array($_GET['id']));
    $Data=$query -> fetch(); ?>

    <form action="suppression.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
      <div class="form-group">
        <label>Question n°</label>
        <input class="form-control" type="number" name="ordre2" value="<?php echo $Data['ordre']; ?>">
        <input type="hidden" name="ordre1" value="<?php echo $Data['ordre']; ?>" >
      </div>
    	<div class="form-group">
      <label>Type</label>
  	  <select name="type" class="form-control" >
      <option value="0" <?php if(!$Data['type']){echo "selected";} ?>>MOD</option>
      <option value="1" <?php if($Data['type']){echo "selected";} ?>>MOI</option>
      </select>
    	<label>Titre</label>
    	<input class="form-control" name="titre" type="text" value="<?php echo $Data['titre']; ?>">
    	</div>
    	<div class="form-group">
    	<label>Question</label>
    	<input class="form-control" name="question" type="text" value="<?php echo $Data['question']; ?>">
    	</div>
      <div class"form-group">
        <div class="row entree">
          <div class="col-md-7" >
            <label>Image :     </label><label style="margin-left:20px">
              <input type="hidden" value="0" name="vrai1">
              <input name="vrai1" type="checkbox" value="1" <?php if($Data['valide']){echo "checked" ;}?>> Valide</label>
            <input type="file" name="file_1">
            <input type="hidden" name="old_file_1" value="<?php echo $Data['image']; ?>">
          </div>
          <div class="col-md-5">
        <?php
          if($Data['image'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['image']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px;  margin:5px;" alt="Image de correction">
          <?php } ?>
          </div>
        </div>
      </div>
      <button type="submit" name="modifier" class="btn btn-default" onclick="return confirm('Modifier la question ?');">Modifier</button>
      <button type="submit" name="supprimer" class="btn btn-default" onclick="return confirm('Supprimer la question ?');">Supprimer</button>
      <a href="suppression.php" class="btn btn-default pull-right" >Retour</a>


    </form>




<?php
}
}}
?>





<?php
drawFooter();
