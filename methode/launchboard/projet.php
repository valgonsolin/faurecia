
<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

function color($field){
  global $Data;
  $class="";
  if( (($Data['profil'] == $_SESSION['id']) || ($_SESSION['launchboard'] && ! $Data[$field] && ! is_null($Data[$field.'_r'])) ) && (! is_null($Data[$field.'_f']))){
    $class="clickable";
    echo "data-toggle='modal' data-target='#".$field."_r'";
  }
  if(! is_null($Data[$field.'_r'])){
    if(strtotime($Data[$field.'_r']) <= strtotime($Data[$field.'_f']) && $Data[$field]){
      echo "class='green ".$class."green' title='Validé'";
    }elseif($Data[$field]){
      echo "class='red ".$class."red' title='Validé'";
    }else{
      echo "class='notvalid ".$class."yellow' title='Non validé'";
    }
  }else{
    echo "class='".$class."'";
  }
}
function forecast($field){
  global $_SESSION;
  global $Data;
  if($Data['profil'] == $_SESSION['id']){
    echo "data-toggle='modal' class='clickable' data-target='#".$field."'";
  }
}
function choose($field){
  global $_SESSION;
  global $Data;
  if($Data['profil'] == $_SESSION['id']){?>
  <div id="<?php echo $field; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modifier la date</h4>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="form-group">
              <input type="date" name="<?php echo $field; ?>value" class="form-control" value="<?php echo $Data[$field]; ?>">
              <input type="submit" name="<?php echo $field; ?>" value="Valider" class="btn btn-default">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
  }elseif($_SESSION['launchboard']){ ?>
  <div id="<?php echo $field; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Valider la date ?</h4>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="form-group">
              <input type="submit" name="<?php echo $field."val"; ?>" value="Valider" class="btn btn-default" style="display:block; margin: 0 auto;">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> <?php 
 }
}
function treatForm($field){
  global $_SESSION;
  global $fields;
  global $bdd;
  global $Data;
  if($Data['profil'] == $_SESSION['id']){
    if(isset($_POST[$field.'_f'])){
      $q = $bdd -> prepare('UPDATE launchboard SET '.$field.'_f = ? WHERE id = ?');
      if($q -> execute(array($_POST[$field.'_fvalue'],$_GET['id']))){
        success('Modifié','La date a bien été modifiée.');
      }else{
        warning('Erreur','Veuillez réessayer.');
      }
    }
    if(isset($_POST[$field.'_r'])){
      $q = $bdd -> prepare('UPDATE launchboard SET '.$field.'_r = ?, '.$field.'= 0 WHERE id = ?');
      if($q -> execute(array($_POST[$field.'_rvalue'],$_GET['id']))){
        success('Modifié','La date a bien été modifiée.');
      }else{
        warning('Erreur','Veuillez réessayer.');
      }
    }
  }elseif($_SESSION['launchboard']){
    if(isset($_POST[$field.'_rval'])){
      $q = $bdd -> prepare('UPDATE launchboard SET '.$field.'= 1 WHERE id = ?');
      if($q -> execute(array($_GET['id']))){
        success('Validée','La date a bien été validée.');
        $Data[$field]=1;
      }else{
        warning('Erreur','Veuillez réessayer.');
      }
       //retablissement pourcentage
      if(substr($field,0,1)=="2"){
        $total=0;
        $avancement=0;
        foreach ($fields as $key => $value) {
          if(! is_null($Data[$value."_f"])){
            $total +=1;
            if($Data[$value]){
              $avancement +=1;
            }
          }
        }
        if($total >0){
          $pourcentage = round((floatval($avancement/$total)*100),2);
        }else{
          $pourcentage =0;
        }
        echo $pourcentage;
        $q = $bdd -> prepare('INSERT INTO evolution_projet(id_projet,pourcentage,date) VALUES (?,?,NOW())');
        $q -> execute(array($_GET['id'],$pourcentage));
      }
    }
  }
}

