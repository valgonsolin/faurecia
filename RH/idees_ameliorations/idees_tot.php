<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('tot_idees');


$recherche = -1;
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

<h2>Idées </h2>

  <form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <select class="form-control" name="recherche" >
      <?php
      $profil = $bdd -> query('SELECT * FROM profil');
      while($personne = $profil -> fetch()){ ?>
        <option value="<?php echo $personne['id']; ?>" ><?php echo $personne['nom']." ".$personne['prenom']; ?></option>
    <?php  } ?>
    </select>


  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <a href="ajout.php" class="btn btn-default pull-right">Espace administration</a>
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

if($recherche>=0){
$Query = $bdd->prepare('SELECT nom,prenom,type,date_rea,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE profil.id= ? and supprime = 0  ORDER BY date_rea LIMIT 40 OFFSET ? ') ;
$Query->execute(array($recherche,$debut));}
else{$Query = $bdd->prepare('SELECT nom,prenom,type,date_rea,vote,idees_ameliorations.id AS id1 FROM idees_ameliorations LEFT JOIN profil  ON idees_ameliorations.emmetteur = profil.id  WHERE supprime = 0  ORDER BY date_rea LIMIT 40 OFFSET ? ') ;
$Query->execute(array($debut));}}

while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td> <?php echo $Data['nom']; ?> </td>
        <td><?php echo $Data['prenom']; ?></td>
        <td><?php echo $Data['type']; ?></td>
        <td><?php echo $Data['date_rea']; ?></td>
        <td><?php echo $Data['vote']; ?></td>
        <td><?php

        $Qy = $bdd->prepare('SELECT * FROM votes_idees WHERE personne= ? AND idee= ?');
        $Qy->execute(array($_SESSION['id'],  $Data['id1']));

        if($Qy->fetch()){echo "<span style='font-size: 200%;'>&check;</span>";}else{echo "<span style='font-size: 150%;'>&#10008;</span>";} ?>
      </td>
      <?php
        if($Qy->fetch()){echo "<td class="clickable" title="Cliquez pour voter/voir le detail " onclick="window.location='details.php?idee=<?php echo $Data['id1'] ;?>'">Voter/Voir details</td>";}
        else{echo "<td class="clickable" title="Cliquez pour retirer vote/voir le detail " onclick="window.location='details.php?idee2=<?php echo $Data['id1'] ;?>'">Retirer vote/Voir details</td>";}?>


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

if($recherche>=0){
$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE profil.id= ? and supprime = 0 and idees_ameliorations.id >= ? LIMIT 40   ');
$test->execute(array($recherche, ($debut+40)));
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>" class="btn btn-default">Elements suivants</a>

<?php
}
}else{$test = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
    ON idees_ameliorations.emmetteur = profil.id
    WHERE supprime = 0 and idees_ameliorations.id >= ? LIMIT 40   ');
$test->execute(array( ($debut+40)));
if($test -> fetch()){ ?>
  <a href="index.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+40;?>" class="btn btn-default">Elements suivants</a>

<?php
}
}

}
drawFooter();
 ?>
