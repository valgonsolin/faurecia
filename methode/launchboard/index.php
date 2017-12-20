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

$recherche = "";
if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}
$query = $bdd -> prepare('SELECT * FROM launchboard WHERE (nom LIKE :nom or prenom LIKE :prenom or titre LIKE :titre)');
$query ->bindValue(':titre','%'.$recherche.'%');
$query ->bindValue(':nom','%'.$recherche.'%');
$query ->bindValue(':prenom','%'.$recherche.'%');
$query ->execute();

?>
<h2>LaunchRoom</h2>
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
    <th>Titre</th>
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
<tr class="clickable" onclick="window.location='projet.php?id=<?php echo $Data['id']; ?>'">
  <td><?php echo $Data['titre']; ?></td>
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
