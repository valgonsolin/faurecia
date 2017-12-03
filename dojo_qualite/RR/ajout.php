<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('R&R');

$lastOrdre= -1;
$query= $bdd -> query('SELECT * FROM qualite_RR_question ORDER BY ordre DESC LIMIT 1');
while ($Data = $query->fetch()) {
$lastOrdre= $Data['ordre'];
}

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
  echo "<h2>R&R</h2>";

  if(!empty($_POST)){

    if(isset($_POST['ordre'])){
      if($lastOrdre >= $_POST['ordre']){
        $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre+1 WHERE ordre >= ? ');
        $query -> execute(array($_POST['ordre']));
      }
    }
    $query = $bdd -> prepare('INSERT INTO qualite_RR_question(type,titre,question,corrige_1,corrige_2,corrige_3,corrige_4,ordre) VALUES (:type,:titre,:question,:corrige_1,:corrige_2,:corrige_3,:corrige_4,:ordre)');
    $query -> execute(array(
      'type' => $_POST['type'],
      'titre' => $_POST['titre'],
      'question' => $_POST['question'],
      'corrige_1' => $_POST['vrai1'],
      'corrige_2' => $_POST['vrai2'],
      'corrige_3' => $_POST['vrai3'],
      'corrige_4' => $_POST['vrai4'],
      'ordre' => $_POST['ordre']
    ));
    $id= $bdd -> lastInsertId();
    if($query ==false){
      warning('Erreur','Les données entrées ne sont pas conformes.');
    }else{
      success('Ajouté','La question a bien été ajoutée.');
      $lastOrdre++;
    }

    if($_FILES['file_1']['name'] != ""){
      $id1=upload($bdd,'file_1',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    }else{
      $id1=-4;
    }
    if($_FILES['file_2']['name'] != ""){
      $id2=upload($bdd,'file_2',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    }else{
      $id2=-4;
    }
    if($_FILES['file_3']['name'] != ""){
      $id3=upload($bdd,'file_3',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    }else{
      $id3=-4;
    }
    if($_FILES['file_4']['name'] != ""){
      $id4=upload($bdd,'file_4',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    }else{
      $id4=-4;
    }
    if($id1 < 0){
      switch($id1){
        case -1:
          warning('Erreur','Le fichier 1 n\'a pas pu etre téléversé.');
          break;
        case -2:
          warning('Erreur taille','La taille du fichier 1 est trop grande.');
          break;
        case -3:
          warning('Erreur extension','L\'extension doit être l\'une des extensions suivantes: jpg, jpeg, gif, png.');
          break;
        case -4:
          break;
        default:
          warning('Erreur','Le fichier 1 n\'a pas pu etre téléversé.');
      }
    }else{
      $query = $bdd -> prepare('UPDATE qualite_RR_question SET reponse_1= :reponse_1 WHERE id = :id');
      $query -> execute(array(
        'reponse_1' => $id1,
        'id' => $id
      ));
    }
    if($id2 < 0){
      switch($id2){
        case -1:
          warning('Erreur','Le fichier 2 n\'a pas pu etre téléversé.');
          break;
        case -2:
          warning('Erreur taille','La taille du fichier 2 est trop grande.');
          break;
        case -3:
          warning('Erreur extension','L\'extension doit être l\'une des extensions suivantes: jpg, jpeg, gif, png.');
          break;
        case -4:
          break;
        default:
          warning('Erreur','Le fichier 2 n\'a pas pu etre téléversé.');
      }
    }else{
      $query = $bdd -> prepare('UPDATE qualite_RR_question SET reponse_2= :reponse_2 WHERE id = :id');
      $query -> execute(array(
        'reponse_2' => $id2,
        'id' => $id
      ));
    }
    if($id3 < 0){
      switch($id3){
        case -1:
          warning('Erreur','Le fichier 3 n\'a pas pu etre téléversé.');
          break;
        case -2:
          warning('Erreur taille','La taille du fichier 3 est trop grande.');
          break;
        case -3:
          warning('Erreur extension','L\'extension doit être l\'une des extensions suivantes: jpg, jpeg, gif, png.');
          break;
        case -4:
          break;
        default:
          warning('Erreur','Le fichier 3 n\'a pas pu etre téléversé.');
      }
    }else{
      $query = $bdd -> prepare('UPDATE qualite_RR_question SET reponse_3= :reponse_3 WHERE id = :id');
      $query -> execute(array(
        'reponse_3' => $id3,
        'id' => $id
      ));
    }
    if($id4 < 0){
      switch($id4){
        case -1:
          warning('Erreur','Le fichier 4 n\'a pas pu etre téléversé.');
          break;
        case -2:
          warning('Erreur taille','La taille du fichier 4 est trop grande.');
          break;
        case -3:
          warning('Erreur extension','L\'extension doit être l\'une des extensions suivantes: jpg, jpeg, gif, png.');
          break;
        case -4:
          break;
        default:
          warning('Erreur','Le fichier 4 n\'a pas pu etre téléversé.');
      }
    }else{
      $query = $bdd -> prepare('UPDATE qualite_RR_question SET reponse_4= :reponse_4 WHERE id = :id');
      $query -> execute(array(
        'reponse_4' => $id4,
        'id' => $id
      ));
    }
  }

  ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <form method="post" style="margin-top:20px;"  enctype="multipart/form-data">
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
        <input type="hidden" name="vrai1" value="0">
        <input name="vrai1" type="checkbox" value="1"> Vrai</label>
  		<input type="file" name="file_1" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 2 :     </label><label style="margin-left:20px">
        <input type="hidden" name="vrai2" value="0">
        <input name="vrai2" type="checkbox" value="1"> Vrai</label>
  		<input type="file" name="file_2" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 3 :     </label><label style="margin-left:20px">
        <input type="hidden" name="vrai3" value="0">
        <input name="vrai3" type="checkbox" value="1"> Vrai</label>
  		<input type="file" name="file_3" />
  	</div>
  	<div class="form-group">
  		<label>Réponse 4 :     </label><label style="margin-left:20px">
        <input type="hidden" name="vrai4" value="0">
        <input name="vrai4" type="checkbox" value="1"> Vrai</label>
  		<input type="file" name="file_4" />
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>



<?php
}
?>





<?php
drawFooter();
 ?>
