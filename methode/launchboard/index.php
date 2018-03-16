<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');

echo "<h2>LaunchRoom</h2>";
if(! empty($_POST)){
  if(isset($_POST['ajout'])){
    $descr="";
    if(isset($_POST['description'])){
      foreach($_POST['description'] as $key => $value) {
        $descr.=$value." / ";
      }
    }
    $descr=substr($descr,0,sizeof($descr)-3);
    $img=upload($bdd,'img',"../../ressources","launchboard",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($img < 0){$img=NULL;}
    $kickoff=upload($bdd,'kickoff',"../../ressources","launchboard",50485760,array( 'ppt' , 'pptx' , 'PPT' , 'PPTX' ));
    if($kickoff < 0){$kickoff=NULL;}
    $makeorbuy=upload($bdd,'makeorbuy',"../../ressources","launchboard",50485760,array( 'ppt' , 'pptx' , 'PPT' , 'PPTX' ));
    if($makeorbuy < 0){$makeorbuy=NULL;}
    $add = $bdd -> prepare('INSERT INTO launchboard(profil,pm,code,titre,client,description,initial_date,link_plr,link_helios,kickoff,makeorbuy,img_presentation,launchbook) VALUES (:profil,:pm,:code,:titre,:client,:description,:sop,:link_plr,:link_helios,:kickoff,:makeorbuy,:img,:launchbook)');
    if($add -> execute(array(
      "profil" => $_POST['profil'],
      "pm" => $_POST['pm'],
      "code" => $_POST['code'],
      "titre" => $_POST['titre'],
      "client" => $_POST['client'],
      "description" => $descr,
      "sop" => $_POST['sop'],
      "link_plr" => $_POST['plr'],
      "link_helios" => $_POST['helios'],
      "kickoff" => $kickoff,
      "makeorbuy" => $makeorbuy,
      "img" => $img,
      "launchbook" => $_POST['launchbook']
    ))){
      $id = $bdd -> lastInsertId();
      foreach($_POST['equipe'] as $prof) {
        $q = $bdd ->prepare('INSERT INTO equipe(id_projet,id_profil) VALUES (?,?)');
        $q-> execute(array($id,$prof));
      }
      success('Ajouté','Le projet a bien été ajouté.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
  if(isset($_POST['archive'])){
    $archive = $bdd -> prepare('UPDATE launchboard SET archive="1" WHERE id= ?');
    if($archive -> execute(array($_POST['id']))){
      success('Archivé','Le projet a été archivé.');
    }else{
      warning('Erreur','Il y a eu une erreur. VEuillez réessayer.');
    }
  }
  if(isset($_POST['desarchive'])){
    $desarchive = $bdd -> prepare('UPDATE launchboard SET archive="0" WHERE id= ?');
    if($desarchive -> execute(array($_POST['id']))){
      success('Restauré','Le projet a été restauré.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez réessayer.');
    }
  }
}

$recherche = "";
if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}
$supprime =false;
if(isset($_GET['supprime'])){
  $supprime=true;
  $query = $bdd -> prepare('SELECT *, launchboard.id as projet, lb.pourcentage as lb FROM launchboard JOIN profil ON profil.id=launchboard.profil LEFT JOIN (SELECT * FROM evolution_projet T WHERE NOT EXISTS (SELECT 1 FROM evolution_projet T2 WHERE T2.id_projet = T.id_projet AND T2.date > T.date)) as lb ON launchboard.id = lb.id_projet WHERE (nom LIKE :nom or prenom LIKE :prenom or titre LIKE :titre or code LIKE :code) ORDER BY lb ASC');
}else{
  $query = $bdd -> prepare('SELECT *, launchboard.id as projet, lb.pourcentage as lb FROM launchboard JOIN profil ON profil.id=launchboard.profil LEFT JOIN (SELECT * FROM evolution_projet T WHERE NOT EXISTS (SELECT 1 FROM evolution_projet T2 WHERE T2.id_projet = T.id_projet AND T2.date > T.date)) as lb ON launchboard.id = lb.id_projet WHERE ((nom LIKE :nom or prenom LIKE :prenom or titre LIKE :titre or code LIKE :code) AND archive = 0) ORDER BY lb ASC');
}
$query ->bindValue(':titre','%'.$recherche.'%');
$query ->bindValue(':nom','%'.$recherche.'%');
$query ->bindValue(':prenom','%'.$recherche.'%');
$query ->bindValue(':code','%'.$recherche.'%');
$query ->execute();

?>

<style>
  .conteneur_projet{
      margin-top:20px;
      margin-left:-12.5%;
      margin-right:-12.5%;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
  }
  .projet{
      color: #000 ;
      font-size: 15px;
      font-family:Arial;
      background-color: #e3e3e3;
      border-color: #ccc;
      border-radius:6px;
      border-width: 1px;
      border-style: solid;
      margin: 5px;
  }
  .projet:hover{
    opacity:0.7;
  }
  .info_projet{
      margin: 10px;
      width: 400px;
      padding: 10px;
      height:280px;
      border-radius:6px;
      background-color: #FFF;
      border-color: #ccc;
      border-width: 1px;
      border-style: solid;
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
</style>
<form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prenom,Titre,Code" value="<?php echo $recherche;?>">
    <label style="margin-left: 30px;"><input type="checkbox" name="supprime" <?php if($supprime){echo "checked";} ?>> Archivé</label>

  </div>

  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="generate.php" class="btn btn-default pull-right">Export Excel</a>
  <a href="ajout.php" class="btn btn-default pull-right">Ajouter</a>
</form>

<div class="conteneur_projet">
  <?php
while($Data = $query -> fetch()){
  $q = $bdd -> prepare('SELECT * FROM files WHERE id= ?');
  $q -> execute(array($Data['img_presentation']));
  if(! is_null($Data['lb'])){
    $pourcentage=$Data['lb'];
  }else{
    $pourcentage=0;
  }  
 ?>
  <a href="projet.php?id=<?php echo $Data['projet']; ?>">
    <div class="projet" >
      <div class="info_projet">
        <h4 style="margin-top: 0px; height:40px; font-size: 40px;"><?php echo $Data['code']; ?><?php if($Data['archive']){ echo "<img src='../ressources/rubbish.png' alt ='corbeille' height='40px'/>";} ?>
            <?php if($Data['lb'] < 50){echo '<img src="../ressources/attention.png" style="height: 40px; float:right;">';} ?>
            </h4>
            <p><b>PPTL : </b><?php echo $Data['nom']." ".$Data['prenom']; ?><br>
            <b>Client : </b><?php echo $Data['client']; ?><span style="float:right;"><b>PM : </b> <?php echo $Data['pm']; ?></span></p>
            <div style="padding:0;" class="container-fluid">
              <div class="row" style="height:120px;">
              <div class="col-md-8">
                <b>Description : </b><?php echo $Data['description']; ?><br>
                <?php
                $gate="2B";
                if($Data['2tct'] && $Data['2capacity'] && $Data['2equip'] && $Data['2pfmea'] && $Data['2mvp'] && $Data['2layout'] && $Data['2master'] && $Data['2pack']){
                  $gate="3";
                  if($Data['3equip'] && $Data['3pack'] && $Data['3supplier'] && $Data['3checklist1'] && $Data['3pt'] && $Data['3checklist2'] && $Data['3mpt'] && $Data['3samples']){
                    $gate="4";
                  }
                }
                ?>
                <p><b> Gate : </b><?php echo $gate; ?>&emsp;
                  <b>LB : </b><?php echo $pourcentage."%"; ?>&ensp; <b>Cp : </b><?php if(is_null($Data['capacitaire'])){echo "-";}else{echo $Data['capacitaire']." %"; }?></p>
                  <p><b>SOP :</b> <?php if(!is_null($Data['initial_date'])){ echo date('d/m/y',strtotime($Data['initial_date']));} ?></p>
              </div>
              <div class="col-md-4">
              <?php
                if($Data['img_presentation'] != NULL){
                  $q= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
                  $q -> execute(array($Data['img_presentation']));
                  $img= $q -> fetch();?>
                  <img src="<?php echo $img['chemin']; ?>" style="border-radius:1px;max-width:100%; max-height: 120px; float:right;" alt="Image">
                <?php } ?>
            </div>
            <div class="col-md-12" style="font-size:75%;">
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
                  echo '<span style="color:'.$color.'; white-space: nowrap;" >'.$value.' : '.$pour.'</span>&emsp; ';
                }
                ?>
            </div>
        </div>
      </div>
      </div>
      <?php
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
      } ?>
      <div class="progress couleur" style="margin-top:15px; margin-bottom: 15px;">
        <div class="progress-bar" role="progressbar" style="color:<?php echo $text; ?>;width: <?php echo $pourcentage; ?>%; background-color: <?php echo $couleur; ?>;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"><?php echo $pourcentage; ?>%</div>
      </div>
    </div>
  </a>


<?php
}

?>
</div>

<script>


    function bouger(){


        console.debug("descente");
        $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, $(document).height()*7);
        setTimeout(function() {

            console.debug("monte");
            $('html, body').animate({scrollTop:0}, $(document).height()*7);
        },$(document).height());


        timeoutHandle  = window.setTimeout(bouger, $(document).height()*14);



    }

    var timeoutHandle  = window.setTimeout(bouger, 30000);

    $("html, body").mousemove(function(event){

        console.info("reset");
        window.clearInterval(timeoutHandle );
        timeoutHandle  = window.setTimeout(bouger, 30000);

        $("html, body").stop();
    });


</script>
<?php
drawFooter();
 ?>
