<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2>R&amp;R</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au R&amp;R</a>
<?php
}
else
{
  if(!(isset($_GET['id']))){?>
    <h2>R&amp;R</h2>
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
            <label>Réponse 1 :     </label><label style="margin-left:20px">
              <input type="hidden" value="0" name="vrai1">
              <input name="vrai4" type="checkbox" value="1" <?php if($Data['corrige_1']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_1">
            <input type="hidden" name="old_file_1" value="<?php echo $Data['reponse_1']; ?>">
          </div>
          <?php if($Data['reponse_1'] != NULL){ ?>
            <input type="submit" name="img-reset1" value="Supprimer l'image" class="btn btn-default" style="position: absolute; left:5px; bottom:5px;">
          <?php } ?>
          <div class="col-md-5">
        <?php
          if($Data['reponse_1'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['reponse_1']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px;  margin:5px;" alt="Image de correction">
          <?php } ?>
          </div>
        </div>
      </div>
      <div class"form-group">
        <div class="row entree">
          <div class="col-md-7">
            <label>Réponse 2 :     </label><label style="margin-left:20px">
              <input type="hidden" value="0" name="vrai2">
              <input name="vrai2" type="checkbox" value="1" <?php if($Data['corrige_2']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_2">
            <input type="hidden" name="old_file_2" value="<?php echo $Data['reponse_2']; ?>">
          </div>
          <?php if($Data['reponse_2'] != NULL){ ?>
            <input type="submit" name="img-reset2" value="Supprimer l'image" class="btn btn-default" style="position: absolute; left:5px; bottom:5px;">
          <?php } ?>
          <div class="col-md-5">
        <?php
          if($Data['reponse_2'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['reponse_2']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; margin:5px;" alt="Image de correction">
          <?php } ?>
          </div>
        </div>
      </div>
      <div class"form-group">
        <div class="row entree">
          <div class="col-md-7">
            <label>Réponse 3 :     </label><label style="margin-left:20px">
              <input type="hidden" value="0" name="vrai3">
              <input name="vrai3" type="checkbox" value="1" <?php if($Data['corrige_3']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_3">
            <input type="hidden" name="old_file_3" value="<?php echo $Data['reponse_3']; ?>">
          </div>
          <?php if($Data['reponse_3'] != NULL){ ?>
            <input type="submit" name="img-reset3" value="Supprimer l'image" class="btn btn-default" style="position: absolute; left:5px; bottom:5px;">
          <?php } ?>
          <div class="col-md-5">
        <?php
          if($Data['reponse_3'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['reponse_3']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; margin:5px; " alt="Image de correction">
          <?php } ?>
          </div>
        </div>
      </div>
      <div class"form-group">
        <div class="row entree">
          <div class="col-md-7">
            <label>Réponse 4 :     </label><label style="margin-left:20px">
              <input type="hidden" value="0" name="vrai4">
              <input name="vrai4" type="checkbox" value="1" <?php if($Data['corrige_4']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_4">
            <input type="hidden" name="old_file_4" value="<?php echo $Data['reponse_4']; ?>">
          </div>
          <?php if($Data['reponse_4'] != NULL){ ?>
            <input type="submit" name="img-reset4" value="Supprimer l'image" class="btn btn-default"  style="position: absolute; left:5px; bottom:5px;">
          <?php } ?>
          <div class="col-md-5">
        <?php
          if($Data['reponse_4'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($Data['reponse_4']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; margin:5px;" alt="Image de correction">
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
}
?>





<?php
drawFooter();
