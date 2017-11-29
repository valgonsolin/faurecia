<?php
include_once "../needed.php";
include_once "needed.php";

drawHeader('logistique');
drawMenu('update');

echo "<h2>Mise à jour de la base de données des pièces</h2>";
if(empty($_SESSION['login'])){ ?>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=logistique/update.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="/index.php" class="btn btn-default">Accueil</a>
<?php }else{
function drawform(){ ?>
  <div class="row">
    <div class="col-md-6">
  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label >Fichier CSV de mise à jour</label>
      <input type="file" name="fichier">
      <p class="help-block">Fichier CSV limité à 15 Mo</p>

  </div>
    <button type="submit" class="btn btn-default">Valider</button>
  </form>
</div>
<div class="col-md-6">
  <figure>
  <img src="../images/example.png" title="Format du fichier CSV" alt="Exemple de fichier" style="width:100%;">
  <figcaption style="font-size:25px; text-align:center; margin:5px;">Format du fichier, sans accents</figcaption>
  </figure>
</div>
</div>
<?php }

if(empty($_FILES)){
drawform();
}
else{
  $ext = substr(strrchr($_FILES['fichier']['name'],'.'),1);
  if($ext != "csv"){ ?>
    <div class="alert alert-danger">
      <strong>Mauvais fichier</strong> - Le fichier doit être un fichier CSV.
    </div> <?php
    drawform();
  }elseif($_FILES['fichier']['size'] >15700000){ ?>
    <div class="alert alert-danger">
      <strong>Fichier trop gros</strong> - Le fichier doit être un fichier CSV.
    </div> <?php
    drawform();
  }else{
    $fichier= fopen($_FILES['fichier']['tmp_name'],'r');
    while($ligne=fgets($fichier)){
      $tableau= explode(',',$ligne);
      if(!strcasecmp($tableau[0],"sebango") && !strcasecmp($tableau[1],"reference") && !strcasecmp($tableau[2],"description")){continue;}
      $query= $bdd -> prepare('SELECT * FROM logistique_pieces WHERE reference= ?');
      $query -> execute(array($tableau[1]));
      $value=$query->fetch();
      if($value){
        if($tableau[0]==''){$sebango=$value['sebango'];}else{$sebango=$tableau[0];}
        if($tableau[2]==''){$description=$value['description'];}else{$description=$tableau[2];}
        if($tableau[3]==''){$adresse=$value['adresse'];}else{$adresse=$tableau[3];}
        $update = $bdd -> prepare('UPDATE logistique_pieces SET sebango= ?, description= ?,adresse= ? WHERE reference=?');
        $update -> execute(array($sebango,$description,$adresse,$tableau[1]));
      }else{
        $ajout = $bdd -> prepare('INSERT INTO logistique_pieces(sebango,reference,description,adresse) VALUES (?,?,?,?)');
        $ajout -> execute(array($tableau[0],$tableau[1],$tableau[2],$tableau[3]));
      }

    }

    fclose($fichier); ?>
    <div class="alert alert-success">
        <strong>Modification effectuée</strong>  -  La base de données a bien été mise à jour.
    </div>
    <a href="/index.php" class="btn btn-default">Accueil</a>
    <a href="/deconnexion.php" class="btn btn-default">Déconnexion</a>
    <?php
  }
}}








drawFooter(); ?>
