<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('les_idees');


$recherche = "";
$debut=0;


if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}

if(isset($_GET['nb'])){
  $debut=$_GET['nb'];
}



if(empty($_SESSION['login']))
{ ?>
  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/idees_ameliorations"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
?>

<h2>Idées du mois</h2>

  <form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Modifier/Supprimer</a>
  <a href="idees_tot.php" class="btn btn-default pull-right">Toutes les idées</a>
</form>


<table class="table">
<thead class="thead">
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th style="width: 70px;">Type</th>
    <th style="width: 30px;">Date</th>
    <th style="width: 30px;">Score</th>
    <th style="width: 30px;"> Votre vote</th>
    <th style="width: 30px;">Details/Vote</th>

</tr>
</thead>
<tbody>

<?php


  $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
      ON idees_ameliorations.emmetteur = profil.id
      WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 ORDER BY vote LIMIT 40 OFFSET ? WHERE MONTH(date)==MONTH(CURDATE()) ') ;
      $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.$debut.'%'));


while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td> <?php echo $Data['nom']; ?> </td>
        <td><?php echo $Data['prenom']; ?></td>
        <td><?php echo $Data['type']; ?></td>
        <td><?php echo $Data['date_rea']; ?></td>
        <td><?php echo $Data['vote']; ?></td>
        <td><?php

        $Qy = $bdd->prepare('SELECT FROM votes_idees WHERE personne= ? AND idee= ?');
        $Qy->execute(array('%'.$_SESSION['id'].'%',  '%'.$Data['idees_ameliorations.id'].'%'));

        if($Qy->fetch()){echo "X";}else{echo "";} ?>
      </td>

        <td class="clickable" title="Cliquez pour voter/voir le detail " onclick="window.location='details.php?idee=<?php echo $Data['idees_ameliorations.id'] ;?>'">Details</td>


    </tr>


    <?php
}
?>
</tbody>
</table>

<?php
if($debut > 39){
  ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-40;?>" class="btn btn-default">Elements précédents</a>
<?php
}
$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 LIMIT 40 OFFSET ?  ');
$test->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.($debut+40).'%'));
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}
drawFooter();
 ?>