if(!isset($_GET['id'])){ ?>
  <h2>LaunchBoard</h2>
  <h4>Erreur... Votre session est inconnue.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard">Retourner au LaunchBoard</a>
  <?php }else{
    if(! isset($_SESSION['login'])){ ?>
        <h2>LaunchBoard</h2>
  <h4>Vous devez etre connecté pour accéder à cette partie.</h4>
  <a class="btn btn-default" href="<?php echo $url; ?>/methode/launchboard">Retourner au LaunchBoard</a>
  <a class="btn btn-default" href="<?php echo $url; ?>/moncompte/identification.php">Connexion</a>
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
     if(isset($_POST['delete_makeorbuy'])){
       remove_file($bdd,$_POST['makeorbuy']);
       $q = $bdd -> prepare('UPDATE launchboard SET makeorbuy = NULL WHERE id= ?');
       if($q -> execute(array($_GET['id']))){
         success('Supprimé','Le Make or Buy a bien été supprimé.');
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
     if(isset($_POST['pm_check'])){
    $q = $bdd -> prepare('UPDATE launchboard SET pm = ? WHERE id = ?');
    if($q -> execute(array($_POST['pm'],$_GET['id']))){
      success('Modifié','Le PM a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['launchbook'])){
    $q = $bdd -> prepare('UPDATE launchboard SET launchbook = ? WHERE id = ?');
    if($q -> execute(array($_POST['launchbook_link'],$_GET['id']))){
      success('Modifié','Le launchbook a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['descr'])){
    $descr="";
    foreach($_POST['description'] as $key => $value) {
      $descr.=$value." / ";
    }
    $descr=substr($descr,0,sizeof($descr)-3);
    $q = $bdd -> prepare('UPDATE launchboard SET description = ? WHERE id = ?');
    if($q -> execute(array($descr,$_GET['id']))){
      success('Modifiée','La description a été modifiée.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['sop'])){
    $q = $bdd -> prepare('UPDATE launchboard SET initial_date = ? WHERE id = ?');
    if($q -> execute(array($_POST['sop_date'],$_GET['id']))){
      success('Modifiée','Le SOP a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['helios'])){
    $q = $bdd -> prepare('UPDATE launchboard SET link_helios = ? WHERE id = ?');
    if($q -> execute(array($_POST['link_helios'],$_GET['id']))){
      success('Modifiée','Le lien a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['plr'])){
    $q = $bdd -> prepare('UPDATE launchboard SET link_plr = ? WHERE id = ?');
    if($q -> execute(array($_POST['link_plr'],$_GET['id']))){
      success('Modifiée','Le lien a été modifié.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  
  if(isset($_POST['ptg'])){
    $q = $bdd -> prepare('INSERT INTO evolution_projet(id_projet,pourcentage,date) VALUES (?,?,NOW())');
    if($q -> execute(array($_GET['id'],$_POST['pourcentage']))){
      success('Modifiée','Le pourcentage a été modifiée.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['ajout_equipe'])){
    foreach($_POST['equipe'] as $prof) {
      $q = $bdd ->prepare('INSERT INTO equipe(id_projet,id_profil) VALUES (?,?)');
      $q-> execute(array($_GET['id'],$prof));
    }
      success('Modifiée','L\'équipe a été modifiée.');
  }
  if(isset($_POST['remove_equipe'])){
    $q = $bdd -> prepare('DELETE FROM equipe WHERE id = ?');
    if($q -> execute(array($_POST['remove']))){
      success('Modifiée','L\'équipe a été modifiée.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  //Pourcentages
  if(isset($_POST['capacitaire_click'])){
    $q = $bdd -> prepare('UPDATE launchboard SET capacitaire= ? WHERE id = ?');
    if($q -> execute(array($_POST['capacitaire'],$_GET['id']))){
      success('Modifiée','Le pourcentage a été modifiée.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  foreach (['me'=>'ME','hsep'=>'HSE','quality'=>'QUALITY','log'=>'LOG/PC&amp;L','training'=>'Training/EE','supplier'=>'Suppliers'] as $key => $value){
    if(isset($_POST[$key.'_click'])){
      $q = $bdd -> prepare('UPDATE launchboard SET '.$key.'= ? WHERE id = ?');
      if($q -> execute(array($_POST[$key],$_GET['id']))){
        success('Modifiée','Le '.$value.' a été modifiée.');
      }else{
        warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
      }
    }
  }
  //Files
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
    if(isset($_FILES['makeorbuy']) && $_FILES['makeorbuy']['name'] != ""){
      $makeorbuy=upload($bdd,'makeorbuy',"../../ressources","launchboard",50485760,array( 'ppt' , 'pptx' , 'PPT' , 'PPTX' ));
      if($makeorbuy < 0){
        warning('Erreur','Le fichier n\'a pas pu être importé.');
      }else{
        $q = $bdd -> prepare('UPDATE launchboard SET makeorbuy = ? WHERE id = ?');
        if($q -> execute(array($makeorbuy,$_GET['id']))){
          success('Ajouté','Le Make or Buy a bien été ajouté.');
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
    if($query -> execute(array($_GET['id'])))
    $Data = $query -> fetch();
    $fields = array('2tct','2capacity','2equip','2pfmea','2mvp','2layout','2master','2pack','3equip','3pack','3supplier','3checklist1','3pt','3checklist2','3mpt','3samples','4checklist','4empt');
    foreach ($fields as $key => $value) {
      treatForm($value);
    }
    
    $query = $bdd -> prepare('SELECT * FROM launchboard JOIN profil ON profil.id=launchboard.profil WHERE launchboard.id = ?');
    $query -> execute(array($_GET['id']));
    $Data = $query -> fetch();
?>
<style>
  .clickable{
    border-radius:4px;
  }
  .couleur{
    margin: 10px;
    width: 400px;
    height: 20px;
    border-radius:3px;
    border-color: #ccc;
    border-width: 1px;
    border-style: solid;
  }
  .clickable:hover{
    background-color:#e0e0e0;
  }
  .clickablered:hover{
    background-color: #df4529;
  }
  .clickablegreen:hover{
    background-color: #73d273;
  }
  .clickableyellow:hover{
    background-color: #d0b41c;
  }
  .onglet
  {
     display:inline-block;
     margin-left:10px;
     margin-right:10px;
     padding:10px;
     border-radius: 6px 6px 0 0;
     cursor:pointer;
   }
   .onglet_0
   {
     background-color: #e0e0e0;
   }
   .onglet_1
   {
     background:#efefef;
  }
   .contenu_onglet
   {
     display:none;
   }
   .conteneur{
     background-color: #efefef;
     margin-bottom:20px;
     padding: 10px;
     border-radius: 6px;
   }
   .gate table, .gate th, .gate td{
     border: 1px solid #d0d0d0;
     text-align: center;
   }
   .gate th,.gate td{
     width:6%;
     padding: 5px;
   }
   .gate table{
     width:100%;
   }
   .green{
     background-color: #90EE90;
   }
   .red{
     background-color: #FF6347;
   }
   .notvalid{
     background-color: #FFD700;
   }
   td{
     font-size: 80%;
   }
</style>
<h2 style="margin-bottom:10px;">Projet : <?php echo $Data['titre']; ?></h2>
<div class="boutons_nav" style="display: flex; justify-content: center;">
  <a href="projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu bouton_nav_selected" style="margin-right:20%">Projet</a>
  <a href="statistiques_projet.php?id=<?php echo $_GET['id']; ?>" class="bouton_menu" >Statistiques</a>
</div>
<div class="row conteneur">
  <div class="col-md-6">
    <h4>PPTL : <?php echo $Data['nom']; ?> <?php echo $Data['prenom']; ?>
      <?php if($_SESSION['launchboard']){ ?>
      <div class="btn btn-default pull-right" data-toggle="modal" data-target="#modal">Modifier le PPTL</div><?php } ?>
</h4>
    <h4>Code : <?php echo $Data['code']; ?></h4>
    <h4>SOP :<?php if(!is_null($Data['initial_date'])){ echo date('d/m/y',strtotime($Data['initial_date']));} ?><?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
          <div class="btn btn-default pull-right" data-toggle="modal" data-target="#sop">Modifier</div><?php } ?></h4>
  </div>
  <div class="col-md-6">
      <h4>PM : <?php echo $Data['pm']; ?>
      <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard']){ ?>
      <div class="btn btn-default pull-right" data-toggle="modal" data-target="#pm">Modifier le PM</div><?php } ?>
    </h4>
    <h4>Description :    <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
          <div class="btn btn-default pull-right" data-toggle="modal" data-target="#description">Modifier la description</div><?php } ?></h4>
    <p><?php echo $Data['description']; ?></p>
  </div>
</div>
<div class="row">
  <?php
  //Calcul avancement et retard
  $total=0;
  $avancement=0;
  $retard=0;
  $c = 3;
  foreach ($fields as $key => $value) {
    if(! is_null($Data[$value."_f"])){
      $total +=1;
      if($Data[$value]){
        $avancement +=1;
        if(strtotime($Data[$value."_f"]) < strtotime($Data[$value."_r"])){
          $retard +=1;
        }
      }
    }
  }  
  if($total <= 0){$total=1;}
    //on recupere la gate
    $gate="2B";
    if($Data['2tct'] && $Data['2capacity'] && $Data['2equip'] && $Data['2pfmea'] && $Data['2mvp'] && $Data['2layout'] && $Data['2master'] && $Data['2pack']){
      $gate="3";
      if($Data['3equip'] && $Data['3pack'] && $Data['3supplier'] && $Data['3checklist1'] && $Data['3pt'] && $Data['3checklist2'] && $Data['3mpt'] && $Data['3samples']){
        $gate="4";
      }
    }
    //on recupere le pourcentage
    $ptg = $bdd -> prepare('SELECT * FROM evolution_projet WHERE id_projet = ? ORDER BY date DESC LIMIT 1' );
    $ptg -> execute(array($_GET['id']));
    if($res = $ptg -> fetch()){
      $pourcentage=$res['pourcentage'];
      $date = strtotime($res['date']);
    }else{
      $pourcentage=0;
      $date= strtotime("now");
    }

    if($pourcentage < 75){
      $couleur = "#da090d";
    }elseif($pourcentage < 85){
      $couleur = "#FF9C00";
    }else{
      $couleur = "#2b669a";
    }
    if($pourcentage >5){
      $text="white";
    }else{
      $text="black";
    }
    ?>
    <div class="col-md-6">
      <h4>Pourcentage : <?php if(isset($date)){ echo " <small>(".date('j/m/y', $date).")</small>"; }
      if((($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ) && $gate != "2B"){ ?>
                  <div class="btn btn-default pull-right" data-toggle="modal" data-target="#pourcentage">Mettre à jour le pourcentage</div><?php
      } ?></h4>

    </div>
    <div class="col-md-6">
      <div class="progress" style="margin-top:15px; margin-bottom: 15px;">
        <div class="progress-bar" role="progressbar" style="color:<?php echo $text; ?>;width: <?php echo $pourcentage; ?>%; background-color: <?php echo $couleur; ?>;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"><?php echo $pourcentage; ?>%</div>
      </div>
    </div>
    <div class="col-md-6">Avancement : <?php echo round((floatval($avancement/$total)*100),2); ?>% &emsp; Retard : <?php echo round((floatval($retard/$total)*100),2); ?>% &emsp; LB : <?php echo $pourcentage; ?>% &emsp; 
    <span <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?> class="clickable" data-toggle="modal" data-target="#capacitaire" <?php } ?>>
        <?php if(! is_null($Data['capacitaire'])){ echo " Cp : ".$Data['capacitaire']."%"; }else{ echo "Cp : -";}?>
      </span>
      </div>
    <div class="col-md-6">
      <h4>Gate : <?php echo $gate; ?></h4>
    </div>
    <div class="col-md-6" style="font-size:80%;">
      <?php
      foreach (['me'=>'ME','hsep'=>'HSE','quality'=>'QUALITY','log'=>'LOG/PC&amp;L','training'=>'Training/EE','supplier'=>'Suppliers'] as $key => $value) {
        if(is_null($Data[$key])){
          $color="";
          $pour="-";
        }else{
          $pour=$Data[$key]." %";
          if($Data[$key] <75){
            $color = '#FF002A';
          }else if($Data[$key] < 85){
            $color='#ff8900';
          }else{
            $color='green';
          }
        }
        if((($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] )){
          echo '<span style="color:'.$color.';" class="clickable" data-toggle="modal" data-target="#'.$key.'">'.$value.' : '.$pour.'</span>&emsp; ';
        }else{
          echo '<span style="color:'.$color.';" >'.$value.' : '.$pour.'</span>&emsp; ';
        }
      }
      ?>
    </div>
</div>
<br>
<div class="row">
  <div >
      <div class="onglets">
          <span class="onglet_0 onglet" id="onglet_1" onclick="javascript:change_onglet('1');"><b>Gate 2B</b></span>
          <span class="onglet_0 onglet" id="onglet_2" onclick="javascript:change_onglet('2');"><b>Gate 3</b></span>
          <span class="onglet_0 onglet" id="onglet_3" onclick="javascript:change_onglet('3');"><b>Gate 4</b></span>
          <span class="onglet_0 onglet" style="float:right;" id="onglet_4" onclick="javascript:change_onglet('4');"><b>Aide</b></span>
      </div>
      <div class="contenu_onglets conteneur">
          <div class="contenu_onglet conteneur" id="contenu_onglet_1">
              <table class="gate">
              <thead>
                  <tr>
                      <th colspan="2">TCT / SWCT</th>
                      <th colspan="2">Capacity</th>
                      <th colspan="2">Eqpt & tooling list</th>
                      <th colspan="2">PFMEA</th>
                      <th colspan="2">MVP</th>
                      <th colspan="2">Layout</th>
                      <th colspan="2">Master schedule</th>
                      <th colspan="2">Packaging incl. Shop stock</th>
                  </tr>
                  <tr>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                      <th>F</th>
                      <th>R</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td <?php forecast('2tct_f'); ?>><?php if(is_null($Data['2tct_f'])){echo "-";}else{$date = strtotime($Data['2tct_f']); echo date('j/m/y', $date);} ?></td><?php choose('2tct_f'); ?>
                    <td <?php color('2tct'); ?>><?php if(is_null($Data['2tct_r'])){echo "-";}else{$date = strtotime($Data['2tct_r']); echo date('j/m/y', $date);} ?></td><?php choose('2tct_r'); ?>
                    <td <?php forecast('2capacity_f'); ?>><?php if(is_null($Data['2capacity_f'])){echo "-";}else{$date = strtotime($Data['2capacity_f']); echo date('j/m/y', $date);} ?></td><?php choose('2capacity_f'); ?>
                    <td <?php color('2capacity'); ?>><?php if(is_null($Data['2capacity_r'])){echo "-";}else{$date = strtotime($Data['2capacity_r']); echo date('j/m/y', $date);} ?></td><?php choose('2capacity_r'); ?>
                    <td <?php forecast('2equip_f'); ?>><?php if(is_null($Data['2equip_f'])){echo "-";}else{$date = strtotime($Data['2equip_f']); echo date('j/m/y', $date);} ?></td><?php choose('2equip_f'); ?>
                    <td <?php color('2equip'); ?>><?php if(is_null($Data['2equip_r'])){echo "-";}else{$date = strtotime($Data['2equip_r']); echo date('j/m/y', $date);} ?></td><?php choose('2equip_r'); ?>
                    <td <?php forecast('2pfmea_f'); ?>><?php if(is_null($Data['2pfmea_f'])){echo "-";}else{$date = strtotime($Data['2pfmea_f']); echo date('j/m/y', $date);} ?></td><?php choose('2pfmea_f'); ?>
                    <td <?php color('2pfmea'); ?>><?php if(is_null($Data['2pfmea_r'])){echo "-";}else{$date = strtotime($Data['2pfmea_r']); echo date('j/m/y', $date);} ?></td><?php choose('2pfmea_r'); ?>
                    <td <?php forecast('2mvp_f'); ?>><?php if(is_null($Data['2mvp_f'])){echo "-";}else{$date = strtotime($Data['2mvp_f']); echo date('j/m/y', $date);} ?></td><?php choose('2mvp_f'); ?>
                    <td <?php color('2mvp'); ?>><?php if(is_null($Data['2mvp_r'])){echo "-";}else{$date = strtotime($Data['2mvp_r']); echo date('j/m/y', $date);} ?></td><?php choose('2mvp_r'); ?>
                    <td <?php forecast('2layout_f'); ?>><?php if(is_null($Data['2layout_f'])){echo "-";}else{$date = strtotime($Data['2layout_f']); echo date('j/m/y', $date);} ?></td><?php choose('2layout_f'); ?>
                    <td <?php color('2layout'); ?>><?php if(is_null($Data['2layout_r'])){echo "-";}else{$date = strtotime($Data['2layout_r']); echo date('j/m/y', $date);} ?></td><?php choose('2layout_r'); ?>
                    <td <?php forecast('2master_f'); ?>><?php if(is_null($Data['2master_f'])){echo "-";}else{$date = strtotime($Data['2master_f']); echo date('j/m/y', $date);} ?></td><?php choose('2master_f'); ?>
                    <td <?php color('2master'); ?>><?php if(is_null($Data['2master_r'])){echo "-";}else{$date = strtotime($Data['2master_r']); echo date('j/m/y', $date);} ?></td><?php choose('2master_r'); ?>
                    <td <?php forecast('2pack_f'); ?>><?php if(is_null($Data['2pack_f'])){echo "-";}else{$date = strtotime($Data['2pack_f']); echo date('j/m/y', $date);} ?></td><?php choose('2pack_f'); ?>
                    <td <?php color('2pack'); ?>><?php if(is_null($Data['2pack_r'])){echo "-";}else{$date = strtotime($Data['2pack_r']); echo date('j/m/y', $date);} ?></td><?php choose('2pack_r'); ?>
                  </tr>
              </tbody>
            </table>
          </div>
          <div class="contenu_onglet conteneur" id="contenu_onglet_2">
            <table class="gate">
              <thead>
                <tr>
                    <th colspan="2">Eqpt & tooling reception</th>
                    <th colspan="2">Packaging incl. Shop stock reception</th>
                    <th colspan="2">Supplier PPAP</th>
                    <th colspan="2">Launch book checklist</th>
                    <th colspan="2">PT run@rate</th>
                    <th colspan="2">Launch book checklist</th>
                    <th colspan="2">MPT run@rate</th>
                    <th colspan="2">Initial Samples Submitted</th>
                </tr>
                <tr>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td <?php forecast('3equip_f'); ?>><?php if(is_null($Data['3equip_f'])){echo "-";}else{$date = strtotime($Data['3equip_f']); echo date('j/m/y', $date);} ?></td><?php choose('3equip_f'); ?>
                  <td <?php color('3equip'); ?>><?php if(is_null($Data['3equip_r'])){echo "-";}else{$date = strtotime($Data['3equip_r']); echo date('j/m/y', $date);} ?></td><?php choose('3equip_r'); ?>
                  <td <?php forecast('3pack_f'); ?>><?php if(is_null($Data['3pack_f'])){echo "-";}else{$date = strtotime($Data['3pack_f']); echo date('j/m/y', $date);} ?></td><?php choose('3pack_f'); ?>
                  <td <?php color('3pack'); ?>><?php if(is_null($Data['3pack_r'])){echo "-";}else{$date = strtotime($Data['3pack_r']); echo date('j/m/y', $date);} ?></td><?php choose('3pack_r'); ?>
                  <td <?php forecast('3supplier_f'); ?>><?php if(is_null($Data['3supplier_f'])){echo "-";}else{$date = strtotime($Data['3supplier_f']); echo date('j/m/y', $date);} ?></td><?php choose('3supplier_f'); ?>
                  <td <?php color('3supplier'); ?>><?php if(is_null($Data['3supplier_r'])){echo "-";}else{$date = strtotime($Data['3supplier_r']); echo date('j/m/y', $date);} ?></td><?php choose('3supplier_r'); ?>
                  <td <?php forecast('3checklist1_f'); ?>><?php if(is_null($Data['3checklist1_f'])){echo "-";}else{$date = strtotime($Data['3checklist1_f']); echo date('j/m/y', $date);} ?></td><?php choose('3checklist1_f'); ?>
                  <td <?php color('3checklist1'); ?>><?php if(is_null($Data['3checklist1_r'])){echo "-";}else{$date = strtotime($Data['3checklist1_r']); echo date('j/m/y', $date);} ?></td><?php choose('3checklist1_r'); ?>
                  <td <?php forecast('3pt_f'); ?>><?php if(is_null($Data['3pt_f'])){echo "-";}else{$date = strtotime($Data['3pt_f']); echo date('j/m/y', $date);} ?></td><?php choose('3pt_f'); ?>
                  <td <?php color('3pt'); ?>><?php if(is_null($Data['3pt_r'])){echo "-";}else{$date = strtotime($Data['3pt_r']); echo date('j/m/y', $date);} ?></td><?php choose('3pt_r'); ?>
                  <td <?php forecast('3checklist2_f'); ?>><?php if(is_null($Data['3checklist2_f'])){echo "-";}else{$date = strtotime($Data['3checklist2_f']); echo date('j/m/y', $date);} ?></td><?php choose('3checklist2_f'); ?>
                  <td <?php color('3checklist2'); ?>><?php if(is_null($Data['3checklist2_r'])){echo "-";}else{$date = strtotime($Data['3checklist2_r']); echo date('j/m/y', $date);} ?></td><?php choose('3checklist2_r'); ?>
                  <td <?php forecast('3mpt_f'); ?>><?php if(is_null($Data['3mpt_f'])){echo "-";}else{$date = strtotime($Data['3mpt_f']); echo date('j/m/y', $date);} ?></td><?php choose('3mpt_f'); ?>
                  <td <?php color('3mpt'); ?>><?php if(is_null($Data['3mpt_r'])){echo "-";}else{$date = strtotime($Data['3mpt_r']); echo date('j/m/y', $date);} ?></td><?php choose('3mpt_r'); ?>
                  <td <?php forecast('3samples_f'); ?>><?php if(is_null($Data['3samples_f'])){echo "-";}else{$date = strtotime($Data['3samples_f']); echo date('j/m/y', $date);} ?></td><?php choose('3samples_f'); ?>
                  <td <?php color('3samples'); ?>><?php if(is_null($Data['3samples_r'])){echo "-";}else{$date = strtotime($Data['3samples_r']); echo date('j/m/y', $date);} ?></td><?php choose('3samples_r'); ?>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="contenu_onglet conteneur" id="contenu_onglet_3">
            <table class="gate">
              <thead>
                <tr>
                    <th colspan="2">Launch book checklist</th>
                    <th colspan="2">EMPT run@rate</th>
                </tr>
                <tr>
                    <th>F</th>
                    <th>R</th>
                    <th>F</th>
                    <th>R</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td <?php forecast('4checklist_f'); ?>><?php if(is_null($Data['4checklist_f'])){echo "-";}else{$date = strtotime($Data['4checklist_f']); echo date('j/m/y', $date);} ?></td><?php choose('4checklist_f'); ?>
                  <td <?php color('4checklist'); ?>><?php if(is_null($Data['4checklist_r'])){echo "-";}else{$date = strtotime($Data['4checklist_r']); echo date('j/m/y', $date);} ?></td><?php choose('4checklist_r'); ?>
                  <td <?php forecast('4empt_f'); ?>><?php if(is_null($Data['4empt_f'])){echo "-";}else{$date = strtotime($Data['4empt_f']); echo date('j/m/y', $date);} ?></td><?php choose('4empt_f'); ?>
                  <td <?php color('4empt'); ?>><?php if(is_null($Data['4empt_r'])){echo "-";}else{$date = strtotime($Data['4empt_r']); echo date('j/m/y', $date);} ?></td><?php choose('4empt_r'); ?>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="contenu_onglet conteneur" id="contenu_onglet_4">
            <div class="container row" style="margin:15px;">
              <div class="col-md-1" style="background-color: #90EE90;height: 20px;"></div>
              <div class="col-md-11">: Date de réalisation antérieure à la data prévue.</div>
            </div>
            <div class="container row" style="margin:15px;">
              <div class="col-md-1" style="background-color: #FF6347;height: 20px;"></div>
              <div class="col-md-11">: Date de réalisation postérieure à la data prévue.</div>
            </div>
            <div class="container row" style="margin:15px;">
              <div class="col-md-1" style="background-color: #FFD700;height: 20px;"></div>
              <div class="col-md-11">: Date de réalisation non validée par le responable PPTL.</div>
            </div>
          </div>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <h4>Client : <?php echo $Data['client']; ?></h4>
    <h4>Equipe :   <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <div class="btn btn-default pull-right" data-toggle="modal" data-target="#equipe">Modifier l'équipe</div><?php } ?></h4>
    <ul>
      <?php
      $team = $bdd -> prepare('SELECT * FROM profil JOIN equipe ON profil.id = equipe.id_profil WHERE equipe.id_projet = ?');
      $team -> execute(array($_GET['id']));
      while($pers = $team ->fetch()){
        echo "<li>".$pers['nom']." ".$pers['prenom']." : <a href='mailto:".$pers['mail']."'>".$pers['mail']."</a></li>";
      } ?>
      </ul>
    <h4>Fichiers :</h4>
      <?php
      if(! is_null($Data['kickoff'])){
        $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
        $img -> execute(array($Data['kickoff']));
        $file = $img -> fetch(); ?>
          <form method="post">
            <input type="hidden" name="kickoff" value="<?php echo $Data['kickoff']; ?>">
            <a href="download.php?id=<?php echo $Data['kickoff']; ?>&amp;name=kickoff<?php echo $_GET['id']; ?>" class="btn btn-default">Télécharger le kickoff</a>
            <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){?>
            <input type="submit" name="delete_kickoff" class="btn btn-default" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer le kickoff ?')"><?php } ?>
          </form>
        <?php
      }elseif(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="kickoff">
          <input type="submit" class="btn btn-default" value="Ajouter le kickoff (ppt)">
        </form>
        <?php
      }
      echo "<br>"; ?>

      <?php
      if(! is_null($Data['makeorbuy'])){
        $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
        $img -> execute(array($Data['makeorbuy']));
        $file = $img -> fetch(); ?>
          <form method="post">
            <input type="hidden" name="makeorbuy" value="<?php echo $Data['makeorbuy']; ?>">
            <a href="download.php?id=<?php echo $Data['makeorbuy']; ?>&amp;name=makeorbuy<?php echo $_GET['id']; ?>" class="btn btn-default">Télécharger le Make or Buy &amp; BOM</a>
            <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){?>
            <input type="submit" name="delete_makeorbuy" class="btn btn-default" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer le Make or Buy ?')"><?php } ?>
          </form>
        <?php
      }elseif(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="makeorbuy">
          <input type="submit" class="btn btn-default" value="Ajouter le Make or Buy (ppt)">
        </form>
        <?php
      }
      echo "<br>"; ?>
        <h4>Launchbook :<?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <div class="btn btn-default pull-right" data-toggle="modal" data-target="#launchbook">Modifier</div><?php } ?></h4><a href="<?php echo $Data['launchbook']; ?>"><?php echo $Data['launchbook']; ?></a>
      
      <h4>Lien HELIOS :<?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <div class="btn btn-default pull-right" data-toggle="modal" data-target="#helios">Modifier</div><?php } ?></h4><a href="<?php echo $Data['link_helios']; ?>"><?php echo $Data['link_helios']; ?></a>
      
      <h4>Lien PLR : <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
        <div class="btn btn-default pull-right" data-toggle="modal" data-target="#plr">Modifier</div><?php } ?></h4><a href="<?php echo $Data['link_plr']; ?>"><?php echo $Data['link_plr']; ?></a>
      
  </div>
  <div class="col-md-6">
    <?php
    if(! is_null($Data['img_presentation'])){
      $img = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
      $img -> execute(array($Data['img_presentation']));
      $img = $img -> fetch();
      echo "<img src=".$img['chemin']." style='max-width:100%;border-radius: 6px; max-height:350px;' alt='Image'>";
    }elseif(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
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
          <select class="form-control" name="profil">
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
<div id="pm" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le PM</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="text" class="form-control" name="pm" value="<?php echo $Data['pm']; ?>">
          <br>
          <input type="submit" name="pm_check" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le PM ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="launchbook" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le launchbook</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="text" name="launchbook_link" value="<?php echo $Data['launchbook']; ?>" class="form-control">
          <input type="submit" name="launchbook" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le launchbook ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="sop" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier SOP</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="date" name="sop_date" value="<?php echo $Data['initial_date']; ?>" class="form-control" >
          <br>
          <input type="submit" name="sop" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le SOP ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="equipe" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier l'équipe</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <select class="form-control" name="equipe[]" multiple>
            <?php
            $profil = $bdd -> query('SELECT * FROM profil');
            while($personne = $profil -> fetch()){
              $test = $bdd -> prepare('SELECT * FROM equipe WHERE id_profil =? AND id_projet = ?');
              $test -> execute(array($personne['id'],$_GET['id']));
              if(! $test -> fetch()){ ?>
              <option value="<?php echo $personne['id']; ?>"><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
            <?php  }} ?>
          </select>
          <br>
          <input type="submit" name="ajout_equipe" class="btn btn-default form-control" value="Ajouter" >
          <br><br><br>
          <select class="form-control" name="remove">
            <?php
            $remove = $bdd -> prepare('SELECT *,equipe.id as ref FROM profil JOIN equipe ON profil.id = equipe.id_profil WHERE equipe.id_projet = ?');
            $remove -> execute(array($_GET['id']));
            while($personne = $remove -> fetch()){ ?>
              <option value="<?php echo $personne['ref']; ?>"><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
            <?php  } ?>
          </select>
          <br>
          <input type="submit" name="remove_equipe" class="btn btn-default form-control" value="Supprimer" >
        </form>
      </div>
    </div>
  </div>
</div>
<div id="description" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier la description</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <div class="row">
            <div class="form-group col-md-12">
              <label>Description :</label>
              <select name="description[]" class="form-control" multiple>
                <option value="Components">Components</option>
                <option value="Stamped muffler">Stamped muffler</option>
                <option value="Locked muffler">Locked muffler</option>
                <option value="Swan neck">Swan neck</option>
                <option value="Hot end">Hot end</option>
                <option value="Final assy no jit">Final assy no jit</option>
                <option value="Final assy jit">Final assy jit</option>
              </select>            </div>
          </div>
          <input type="submit" name="descr" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier la description ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="helios" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le lien</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="text" class="form-control" name="link_helios" value="<?php echo $Data['link_helios']; ?>">
          <br>
          <input type="submit" name="helios" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le lien ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="plr" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le lien</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="text" class="form-control" name="link_plr" value="<?php echo $Data['link_plr']; ?>">
          <br>
          <input type="submit" name="plr" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le lien ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="pourcentage" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mettre à jour le pourcentage</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="text" class="form-control" name="pourcentage" value="<?php echo $pourcentage; ?>">
          <br>
          <input type="submit" name="ptg" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le pourcentage ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Pourcentages -->
<div id="capacitaire" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le capacitaire</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="number" class="form-control" name="capacitaire" value="<?php echo $Data['capacitaire']; ?>" min="0" max="100">
          <br>
          <input type="submit" name="capacitaire_click" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le capacitaire ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<?php
foreach (['me'=>'ME','hsep'=>'HSE','quality'=>'QUALITY','log'=>'LOG/PC&amp;L','training'=>'Training/EE','supplier'=>'Suppliers'] as $key => $value){ ?>
<div id="<?php echo $key; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le pourcentage <?php echo $value; ?></h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <input type="number" class="form-control" name="<?php echo $key; ?>" value="<?php echo $Data[$key]; ?>" min="0" max="100">
          <br>
          <input type="submit" name="<?php echo $key; ?>_click" class="btn btn-default form-control" value="Modifier" onclick="return confirm('Êtes-vous sûr de vouloir modifier le pourcentage <?php echo $value; ?> ?')">
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<br><br>
<form method="post" action="index.php">
  <a href="index.php" class="btn btn-default">Retour</a>
  <?php if(($Data['profil'] == $_SESSION['id']) || $_SESSION['launchboard'] ){ ?>
    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" >
    <?php if($Data['archive']){
      echo '<input type="submit" class="btn btn-default pull-right" value="Restaurer" name="desarchive" onclick="return confirm(\'Êtes-vous sûr de vouloir restaurer le projet ?\')">';
    }else{
      echo '<input type="submit" class="btn btn-default pull-right" value="Archiver" name="archive" onclick="return confirm(\'Êtes-vous sûr de vouloir archiver le projet ?\')">';
    }
  } 
  ?>
</form>
<script type="text/javascript">
    //<!--
            function change_onglet(name)
            {
                    document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
                    document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
                    document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
                    document.getElementById('contenu_onglet_'+name).style.display = 'block';
                    anc_onglet = name;
            }
    //-->
  </script>
  <script type="text/javascript">
    //<!--
            var anc_onglet = '1';
            change_onglet(anc_onglet);
    //-->
    </script>
<?php
}}
drawFooter();
 ?>
