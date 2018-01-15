
<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

if(!isset($_GET['id'])){ ?>
  <h2>LaunchBoard</h2>
  <h4>Erreur... Votre session est inconnue.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard">Retourner au LaunchBoard</a>
<?php }else{
  if(isset($_POST['delete_kickoff'])){
    remove_file($bdd,$_POST['kickoff']);
    $q = $bdd -> prepare('UPDATE launchboard SET kickoff = NULL WHERE id= ?');
    if($q -> execute(array($_GET['id']))){
      success('Supprimé','Le kickoff a bien été supprimé.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['delete_launchbook'])){
    remove_file($bdd,$_POST['launchbook']);
    $q = $bdd -> prepare('UPDATE launchboard SET launchbook = NULL WHERE id= ?');
    if($q -> execute(array($_GET['id']))){
      success('Supprimé','Le launchbook a bien été supprimé.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['pptl'])){
    $q = $bdd -> prepare('UPDATE launchboard SET profil = ? WHERE id = ?');
    if($q -> execute(array($_POST['profil'],$_GET['id']))){
      success('Modifié','Le PPTL a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(! empty($_FILES)){
    if(isset($_FILES['kickoff']) && $_FILES['kickoff']['name'] != ""){
      $kickoff=upload($bdd,'kickoff',"../../ressources","launchboard",50485760,array( 'ppt' , 'pptx' , 'PPT' , 'PPTX' ));
      if($kickoff < 0){
        warning('Erreur','Le fichier n\'a pas pu être importé.');
      }else{
        $q = $bdd -> prepare('UPDATE launchboard SET kickoff = ? WHERE id = ?');
        if($q -> execute(array($kickoff,$_GET['id']))){
          success('Ajouté','Le kickoff a bien été ajouté.');
        }else{
          warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
        }
      }
    }
    if(isset($_FILES['launchbook']) && $_FILES['launchbook']['name'] != ""){
      $launchbook=upload($bdd,'launchbook',"../../ressources","launchboard",50485760,array( 'xls' , 'xlsx' , 'XLS' , 'XLSX' ));
      if($launchbook < 0){
        warning('Erreur','Le fichier n\'a pas pu être importé.');
      }else{
        $q = $bdd -> prepare('UPDATE launchboard SET launchbook = ? WHERE id = ?');
        if($q -> execute(array($launchbook,$_GET['id']))){
          success('Ajouté','Le launchbook a bien été ajouté.');
        }else{
          warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
        }
      }
    }
    if(isset($_FILES['img']) && $_FILES['img']['name'] != ""){
      $img=upload($bdd,'img',"../../ressources","launchboard",50485760,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG'  ));
      if($img < 0){
        warning('Erreur','Le fichier n\'a pas pu être importé.');
      }else{
        $q = $bdd -> prepare('UPDATE launchboard SET img_presentation = ? WHERE id = ?');
        if($q -> execute(array($img,$_GET['id']))){
          success('Ajouté','L\'image a bien été ajoutée.');
        }else{
          warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
        }
      }
    }
  }
    $query = $bdd -> prepare('SELECT * FROM launchboard JOIN profil ON profil.id=launchboard.profil WHERE launchboard.id = ?');
    $query -> execute(array($_GET['id']));
    $Data = $query -> fetch();

?>
<h2 style="margin-bottom:10px;">Projet : <?php echo $Data['titre']; ?></h2>
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Projet</a>
  <a href="statistiques.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu" >Statistiques</a>
</div>
<div class="row" style="background-color: #efefef; margin-bottom:20px; padding: 10px; border-radius: 6px;">
  <div class="col-md-6">
    <h4>PPTL : <?php echo $Data['nom']; ?> <?php echo $Data['prenom']; ?>
      <div class="btn btn-default pull-right" data-toggle="modal" data-target="#modal">Modifier le PPTL</div>
</h4>
    <h4>Code : <?php echo $Data['code']; ?></h4>
  </div>
  <div class="col-md-6">
    <h4>Description :</h4>
    <p><?php echo $Data['description']; ?></p>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <h4>Client : <?php echo $Data['client']; ?></h4>
    <h4>Fichiers :</h4>
      <?php
      if(! is_null($Data['kickoff'])){
        $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
        $img -> execute(array($Data['kickoff']));
        $file = $img -> fetch(); ?>
          <form method="post">
            <input type="hidden" name="kickoff" value="<?php echo $Data['kickoff']; ?>">
            <a href="<?php echo $file['chemin']; ?>" class="btn btn-default">Télécharger le kickoff</a>
            <input type="submit" name="delete_kickoff" class="btn btn-default" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer le kickoff ?')">
          </form>
        <?php
      }else{ ?>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="kickoff">
          <input type="submit" class="btn btn-default" value="Ajouter le kickoff (ppt)">
        </form>
        <?php
      }
      echo "<br>";
      if(! is_null($Data['launchbook'])){
        $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
        $img -> execute(array($Data['launchbook']));
        $file = $img -> fetch(); ?>
        <form method="post" >
          <input type="hidden" name="launchbook" value="<?php echo $Data['launchbook']; ?>">
          <a href="<?php echo $file['chemin']; ?>" class="btn btn-default">Télécharger le launchbook</a>
          <input type="submit" name="delete_launchbook" class="btn btn-default" onclick="return confirm('Êtes-vous sûr de vouloir supprimer le launchbook ? ')" value="Supprimer">
        </form>
        <?php
      }
      else{ ?>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="launchbook">
          <input type="submit" class="btn btn-default" value="Ajouter le launchbook">
        </form>
        <?php
      }
      ?>
  </div>
  <div class="col-md-6">
    <?php
    if(! is_null($Data['img_presentation'])){
      $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
      $img -> execute(array($Data['img_presentation']));
      $img = $img -> fetch();
      echo "<img src=".$img['chemin']." style='width:100%;border-radius: 6px;' alt='Image'>";
    }else{ ?>
      <form method="post" enctype="multipart/form-data">
        <input type="file" name="img">
        <input type="submit" class="btn btn-default" value="Ajouter l'image">
      </form>
    <?php
    }
    ?>
  </div>
</div>
<div id="modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le PPTL</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <select class="form-control" name="profil" <?php echo $Data['profil']; ?>>
            <?php
            $profil = $bdd -> query('SELECT * FROM profil');
            while($personne = $profil -> fetch()){ ?>
              <option value="<?php echo $personne['id']; ?>" <?php if($Data['profil'] == $personne['id']){echo "selected";} ?>><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
          <?php  } ?>
          </select>
          <br>
          <input type="submit" name="pptl" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le PPTL ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<br><br>
<form method="post" action="index.php">
  <a href="index.php" class="btn btn-default">Retour</a>
  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" >
  <?php if($Data['archive']){
    echo '<input type="submit" class="btn btn-default pull-right" value="Restaurer" name="desarchive">';
  }else{
    echo '<input type="submit" class="btn btn-default pull-right" value="Archiver" name="archive">';
  }
  ?>
</form>
<?php
}
drawFooter();
 ?>
