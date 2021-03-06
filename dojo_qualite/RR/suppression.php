<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2><?php echo "R&R" ; ?></h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=dojo_qualite/RR/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au début</a>
<?php
}
else
{
  if(!$_SESSION['rr']){
    echo "<h2>R&amp;R</h2>";
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie.<a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{
  if(isset($_POST['reset'])){
    $query= $bdd -> query('SELECT * FROM qualite_RR_question');
    $i=0;
    while($Data = $query -> fetch()){
      $update=$bdd -> prepare('UPDATE qualite_RR_question SET ordre=? WHERE id=?');
      $update -> execute(array($i,$Data['id']));
      $i=$i+1;
    }
    success('Ordre réinitialisé','L\'ordre des questions a été réinitialisé.');
  }elseif(isset($_POST['supprimer'])){
    $query= $bdd -> prepare('SELECT * FROM qualite_RR_question WHERE id=?');
    $query -> execute(array($_POST['id']));
    $Data= $query -> fetch();
    if($Data['image'] != NULL){
      remove_file($bdd,$Data['image']);
    }
    $query = $bdd -> prepare('DELETE FROM qualite_RR_question WHERE id=?');
    $query -> execute(array($_POST['id']));
    $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre-1 WHERE ordre > ?');
    $query -> execute(array($_POST['ordre1']));
    success('Supprimé','La question a bien été supprimée.');
}elseif(isset($_POST['modifier'])){
  if($_FILES['file_1']['name'] != ""){
    $id1=upload($bdd,'file_1',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($id1 < 0){
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
    }else{
      $query = $bdd -> prepare('UPDATE qualite_RR_question SET image= :image WHERE id = :id');
      $query -> execute(array(
        'image' => $id1,
        'id' => $_POST['id']
      ));
      if($_POST['old_file_1'] != ""){
        remove_file($bdd,$_POST['old_file_1']);
      }
    }
  }
  $query = $bdd -> prepare('UPDATE qualite_RR_question SET type = :type,titre= :titre,question = :question, valide = :valide, ordre= :ordre WHERE id = :id');
  $query -> execute(array(
    'type' => $_POST['type'],
    'titre' => $_POST['titre'],
    'question' => $_POST['question'],
    'valide' => $_POST['vrai1'],
    'ordre' => $_POST['ordre2'],
    'id' => $_POST['id']
  ));
  if($_POST['ordre1'] > $_POST['ordre2']){
    $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre+1 WHERE ordre >= ? AND ordre < ? AND id <> ?');
    $query -> execute(array($_POST['ordre2'],$_POST['ordre1'],$_POST['id']));
  }
  if($_POST['ordre1'] < $_POST['ordre2']){
    $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre-1 WHERE ordre > ? AND ordre <= ? AND id <> ?');
    $query -> execute(array($_POST['ordre1'],$_POST['ordre2'],$_POST['id']));
  }
  if($query ==false){
    warning('Erreur','Les données entrées ne sont pas conformes.');
  }else{
    success('Modifié','La question a bien été mise à jour.');
  }
}


  $recherche = "";
  if (isset($_GET["recherche"])){
      $recherche = $_GET["recherche"];
  }
  ?>
  <h2>R&amp;R</h2>
  <style>
  .img-hover{
    visibility: hidden;
    position:fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index:1000;
    max-width: 80%;
    max-height:80%;
    border-radius: 4px;
    box-shadow: 2px 2px 4px grey;
    transition-delay: 0.1s;
  }
  .hov:hover .img-hover{
    visibility: visible;
    transition-delay: 1.5s;
  }
  </style>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Modification/Suppression</a>
  </div>

  <form class="form-inline">
    <div class="form-group">
      <label for="recherche">Recherche :</label>
      <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Question" value="<?php echo $recherche;?>">
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
  </form>
  <table class="table">
  <thead class="thead">
  <tr>
      <th>N°</th>
      <th>Titre</th>
      <th>Type</th>
      <th>Question</th>
      <th></th>
  </tr>
  </thead>
  <tbody>

<?php
$nb=0;
if(isset($_GET['nb'])){
$nb=$_GET['nb'];
}
  $query=$bdd -> prepare('SELECT * FROM qualite_RR_question WHERE (question LIKE :question or titre LIKE :titre) ORDER BY ordre LIMIT 20 OFFSET :nb');
  $query ->bindValue(':question','%'.$recherche.'%');
  $query ->bindValue(':titre','%'.$recherche.'%');
  $query ->bindValue(':nb',(int) $nb, PDO::PARAM_INT);
  $query ->execute();
  while($Data = $query->fetch()){
    $query2=$bdd -> prepare('SELECT * FROM files WHere id = ?');
    $query2 -> execute(array($Data['image']));
    $img=$query2 -> fetch(); ?>
    <tr class="hov">
      <td><?php echo $Data['ordre']; ?></td>
      <td><?php echo $Data['titre']; ?></td>
      <td><?php if($Data['type']){echo "MOI";}else{echo "MOD";} ?></td>
      <td><?php echo $Data['question'];?></td>
      <td><img class="img-hover" src="<?php echo $img['chemin']; ?>" alt="Image"><td>
      <td><a href="supprimer_question.php?id=<?php echo $Data['id']?>" class="btn btn-default pull-right">Modifier</a></td>
    </tr>
  <?php
} ?>
</tbody>

</table>
<?php

$test = $bdd->prepare('SELECT * FROM qualite_RR_question WHERE (question LIKE :question or titre LIKE :titre) LIMIT 1 OFFSET :nb');
$test ->bindValue(':question','%'.$recherche.'%');
$test ->bindValue(':titre','%'.$recherche.'%');
$test ->bindValue(':nb',(int) $nb+20, PDO::PARAM_INT);
$test->execute(); ?>
<form method="post" class="inline-form"> <?php
  if($nb > 19){    ?>
      <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb-20;?>" class="btn btn-default">Elements précédents</a>
    <?php
    }
    if($test -> fetch()){ ?>
    <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $nb+20;?>" class="btn btn-default">Elements suivants</a>
  <?php } ?>
    <button type="submit" onclick="return confirm('Voulez-vous reinitialiser l\'ordre des questions ?');" class="btn btn-default pull-right" name="reset">Réinitialiser l'ordre des questions</button>
    <span class="clear" style="clear: both; display: block;"></span>
  </form>
<?php
} }
drawFooter();
