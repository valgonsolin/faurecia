<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('launchboard');
//Entée de la base de données:
//Nom Prenom
//Titre
//Client
//description
//LB
//gate
//img_presentation
//   ICI le details des gates
//    Pour le gate 2B
//2tct_f
//2tct_r
//2capacity_f
//2capacity_r
//2equip_f
//2equip_r
//2pfmea_f
//2pfmea_r
//2mvp_f
//2mvp_r
//2layout_f
//2layout_r
//2master_f
//2master_r
//2pack_f
//2pack_r

//      Gate 3
//3equip_f
//3equip_r
//3pack_f
//3pack_r
//3supplier_f
//3supplier_r
//3checklist1_f
//3checklist1_r
//3pt_f
//3pt_r
//3checklist2_f
//3checklist2_r
//3mpt_f
//3mpt_r
//3samples_f
//3samples_r
//    GATE 4
//4checklist_f
//4checklist_r
//4empt_f
//4empt_r

//initial_date
//date_updated
//realized_date
//launchbook
//pourcentage_a_date
//link_plr
//link_helios
//equipe id vers une autre table equipe qui contient les noms et mails de l'equipe
//kickoff


echo "<h2>LaunchRoom</h2>";
if(! empty($_POST)){
  if(isset($_POST['ajout'])){
    $img=upload($bdd,'img',"../../ressources","launchboard",5048576,array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'JPG' , 'JPEG' , 'GIF' , 'PNG' ));
    if($img < 0){$img=NULL;}
    $kickoff=upload($bdd,'kickoff',"../../ressources","launchboard",50485760,array( 'ppt' , 'pptx' , 'PPT' , 'PPTX' ));
    if($kickoff < 0){$kickoff=NULL;}
    $launchbook=upload($bdd,'launchbook',"../../ressources","launchboard",50485760,array( 'xls' , 'xlsx' , 'XLS' , 'XLSX' ));
    if($launchbook < 0){$launchbook=NULL;}
    $add = $bdd -> prepare('INSERT INTO launchboard(profil,code,titre,client,description,initial_date,link_plr,link_helios,date_updated,kickoff,img_presentation,launchbook) VALUES (:profil,:code,:titre,:client,:description,CURDATE(),:link_plr,:link_helios,CURDATE(),:kickoff,:img,:launchbook)');
    if($add -> execute(array(
      "profil" => $_POST['profil'],
      "code" => $_POST['code'],
      "titre" => $_POST['titre'],
      "client" => $_POST['client'],
      "description" => $_POST['description'],
      "link_plr" => $_POST['plr'],
      "link_helios" => $_POST['helios'],
      "kickoff" => $kickoff,
      "img" => $img,
      "launchbook" => $launchbook
    ))){
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
  $query = $bdd -> prepare('SELECT * FROM launchboard JOIN profil ON profil.id=launchboard.profil WHERE (nom LIKE :nom or prenom LIKE :prenom or titre LIKE :titre or code LIKE :code) ORDER BY couleur DESC');
}else{
  $query = $bdd -> prepare('SELECT * FROM launchboard JOIN profil ON profil.id=launchboard.profil WHERE ((nom LIKE :nom or prenom LIKE :prenom or titre LIKE :titre or code LIKE :code) AND archive = 0) ORDER BY couleur DESC');
}
$query ->bindValue(':titre','%'.$recherche.'%');
$query ->bindValue(':nom','%'.$recherche.'%');
$query ->bindValue(':prenom','%'.$recherche.'%');
$query ->bindValue(':code','%'.$recherche.'%');
$query ->execute();

?>

<style>
.color1{
  background-color:#ccffcc;
  box-shadow: 1px 1px 3px #ccffcc;
}
.color1:hover{
  background-color: #b3ffb3;
}
.color2{
  background-color:#ffd699;
  box-shadow: 1px 1px 3px #ffd699;

}
.color2:hover{
  background-color: #ffcc80;
}
.color3{
  background-color:#ff9999;
  box-shadow: 1px 1px 3px #ff9999;
}
.color3:hover{
  background-color: #ff8080;
}
</style>
<form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prenom,Titre,Code" value="<?php echo $recherche;?>">
    <label style="margin-left: 30px;"><input type="checkbox" name="supprime" <?php if($supprime){echo "checked";} ?>> Archivé</label>

  </div>

  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Ajouter</a>
</form>
<table class="table">
<thead class="thead">
<tr>
    <th>Code</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Description</th>
    <th>% LB</th>
    <th>Gate</th>
    <th>Image</th>
</tr>
</thead>
<tbody> <?php
while($Data = $query -> fetch()){
  $q = $bdd -> prepare('SELECT * FROM files WHERE id= ?');
  $q -> execute(array($Data['img_presentation']));?>
<tr class="clickable color<?php echo $Data['couleur']; ?>" onclick="window.location='projet.php?id=<?php echo $Data['id']; ?>'">
  <td><?php echo $Data['code']; ?></td>
  <td><?php echo $Data['nom']; ?></td>
  <td><?php echo $Data['prenom']; ?></td>
  <td><?php echo $Data['description']; ?></td>
  <td></td>
  <td></td>
  <td><?php
    if($Data['img_presentation'] != NULL){
      $q= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
      $q -> execute(array($Data['img_presentation']));
      $img= $q -> fetch();?>
      <img src="<?php echo $img['chemin']; ?>" style="max-width:100px; max-height:100px;  margin:5px;" alt="Image">
    <?php } ?>
  </td>
</tr>
<?php
}

?>
</tbody>

</table>
<?php
drawFooter();
 ?>
