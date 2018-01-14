<?php
include_once "../needed.php";
include_once "needed.php";

drawHeader('logistique');
drawMenu('update');

echo "<h2>Mise à jour de la base de données des pièces</h2>";
echo "<div id='message' style='text-align:center; display:none;'><h1>Chargement...</h1><h4>Veuillez patienter. Cette opération peut prendre plusieurs minutes.</h4></div>";

if(empty($_SESSION['login'])){ ?>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=logistique/update.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="/index.php" class="btn btn-default">Accueil</a>
<?php }else{
  if(!$_SESSION['logistique']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{
function drawform(){ ?>
  <style>
  #first{
    margin-left: 50px;
    background-color: orange;
    padding:5px;
    border-radius:6px;
  }
  </style>
  <div class="row">
    <div class="col-md-6">
  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label >Fichier CSV Inventaire Magasin <span id="first">! A faire en premier !</span></label>
      <input type="file" name="inventaire">
      <p class="help-block">Fichier CSV limité à 15 Mo</p>
  </div>
    <button type="submit_inventaire" class="btn btn-default">Valider</button>
  </form>
</div>
<div class="col-md-6">
  <figure>
  <img src="ressources/inventaire.png" title="Format du fichier CSV" alt="Exemple de fichier" style="width:100%;">
  <figcaption style="font-size:25px; text-align:center; margin:5px;">Format du fichier</figcaption>
  </figure>
</div>
</div>
<hr>
<div class="row">
  <div class="col-md-6">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label >Fichier CSV Sebango</label>
        <input type="file" name="sebango">
        <p class="help-block">Fichier CSV limité à 15 Mo</p>
      </div>
      <button type="submit_sebango" class="btn btn-default">Valider</button>
    </form>
  </div>
  <div class="col-md-6">
    <figure>
      <img src="ressources/sebango.png" title="Format du fichier CSV" alt="Exemple de fichier" style="width:100%;">
      <figcaption style="font-size:25px; text-align:center; margin:5px;">Format du fichier</figcaption>
    </figure>
  </div>
</div>
<hr>
  <div class="row">
    <div class="col-md-6">
  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label >Fichier CSV E-kanban</label>
      <input type="file" name="kanban">
      <p class="help-block">Fichier CSV limité à 15 Mo</p>

  </div>
    <button type="submit_kanban" class="btn btn-default">Valider</button>
  </form>
</div>
<div class="col-md-6">
  <figure>
  <img src="ressources/kanban.png" title="Format du fichier CSV" alt="Exemple de fichier" style="width:100%;">
  <figcaption style="font-size:25px; text-align:center; margin:5px;">Format du fichier</figcaption>
  </figure>
</div>
</div>
<?php }

if(empty($_FILES)){
drawform();
}
else{
  if(isset($_FILES['inventaire'])){
    echo "<script>document.getElementById('message').style.display = 'block';</script>";
    ob_flush();
    flush();
    ob_flush();
    $ext = strtolower(substr(strrchr($_FILES['inventaire']['name'],'.'),1));
    if($ext != "csv"){
      warning('Mauvais fichier','Le fichier doit être un fichier CSV.');
      drawform();
    }elseif($_FILES['inventaire']['size'] >15700000){
      warning('Fichier trop gros',' Le fichier doit faire moins de 15 Mo.');
      drawform();
    }else{
      $fichier= fopen($_FILES['inventaire']['tmp_name'],'r');
      $entete = fgets($fichier);
      while(preg_match('/^[(?:,), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      while(preg_match('/^[(?:;), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      $champs = explode(',',$entete);
      if(sizeof($champs) < 2){
        $champs = explode(';',$champs[0]);
      }
      $erreur=0;
      foreach($champs as $key => $value){
        if(levenshtein(strtolower("article"),strtolower($value)) < 5 ){
          $article=$key;
        }
        if(levenshtein(strtolower("designation article"),strtolower($value)) < 5 ){
          $description=$key;
        }
        if(levenshtein(strtolower("emplacement"),strtolower($value)) < 5 ){
          $emplacement=$key;
        }
      }
      if(! isset($article) || ! isset($description) || ! isset($emplacement)){
        warning("Erreur","Problème de format de fichier.");
        drawForm();
      }else{
        while($ligne=fgets($fichier)){
          $tableau= explode(',',$ligne);
          if(sizeof($tableau) < 2){
            $tableau = explode(';',$tableau[0]);
          }
          if(preg_match('/^[(?:,), ]+$/',$ligne)){continue;}
          $query= $bdd -> prepare('SELECT * FROM logistique_pieces WHERE reference= ?');
          $query -> execute(array($tableau[$article]));
          $value=$query->fetch();
          if($value){
            if($tableau[$description]==''){$descr=$value['description'];}else{$descr=$tableau[$description];}
            if($tableau[$emplacement]==''){$adresse=$value['adresse'];}else{$adresse=$tableau[$emplacement];}
            $update = $bdd -> prepare('UPDATE logistique_pieces SET description= ?,adresse= ? WHERE reference=?');
            if( ! $update -> execute(array($descr,$adresse,$tableau[$article]))){
              $erreur += 1;
            }
          }else{
            $ajout = $bdd -> prepare('INSERT INTO logistique_pieces(reference,description,adresse) VALUES (?,?,?)');
            if(! $ajout -> execute(array($tableau[$article],$tableau[$description],$tableau[$emplacement]))){
              $erreur +=1;
            }
          }
        }

        fclose($fichier);
        if($erreur == 0){
          success('Modification effectuée','La base de données des pièces a bien été mise à jour.');
        }else{
          warning('Erreur','Il y a eu '.$erreur." erreurs.");
        }
        drawForm();
      }
    }
  }elseif(isset($_FILES['sebango'])){
    echo "<script>document.getElementById('message').style.display = 'block';</script>";
    ob_flush();
    flush();
    ob_flush();
    $ext = strtolower(substr(strrchr($_FILES['sebango']['name'],'.'),1));
    if($ext != "csv"){
      warning('Mauvais fichier','Le fichier doit être un fichier CSV.');
      drawform();
    }elseif($_FILES['sebango']['size'] >15700000){
      warning('Fichier trop gros',' Le fichier doit faire moins de 15 Mo.');
      drawform();
    }else{
      $fichier= fopen($_FILES['sebango']['tmp_name'],'r');
      $entete = fgets($fichier);
      while(preg_match('/^[(?:,), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      while(preg_match('/^[(?:;), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      $champs = explode(',',$entete);
      if(sizeof($champs) < 2){
        $champs = explode(';',$champs[0]);
      }
      $erreur=0;
      $erreur1=0;
      foreach($champs as $key => $value){
        if(levenshtein(strtolower("article"),strtolower($value)) < 5 ){
          $article=$key;
        }
        if(levenshtein(strtolower("sebango"),strtolower($value)) < 5 ){
          $sebango=$key;
        }
      }
      if(! isset($article) || ! isset($sebango)){
        warning("Erreur","Problème de format de fichier.");drawForm();
      }else{
        while($ligne=fgets($fichier)){
          $tableau= explode(',',$ligne);
          if(sizeof($tableau) < 2){
            $tableau = explode(';',$tableau[0]);
          }
          if(preg_match('/^[(?:,), ]+$/',$ligne)){continue;}
          $query= $bdd -> prepare('SELECT * FROM logistique_pieces WHERE reference= ?');
          $query -> execute(array($tableau[$article]));
          $value=$query->fetch();
          if($value){
            if($tableau[$sebango] == ''){$seb=$value['description'];}else{$seb=$tableau[$sebango];}
            $update = $bdd -> prepare('UPDATE logistique_pieces SET sebango = ? WHERE reference=?');
            if(! $update -> execute(array($seb,$tableau[$article]))){
              $erreur +=1;
            }
          }else{
            $erreur1+=1;
          }
        }

        fclose($fichier);
        if($erreur == 0 && $erreur1 == 0){
          success('Modification effectuée','Les sebangos ont bien été mis à jour.');
        }elseif($erreur == 0){
          success('Modification effectuée','Les sebangos ont bien été mis à jour. '.$erreur1." articles n'ont pas été trouvés.");
        }else{
          warning('Erreur','Il y a eu '.$erreur." erreurs. ".$erreur1." articles n'ont pas été trouvés.");
        }
        drawForm();
      }
    }
  }elseif(isset($_FILES['kanban'])){
    echo "<script>document.getElementById('message').style.display = 'block';</script>";
    ob_flush();
    flush();
    ob_flush();
    $ext = strtolower(substr(strrchr($_FILES['kanban']['name'],'.'),1));
    if($ext != "csv"){
      warning('Mauvais fichier','Le fichier doit être un fichier CSV.');
      drawform();
    }elseif($_FILES['kanban']['size'] >15700000){
      warning('Fichier trop gros',' Le fichier doit faire moins de 15 Mo.');
      drawform();
    }else{
      $fichier= fopen($_FILES['kanban']['tmp_name'],'r');
      $entete = fgets($fichier);
      while(preg_match('/^[(?:,), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      while(preg_match('/^[(?:;), ]+$/',$entete)){
        $entete = fgets($fichier);
      }
      $champs = explode(',',$entete);
      if(sizeof($champs) < 2){
        $champs = explode(';',$champs[0]);
      }
      $erreur=0;
      $erreur1=0;
      foreach($champs as $key => $value){
        if(levenshtein(strtolower("article"),strtolower($value)) < 5 ){
          $article=$key;
        }
        if(levenshtein(strtolower("id de l'ekanban"),strtolower($value)) < 5 ){
          $kanban=$key;
        }
        if(levenshtein(strtolower("ligne de production"),strtolower($value)) < 5 ){
          $lign=$key;
        }
        if(levenshtein(strtolower("ligne"),strtolower($value)) < 5 ){
          $lign=$key;
        }
        if(levenshtein(strtolower("qté d'articles"),strtolower($value)) < 5 ){
          $qte=$key;
        }
        if(levenshtein(strtolower("quantité"),strtolower($value)) < 5 ){
          $qte=$key;
        }
      }
      if(! isset($article) || ! isset($kanban) || ! isset($lign) || ! isset($qte) ){
        warning("Erreur","Problème de format de fichier.");drawForm();
      }else{
        while($ligne=fgets($fichier)){
          $tableau= explode(',',$ligne);
          if(sizeof($tableau) < 2){
            $tableau = explode(';',$tableau[0]);
          }
          if(preg_match('/^[(?:,), ]+$/',$ligne)){continue;}
          if(preg_match('/^[(?:;), ]+$/',$ligne)){continue;}
          $query= $bdd -> prepare('SELECT * FROM logistique_pieces WHERE reference= ?');
          $query -> execute(array($tableau[$article]));
          $value=$query->fetch();
          if($value){
            $q = $bdd -> prepare('SELECT * FROM logistique_e_kanban WHERE piece = ?');
            $q -> execute(array($value['id']));
            if($Data = $q -> fetch()){
              if($tableau[$kanban] == ''){$k=$Data['kanban'];}else{$k="S".substr($tableau[$kanban],4);}
              if($tableau[$lign] == ''){$li=$Data['ligne'];}else{$li=$tableau[$lign];}
              if($tableau[$qte] == ''){$quantite=$Data['quantite'];}else{$quantite=$tableau[$qte];}
              $update = $bdd -> prepare('UPDATE logistique_e_kanban SET code_barres = ?, quantite = ?, ligne = ? WHERE piece =?');
              if(! $update -> execute(array($k,$quantite,$li,$value['id']))){
                $erreur +=1;
              }
            }else{
              $k=$tableau[$kanban];
              $add = $bdd -> prepare('INSERT INTO logistique_e_kanban(code_barres, quantite,ligne, piece) VALUES (?,?,?,?)');
              if(! $add -> execute(array($k,$tableau[$qte],$tableau[$lign],$value['id']))){
                $erreur +=1;
              }
            }
          }else{
            $erreur1+=1;
          }
        }

        fclose($fichier);
        if($erreur == 0 && $erreur1 == 0){
          success('Modification effectuée','Les e-Kanban ont bien été mis à jour.');
        }elseif($erreur == 0){
          success('Modification effectuée','Les e-Kanban ont bien été mis à jour. '.$erreur1." articles n'ont pas été trouvés.");
        }else{
          warning('Erreur','Il y a eu '.$erreur." erreurs. ".$erreur1." articles n'ont pas été trouvés.");
        }
        drawForm();
      }
    }
  }
}}}


echo "<script>document.getElementById('message').style.display = 'none';</script>";

drawFooter(); ?>
