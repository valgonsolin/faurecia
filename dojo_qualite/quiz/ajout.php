<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('quiz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">Quiz</a>
<?php
}
else
{
  echo "<h2>Quiz</h2>";
  $lastOrdre=-1;
  $query= $bdd -> query('SELECT * FROM qualite_quiz_question ORDER BY ordre DESC LIMIT 1');
  while ($Data = $query->fetch()) {
  $lastOrdre= $Data['ordre'];
  }

  if(!empty($_POST)){
    if($lastOrdre >= $_POST['ordre']){
      $query = $bdd -> prepare('UPDATE qualite_quiz_question SET ordre=ordre+1 WHERE ordre >= ? ');
      $query -> execute(array($_POST['ordre']));
    }
    $file=upload($bdd,'file',"../../ressources","Quiz",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($file < 0){$file=NULL;}
    $reponse1=$_POST['reponse1'];
    $reponse2=$_POST['reponse2'];
    $reponse3=$_POST['reponse3'];
    $reponse4=$_POST['reponse4'];
    if($_FILES['file_1']['name'] != ""){
      $id1=upload($bdd,'file_1',"../../ressources","Quiz",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
      if($id1>=0){
        $reponse1="img=".$id1;
      }
    }
    if($_FILES['file_2']['name'] != ""){
      $id2=upload($bdd,'file_2',"../../ressources","Quiz",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
      if($id2>=0){
        $reponse2="img=".$id2;
      }    }
    if($_FILES['file_3']['name'] != ""){
      $id3=upload($bdd,'file_3',"../../ressources","Quiz",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
      if($id3>=0){
        $reponse3="img=".$id3;
      }    }
    if($_FILES['file_4']['name'] != ""){
      $id4=upload($bdd,'file_4',"../../ressources","Quiz",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
      if($id4>=0){
        $reponse4="img=".$id4;
      }    }
    $query = $bdd -> prepare('INSERT INTO qualite_quiz_question(type,titre,question,reponse_1,reponse_2,reponse_3,reponse_4,corrige_1,corrige_2,corrige_3,corrige_4,ordre,image_correction,commentaire) VALUES (:type,:titre,:question,:reponse_1,:reponse_2,:reponse_3,:reponse_4,:corrige_1,:corrige_2,:corrige_3,:corrige_4,:ordre,:file,:commentaire)');
    $id= $bdd -> lastInsertId();
    $query -> execute(array(
      'type' => $_POST['type'],
      'titre' => $_POST['titre'],
      'question' => $_POST['question'],
      'reponse_1' => $reponse1,
      'reponse_2' => $reponse2,
      'reponse_3' => $reponse3,
      'reponse_4' => $reponse4,
      'corrige_1' => $_POST['vrai1'],
      'corrige_2' => $_POST['vrai2'],
      'corrige_3' => $_POST['vrai3'],
      'corrige_4' => $_POST['vrai4'],
      'ordre' => $_POST['ordre'],
      'file' => $file,
      'commentaire' => $_POST['commentaire']
    ));

    if($query ==false){
      warning('Erreur','Les données entrées ne sont pas conformes.');
    }else{
      success('Ajouté','La question a bien été ajoutée.');
      $lastOrdre++;
    }
  }
  ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;" enctype="multipart/form-data">
    <div class="form-group">
      <label>Question n°</label>
      <input type="number" class="form-control" name="ordre" value="<?php echo $lastOrdre+1 ?>">
    </div>
  	<div class="form-group">
  	<label>Type</label>
  	<select name="type" class="form-control">
  		<option value="0" selected="selected">MOD</option>
  		<option value="1">MOI</option>
  	</select>
  	<label>Titre</label>
  	<input class="form-control" name="titre" type="text">
  	</div>
  	<div class="form-group">
  	<label>Question</label>
  	<input class="form-control" name="question" type="text">
  	</div>
  	<div class="form-group">
  		<label>Réponse 1 :     </label><label style="margin-left:20px">
        <input type="hidden" value="0" name="vrai1">
        <input name="vrai1" type="checkbox" value="1"> Vrai</label>
  		<input name="reponse1" class="form-control" type="text">
      <input type="file" name="file_1">
  	</div>
  	<div class="form-group">
  		<label>Réponse 2 :     </label><label style="margin-left:20px">
        <input type="hidden" value="0" name="vrai2">
        <input name="vrai2" type="checkbox" value="1"> Vrai</label>
  		<input name="reponse2" class="form-control" type="text">
      <input type="file" name="file_2">
  	</div>
  	<div class="form-group">
  		<label>Réponse 3 :     </label><label style="margin-left:20px">
        <input type="hidden" value="0" name="vrai3">
        <input name="vrai3" type="checkbox" value="1"> Vrai</label>
  		<input name="reponse3" class="form-control" type="text">
      <input type="file" name="file_3">
  	</div>
  	<div class="form-group">
  		<label>Réponse 4 :     </label><label style="margin-left:20px">
        <input type="hidden" value="0" name="vrai4">
        <input name="vrai4" type="checkbox" value="1"> Vrai</label>
  		<input name="reponse4" class="form-control" type="text">
      <input type="file" name="file_4">
  	</div>
    <div class="form-group">
  		<label>Image de Correction :     </label>
  		<input name="file" type="file">
  	</div>
    <div class="form-group">
  		<label>Commentaire de correction :     </label>
  		<input name="commentaire" class="form-control" type="text">
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>


<?php
}
?>





<?php
drawFooter();
 ?>
