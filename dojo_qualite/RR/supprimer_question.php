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
      <div class="form-group">
        <label>Question n°</label>
        <input class="form-control" type="number" name="ordre2" value="<?php echo $Data['ordre']; ?>">
        <input type="hidden" name="ordre1" value="<?php echo $Data['ordre']; ?>" >
      </div>
    	<div class="form-group">
      <label>Type</label>
  	  <select name="type" class="form-control" value="<?php echo $Data['type']; ?>">
      <option value="0" selected="selected">MOD</option>
      <option value="1">MOI</option>
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
          <div class="col-md-7">
            <label>Réponse 1 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox" <?php if($Data['corrige_1']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_1">
          </div>
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
            <label>Réponse 2 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox" <?php if($Data['corrige_2']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_2">
          </div>
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
            <label>Réponse 3 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox" <?php if($Data['corrige_3']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_3">
          </div>
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
            <label>Réponse 4 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox" <?php if($Data['corrige_4']){echo "checked" ;}?>> Vrai</label>
            <input type="file" name="file_4">
          </div>
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
      <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
      <button type="submit" name="modifier" class="btn btn-default" onclick="return confirm('Modifier la question ?');">Modifier</button>
      <button type="submit" name="supprimer" class="btn btn-default" onclick="return confirm('Supprimer la question ?');">Supprimer</button>


    </form>




<?php
}
}
?>





<?php
drawFooter();
