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
  <h2>Idees</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/idees_ameliorations"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php
}
else
{
?>

<h2>Idees</h2>

  <form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Modifier/Supprimer</a>
  <a href="classement.php" class="btn btn-default pull-right">Classement du mois</a>
</form>


<table class="table">
<thead class="thead">
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th style="width: 70px;">Titre</th>
    <th style="width: 30px;">Date</th>
    <th style="width: 30px;">Nombres de Votes</th>
    <th style="width: 30px;"> Etat</th>
    <th style="width: 30px;">Details</th>

</tr>
</thead>
<tbody>

<?php


  $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
      ON idees_ameliorations.personne = profil.id
      WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 LIMIT 40 OFFSET ?  ') ;
      $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.$debut.'%'));


while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td> <?php echo $Data['nom']; ?> </td>
        <td><?php echo $Data['prenom']; ?></td>
        <td><?php echo $Data['titre']; ?></td>
        <td><?php echo $Data['date']; ?></td>
        <td><?php echo $Data['vote']; ?></td>
        <td><?php if($Data['vote']){echo "oui";}else{echo "non";} ?></td>
        <td class="clickable" title="Cliquez pour accéder au details" onclick="window.location='details.php?id=<?php echo $Data['idees_ameliorations.id']; ?>'">Details</td>


    </tr>


    <?php
}
?>
</tbody>
</table>

<?php
if($debut > 39){
  ?>
  <a href="pieces.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-40;?>" class="btn btn-default">Elements précédents</a>
<?php
}
$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.personne = profil.id
    WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 LIMIT 40 OFFSET ?  ');
$test->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.($debut+40).'%'));
if($test -> fetch()){ ?>
  <a href="pieces.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}
drawFooter();
 ?>
