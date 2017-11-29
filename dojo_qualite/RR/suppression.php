<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('RR');

if(empty($_SESSION['login']))
{ ?>
  <h2><?php echo "R&R" ; ?></h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/RR/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au début</a>
<?php
}
else
{
  if(isset($_POST['reset'])){
    $query= $bdd -> query('SELECT * FROM qualite_RR_question');
    $i=0;
    while($Data = $query -> fetch()){
      $update=$bdd -> prepare('UPDATE qualite_RR_question SET ordre=? WHERE id=?');
      $update -> execute(array($i,$Data['id']));
      $i=$i+1;
    } ?>
    <div class="alert alert-success">
        <strong>Ordre réinitialisé</strong>  -  L'ordre des questions a été réinitialisé.
    </div>
  <?php
  }
  if(isset($_POST['supprimer'])){
    $query= $bdd -> prepare('SELECT * FROM qualite_RR_question WHERE id=?');
    $query -> execute(array($_POST['id']));
    $Data= $query -> fetch();
    if($Data['reponse_1'] >= 0){
      remove_file($bdd,$Data['reponse_1']);
    }
    if($Data['reponse_2'] >= 0){
      remove_file($bdd,$Data['reponse_2']);
    }
    if($Data['reponse_3'] >= 0){
      remove_file($bdd,$Data['reponse_3']);
    }
    if($Data['reponse_4'] >= 0){
      remove_file($bdd,$Data['reponse_4']);
    }
    $query = $bdd -> prepare('DELETE FROM qualite_RR_question WHERE id=?');
    $query -> execute(array($_POST['id']));
    $query = $bdd -> prepare('UPDATE qualite_RR_question SET ordre=ordre-1 WHERE ordre > ?');
    $query -> execute(array($_POST['ordre1'])); ?>
    <div class="alert alert-success">
        <strong>Supprimé</strong>  -  La question a bien été supprimée.
    </div>
  <?php
}elseif(isset($_POST['modifier'])){
  $id1=upload($bdd,'file_1',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
  if($id1==-1){ echo "le fichier 1 n'a pas pu etre téléversé" ; }
  elseif ($id1==-2){echo "la taille du fichier 1 est trop grande";}
  elseif ($id1==-3){echo "le fichier 1 doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ;}
  else { $id2=upload($bdd,'file_2',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($id2==-1){ echo "le fichier 2 n'a pas pu etre téléversé" ; remove_file($bdd,$id1);}
    elseif ($id2==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); }
    elseif ($id2==-3){echo "le fichier 2 doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1);}
    else { $id3=upload($bdd,'file_3',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
      if($id3==-1){ echo "le fichier 3 n'a pas pu etre téléversé" ; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      elseif ($id3==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      elseif ($id3==-3){echo "le fichier  3 doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1); remove_file($bdd,$id2);}
      else {$id4=upload($bdd,'file_4',"../../ressources","R&R",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
        if($id3==-1){ echo "le fichier 4 n'a pas pu etre téléversé" ; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3);}
        elseif ($id3==-2){echo "la taille du fichier 1 est trop grande"; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3); }
        elseif ($id3==-3){echo "le fichier 4 doit posséder l'une des extensions suivantes: jpg, jpeg, gif, png " ; remove_file($bdd,$id1); remove_file($bdd,$id2); remove_file($bdd,$id3);}
        else {
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
  $query = $bdd -> prepare('UPDATE qualite_RR_question SET type = :type,titre= :titre,question = :question,reponse_1 = :reponse_1,reponse_2 = :reponse_2,reponse_3 = :reponse_3,reponse_4 = :reponse_4,corrige_1 = :corrige_1,corrige_2 = :corrige_2,corrige_3 = :corrige_3,corrige_4 = :corrige_4, ordre= :ordre WHERE id = :id');
  $query -> execute(array(
    'type' => $_POST['type'],
    'titre' => $_POST['titre'],
    'question' => $_POST['question'],
    'reponse_1' => $id1,
    'reponse_2' => $id2,
    'reponse_3' => $id3,
    'reponse_4' => $id4,
    'corrige_1' => $vrai1,
    'corrige_2' => $vrai2,
    'corrige_3' => $vrai3,
    'corrige_4' => $vrai4,
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
  if($query ==false){ ?>
    <div class="alert alert-danger">
        <strong>Erreur</strong>  -  Les données entrées ne sont pas conformes.
    </div>
  <?php }else{ ?>
        <div class="alert alert-success">
      <strong>Modifié</strong>  -  La question a bien été mise à jour.
  </div>
  <?php
}}}}}}



  $recherche = "";
  if (isset($_GET["recherche"])){
      $recherche = $_GET["recherche"];
  }
  ?>
  <h2><?php echo "R&R" ; ?></h2>
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

  ?>
    <tr>
      <td><?php echo $Data['ordre']; ?></td>
      <td><?php echo $Data['titre']; ?></td>
      <td><?php if($Data['type']){echo "Autre";}else{echo "MOD";} ?></td>
      <td><?php echo $Data['question'];?></td>
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
}

drawFooter();
