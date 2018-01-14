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
             $qy -> execute(array($v['v']+1,$a_vote));
            success("SUCCES","Le vote a bien été pris en compte") ;}
  }
}
?>

<h2>Idée n°<?php echo $idee ; ?> </h2>


  <?php


    $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
                            ON profil.id=idees_ameliorations.emmetteur
                            WHERE idees_ameliorations.id= ? ') ;
    $Query->execute(array($idee));
    $Query2= $bdd->prepare('SELECT  nom AS nom_sup, prenom AS prenom_sup FROM idees_ameliorations JOIN profil
          ON profil.id=idees_ameliorations.superviseur
          WHERE idees_ameliorations.id= ? ');
    $Query2->execute(array($idee));

      $Query3= $bdd-> prepare('SELECT  nom AS nom_respo, prenom AS prenom_respo FROM idees_ameliorations JOIN profil
            ON profil.id=idees_ameliorations.superviseur
            WHERE idees_ameliorations.id= ? ');
      $Query3->execute(array($idee));

      $emm=$Query->fetch();
      $sup=$Query2->fetch();
      $respo=$Query3->fetch();
      ?>

      <table class="table" >
      <tbody>
      <tr>  <td style="width: 50%; ">Nom emmetteur</td> <td style="width: 50% "> <?php echo $emm['nom']; ?> </td></tr>
      <tr>  <td style="width: 50% ;">Prénom emmetteur</td> <td style="width: 50% "><?php echo $emm['prenom']; ?></td></tr>
      <tr>  <td style="width: 50% ;">Nom superviseur</td> <td style="width: 50%;"> <?php echo $sup['nom_sup']; ?> </td></tr>
      <tr>  <td style="width: 50% ;">Prénom superviseur</td> <td style="width: 50%;"><?php echo $sup['prenom_sup']; ?></td></tr>
      <tr>  <td style="width: 50%;">Type</td><td style="width: 50%;"><?php echo $emm['type']; ?></td></tr>
      <tr>  <td style="width: 50%;">Transversalisation</td><td style="width: 50%;"><?php echo $emm['transversalisation']; ?></td></tr>
      <tr>  <td style="width: 50%;">Retenue</td> <td style="width: 50%;"><?php echo $emm['retenue']; ?></td></tr>
      <tr>  <td style="width: 50%;">Respo réalisation</td><td style="width: 50%;"><?php echo $respo['prenom_respo']; echo "  "; echo $respo['nom_respo']  ?></td></tr>
      <tr>  <td style="width: 50%;">Date réalisation</td> <td style="width: 50%;"><?php echo $emm['date_rea']; ?></td></tr>
      <tr>  <td style="width: 50%;">Score</td><td style="width: 50%;"><?php echo $emm['vote']; ?></td></tr>
      <tr>  <td style="width: 50%;">Situation actuelle </td> <td style="width: 50%;"><?php echo $emm['situation_actuelle']; ?></td></tr>
      <tr>  <td style="width: 50%;">Situation prposée</td><td style="width: 50%;"><?php echo $emm['situation_proposee']; ?></td></tr>





</tbody>
  </table>

<?php

$Qy2 = $bdd->prepare('SELECT COUNT(*) as n FROM votes_idees WHERE personne= ? AND idee= ?');
$Qy2->execute(array($_SESSION['id'],  $idee));

$s=$Qy2->fetch();

if($s['n']==0){
?>
    <a href="details.php?idee=<?php echo $idee;?>&amp;vote=<?php echo $idee;?>" class="btn btn-default">Cliquez pour voter pour cette idée</a>



<?php
  }
}


drawFooter();
 ?>
