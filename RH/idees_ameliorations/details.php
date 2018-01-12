<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('');

$a_vote=-1;
$idee=$_GET['idee'];

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


if(isset($_GET['vote'])){
  $a_vote=$_GET['vote'];
  $idee=$_GET['vote'];

  if($a_vote>0){
      $Qy = $bdd->prepare('SELECT COUNT(id) AS nb FROM votes_idees WHERE personne= ? AND idee= ? ');
      $Qy->execute(array($_SESSION['id'],  $a_vote));
      $nb= $Qy->fetch();
      if($nb['nb']>0){warning("ERREUR","vous avez deja voté pour cette idée");
        }else{$queryy=$bdd->prepare('INSERT INTO votes_idees( personne, idee) VALUES(:personne , :idee )');
                                                                                $queryy -> execute(array(
                                                                                'personne' => $_SESSION['id'],
                                                                                'idee' => $idee,
                                                                                ));
             $qy=$bdd->prepare('UPDATE idees_ameliorations SET vote= ? WHERE id = ?');
             $qy2=$bdd->prepare('SELECT vote as v FROM idees_ameliorations WHERE id = ?');
             $qy2 -> execute(array($a_vote));
             $v=$qy2->fetch();
             $qy2 -> execute(array($v['v']+1,$a_vote));
            success("SUCCES","Le vote a bien été pris en compte") ;}
  }
}
?>

<h2>Idée n°<?php echo $idee ; ?> </h2>


  <table class="table">
  <thead class="thead">
  <tr>  <th>Nom emmetteur</th> </tr>
  <tr>  <th>Prénom emmetteur</th></tr>
  <tr>  <th>Nom superviseur</th> </tr>
  <tr>  <th>Prénom superviseur</th></tr>
  <tr>  <th style="width: 70px;">Type</th></tr>
  <tr>  <th style="width: 70px;">Transversalisation</th></tr>
  <tr>  <th style="width: 70px;">Retenue</th></tr>
  <tr>  <th style="width: 70px;">Respo réalisation</th></tr>
  <tr>  <th style="width: 30px;">Date réalisation</th></tr>
  <tr>  <th style="width: 30px;">Score</th></tr>
  <tr>  <th style="width: 30px;"> Situation actuelle </th></tr>
  <tr>  <th style="width: 30px;">Situation prposée</th></tr>

  </tr>
  </thead>

  <?php


    $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
                            ON profil.id=idees_ameliorations.emmetteur
                            WHERE id= ? ') ;
        $Query->execute(array($idee));
    $Query2= $bdd->prepare('SELECT  nom AS nom_sup, prenom AS prenom_sup FROM idees_ameliorations JOIN profil
          ON profil.id=idees_ameliorations.superviseur
          WHERE id= ? ');
    $Query2->execute(array($idee));

      $Query3= $bdd-> prepare('SELECT  nom AS nom_respo, prenom AS prenom_respo FROM idees_ameliorations JOIN profil
            ON profil.id=idees_ameliorations.superviseur
            WHERE id= ? ');
      $Query3->execute(array($idee));
        ?>

<tbody>

<?php  while ($Data = $Query->fetch() && $Data2 = $Query2->fetch() && $Data3 = $Query3->fetch()) {

      ?>

      <tr><td> <?php echo $Data['nom']; ?> </td></tr>
      <tr><td><?php echo $Data['prenom']; ?></td></tr>
      <tr><td> <?php echo $Data2['nom_sup']; ?> </td></tr>
      <tr><td><?php echo $Data2['prenom_sup']; ?></td></tr>
      <tr><td><?php echo $Data['type']; ?></td></tr>
      <tr><td><?php echo $Data['transversaliation']; ?></td></tr>
      <tr><td><?php echo $Data['retenue']; ?></td></tr>
      <tr><td><?php echo $Data3['prenom_respo']; echo "  "; echo $Data3['nom_respo']  ?></td></tr>
      <tr><td><?php echo $Data['date_rea']; ?></td></tr>
      <tr><td><?php echo $Data['vote']; ?></td></tr>
      <tr><td><?php echo $Data2['situation_actuelle']; ?></td></tr>
      <tr><td><?php echo $Data2['situation_proposee']; ?></td></tr>




<?php }?>
</tbody>
  </table>

<?php

$Qy2 = $bdd->prepare('SELECT * FROM votes_idees WHERE personne= ? AND idee= ?');
$Qy2->execute(array($_SESSION['id'],  $a_vote));
if(!($Qy2->fetch())){
?>
    <a href="details.php?idee=<?php echo $idee;?>&amp;vote=<?php echo $idee;?>" class="btn btn-default">Cliquez pour voter pour cette idée</a>



<?php
  }
}


drawFooter();
 ?>
