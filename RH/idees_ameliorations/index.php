<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('les_idees');


$recherche = "";
$debut=0;
$a_vote=-1;

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}

if(isset($_GET['nb'])){
  $debut=$_GET['nb'];
  if
}

if(isset($_GET['vote'])){
  $a_vote=$_GET['vote'];
  if($a_vote>0){
      $Qy = $bdd->prepare('SELECT FROM votes_idees WHERE personne= ? AND idee= ?')
      $Qy->execute(array('%'.$SESSION['id'].'%',  '%'.$a_vote.'%'));
      if($Qy->fetch()){warning("ERREUR","vous avez deja voté pour cette idée");}else{$bdd->exec(INSERT INTO votes_idees(id, personne, idee) VALUES('','%'.$SESSION['id'].'%','%'.$a_vote.'%')); success("SUCCES","Le vote a bien été pris en compte");}
  }
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
      WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 ORDER BY vote LIMIT 40 OFFSET ? WHERE MONTH(date)==MONTH(CURDATE()) ') ;
      $Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.$debut.'%'));


while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td> <?php echo $Data['nom']; ?> </td>
        <td><?php echo $Data['prenom']; ?></td>
        <td><?php echo $Data['titre']; ?></td>
        <td><?php echo $Data['date']; ?></td>
        <td><?php echo $Data['vote']; ?></td>
        <td><?php if($Data['vote']){echo "x";}else{echo "";} ?></td>
        <td class="clickable" title="Cliquez voter pour cette idée" onclick="window.location='index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-40;?>&amp;vote=<?php echo $Data['idees_ameliorations.id'] ;?>'">Details</td>


    </tr>


    <?php
}
?>
</tbody>
</table>

<?php
if($debut > 39){
  ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-40;?>&amp;vote=<?php echo $a_vote;?>" class="btn btn-default">Elements précédents</a>
<?php
}
$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.personne = profil.id
    WHERE (nom LIKE ? or prenom LIKE ?) and supprime = 0 LIMIT 40 OFFSET ?  ');
$test->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.($debut+40).'%'));
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>&amp;vote=<?php echo $a_vote;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}
drawFooter();
 ?>
