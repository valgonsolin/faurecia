<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('R&R');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/RR/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  echo "<h2>Quiz</h2>";
  $query= $bdd -> query('SELECT * FROM qualite_quiz_question ORDER BY ordre DESC LIMIT 1');
  $Data = $query -> fetch();
  $lastOrdre= $Data['ordre'];

  if(!empty($_POST)){
    $vrai1=0;
    $vrai2=0;
    $vrai3=0;
    $vrai4=0;
    if(isset($_POST['vrai1'])){
      $vrai1=$_POST['vrai1'];
    }
    if(isset($_POST['vrai2'])){
      $vrai1=$_POST['vrai2'];
    }
    if(isset($_POST['vrai3'])){
      $vrai1=$_POST['vrai3'];
    }
    if(isset($_POST['vrai4'])){
      $vrai1=$_POST['vrai4'];
    }
    if($lastOrdre >= $_POST['ordre']){
      $query = $bdd -> prepare('UPDATE qualite_quiz_question SET ordre=ordre+1 WHERE ordre >= ? ');
      $query -> execute(array($_POST['ordre']));
    }
    $query = $bdd -> prepare('INSERT INTO qualite_quiz_question(type,titre,question,reponse_1,reponse_2,reponse_3,reponse_4,corrige_1,corrige_2,corrige_3,corrige_4,ordre) VALUES (:type,:titre,:question,:reponse_1,:reponse_2,:reponse_3,:reponse_4,:corrige_1,:corrige_2,:corrige_3,:corrige_4,:ordre)');
    $id= $bdd -> lastInsertId();
    $query -> execute(array(
      'type' => $_POST['type'],
      'titre' => $_POST['titre'],
      'question' => $_POST['question'],
      'reponse_1' => $_POST['reponse1'],
      'reponse_2' => $_POST['reponse2'],
      'reponse_3' => $_POST['reponse3'],
      'reponse_4' => $_POST['reponse4'],
      'corrige_1' => $vrai1,
      'corrige_2' => $vrai2,
      'corrige_3' => $vrai3,
      'corrige_4' => $vrai4,
      'ordre' => $_POST['ordre']
    ));

    if($query ==false){ ?>
      <div class="alert alert-danger">
          <strong>Erreur</strong>  -  Les données entrées ne sont pas conformes.
      </div>
    <?php }else{ ?>
          <div class="alert alert-success">
        <strong>Ajouté</strong>  -  La question a bien été ajoutée.
    </div>
    <?php
  }}
  ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;">
    <div class="form-group">
      <label>Question n°</label>
      <input type="number" class="form-control" name="ordre" value="<?php echo $lastOrdre+1 ?>">
    </div>
  	<div class="form-group">
  	<label>Type</label>
  	<select name="type" class="form-control">
  		<option value="0" selected="selected">MOD</option>
  		<option value="1">Autre</option>
  	</select>
  	<label>Titre</label>
  	<input class="form-control" name="titre" type="text">
  	</div>
  	<div class="form-group">
  	<label>Question</label>
  	<input class="form-control" name="question" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 1 :     </label><label style="margin-left:20px"><input name="vrai1" type="checkbox"> Vrai</label>
  		<input name="reponse1" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 2 :     </label><label style="margin-left:20px"><input name="vrai2" type="checkbox"> Vrai</label>
  		<input name="reponse2" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 3 :     </label><label style="margin-left:20px"><input name="vrai3" type="checkbox"> Vrai</label>
  		<input name="reponse3" class="form-control" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 4 :     </label><label style="margin-left:20px"><input name="vrai4" type="checkbox"> Vrai</label>
  		<input name="reponse4" class="form-control" type="text">
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>


<?php
}
?>





<?php
drawFooter();
 ?>
