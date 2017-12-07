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
  <h2>R&amp;R</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/RR/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php" class="btn btn-default">R&amp;R</a>
<?php
}
else
{
  echo "<h2>R&R</h2>";

  if(!empty($_POST)){
    $erreur=false;
    if($_FILES['file_1']['name'] != ""){
      $id1=upload($bdd,'file_1',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    } else{
      $erreur=true;
      warning('Erreur','Vous n\'avez pas choisi de fichier');
    }

    if(!$erreur && ($id1 < 0)){
      $erreur=true;
      switch($id1){
        case -1:
          warning('Erreur','Le fichier n\'a pas pu etre téléversé.');
          break;
        case -2:
          warning('Erreur taille','La taille du fichier est trop grande.');
          break;
        case -3:
          warning('Erreur extension','L\'extension doit être l\'une des extensions suivantes: jpg, jpeg, gif, png.');
          break;
        default:
          warning('Erreur','Le fichier n\'a pas pu etre téléversé.');
      }
    }
    if(isset($_POST['ordre']) && !$erreur){
      if($lastOrdre >= $_POST['ordre']){
        $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre+1 WHERE ordre >= ? ');
        $query -> execute(array($_POST['ordre']));
      }
    }
    if(!$erreur){
      $query = $bdd -> prepare('INSERT INTO qualite_RR_question(type,titre,question,image,valide,ordre) VALUES (:type,:titre,:question,:image,:valide,:ordre)');
      $query -> execute(array(
        'type' => $_POST['type'],
        'titre' => $_POST['titre'],
        'question' => $_POST['question'],
        'image' => $id1,
        'valide' => $_POST['vrai1'],
        'ordre' => $_POST['ordre']
      ));
      if($query ==false){
        warning('Erreur','Les données entrées ne sont pas conformes.');
      }else{
        success('Ajouté','La question a bien été ajoutée.');
        $lastOrdre++;
      }
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
  	<input class="form-control" name="question" type="text" value="Cette image est-elle valide ?">
  	</div>
  	<div class="form-group">
  		<label>Image :     </label><label style="margin-left:20px">
        <input type="hidden" name="vrai1" value="0">
        <input name="vrai1" type="checkbox" value="1">Valide
      </label>
  		<input type="file" name="file_1" />
  	</div>
  	<input value="Ajouter" class="btn btn-default" type="submit">


  </form>



<?php
}
?>





<?php
drawFooter();
 ?>
