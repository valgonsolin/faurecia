<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('');

function img($int){
    if($int>0){
      return '<img src="ressources/checked.png" style="height: 20px;" >';
    }else{
        return '<img src="ressources/cancel.png" style="height: 20px;" >';
    }
}

$a_vote=0;
$idee=-1;



if(empty($_SESSION['login'])){ ?>
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

  if($a_vote>0){
      $Qy = $bdd->prepare('SELECT COUNT(id) AS nb FROM votes_idees WHERE personne= ? AND idee= ? ');
      $Qy->execute(array($_SESSION['id'],  $a_vote));
      $nb= $Qy->fetch();
      if($nb['nb']>0){warning("ERREUR","vous avez deja voté pour cette idée");
        }else{$queryy=$bdd->prepare('INSERT INTO votes_idees( personne, idee) VALUES(:personne , :idee )');
                                                                                $queryy -> execute(array(
                                                                                'personne' => $_SESSION['id'],
                                                                                'idee' => $a_vote,
                                                                                ));
             $qy=$bdd->prepare('UPDATE idees_ameliorations SET vote= ? WHERE id = ?');
             $qy2=$bdd->prepare('SELECT vote as v FROM idees_ameliorations WHERE id = ?');
             $qy2 -> execute(array($a_vote));
             $v=$qy2->fetch();
             $qy -> execute(array($v['v']+1,$a_vote));
            success("SUCCES","Le vote a bien été pris en compte") ;}
  }else{$Qy = $bdd->prepare('SELECT COUNT(id) AS nb FROM idees_ameliorations WHERE id=? ');
  $Qy->execute(array( (- $a_vote)));
  $nb= $Qy->fetch();
  if($nb['nb']=0){warning("ERREUR","vous ne pouvez pas voter pour cette idée");
  }else{$p=(-$a_vote);
        $queryy=$bdd->prepare('DELETE FROM votes_idees WHERE  personne= ? AND idee= ?');
        $queryy -> execute(array($_SESSION['id'],$p));
         $qy=$bdd->prepare('UPDATE idees_ameliorations SET vote= ? WHERE id = ?');
         $qy2=$bdd->prepare('SELECT vote as v FROM idees_ameliorations WHERE id = ?');
         $qy2 -> execute(array((-$a_vote)));
         $v=$qy2->fetch();
         $qy -> execute(array($v['v']-1,(-$a_vote)));
        success("SUCCES","Le vote a bien été supprimé") ;

  }

}
} ?>

<style>
    .conteneur_alerte{
        margin-top:20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .alerte{
        color: #000 ;
        font-size: 15px;
        background-color: #e3e3e3;
        border-color: #ccc;
        border-radius:6px;
        border-width: 1px;
        border-style: solid;
        margin: 5px;
    }
    .alerte:hover{
      opacity:0.7;
    }
    .info_alerte{
        margin: 10px;
        width: 320px;
        padding: 10px;
        border-radius:6px;
        background-color: #FFF;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }

    .couleur{
        margin: 10px;
        width: 320px;
        height: 20px;
        border-radius:3px;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }
    .date_et_titre{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
</style>


<?php

if(isset($_GET['idee'])){
  $idee=$_GET['idee'];
?>


<h2>Idée n°<?php echo $idee ; ?> </h2>


  <?php


    $Query = $bdd->prepare('SELECT * FROM profil LEFT JOIN idees_ameliorations
                            ON profil.id=idees_ameliorations.emmetteur
                            WHERE idees_ameliorations.id= ? ') ;
    $Query->execute(array($idee));
    $Query2= $bdd->prepare('SELECT  nom AS nom_sup, prenom AS prenom_sup FROM profil
          WHERE id= ? ');
    $Query2->execute(array($_SESSION['manager']));



      $emm=$Query->fetch();
      $sup=$Query2->fetch();

      ?>
      <div class="row">
      <div class="col-md-7">
    <a href="details.php?vote=<?php echo $idee;?>" class="btn btn-default"><div class="alerte" >

          <div class="info_alerte">
              <div class="date_et_titre">
                  <h4 style="margin-top: 0px; font-size: 40px;">
                    <?php echo $emm['type']; ?>
                    </h4>
              </div>

              <p><b>Emmeteur : </b><?php echo $emm['nom']; echo "  "; echo $emm['prenom']; ?><br>
                  <b>Manager : </b> <?php echo $sup['nom_sup']; echo "  "; echo $sup["prenom_sup"]; ?> <br>
                  <b>Nombre de vote : </b><?php echo $emm['vote'];?><br>
                  <b>Situation actuelle :</b><?php echo $emm['situation_actuelle'];?><br>
                  <b>Nobre d'idées qu'elle contient : </b><?php echo $emm['nbidees'];?><br>
                  <b><?php if($emm['valide']==1){echo "Cette idée a déja été validé par le manager ";}else{echo "L'idée n'a pas été validé par le manager";} ?></b><br>
                  <b>Situation proposée :</b><?php echo $emm['situation_proposee'];?><br><br><br>
                  <b><?php echo "Cliquez pour voter";?></b><br></p>



          </div>

      </div></a> </div>
      <div class="col-md-5">


        <?php
          if($emm['image'] != NULL){
            $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
            $query -> execute(array($emm['image']));
            $img= $query -> fetch(); ?>
            <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; " alt="Image associée à l'idée">
          <?php } ?>
        </div>
    </div>
    <div class="row"> <div class="col-md-7">
      <div class="row">
        &emsp;Transversalisation : <?php echo(img($emm['transversalisation'])); ?>&emsp;
        retenue : <?php echo(img($emm['retenue'])); ?>&emsp;
      </div>
    </div>

    <div class="col-md-5">
      <?php
        if($emm['image2'] != NULL){
          $query= $bdd -> prepare('SELECT * FROM files WHERE id= ?');
          $query -> execute(array($emm['image']));
          $img= $query -> fetch(); ?>
          <img src="<?php echo $img['chemin']; ?>" style="max-width:100%; max-height:200px; " alt="Image associée à l'idée">
        <?php } ?>

    </div>

    </div>



<?php
}else{

if(isset($_GET['idee2'])){
  $idee=$_GET['idee2'];
?>

<h2>Idée n°<?php echo $idee ; ?> </h2>


  <?php


    $Query = $bdd->prepare('SELECT *, idees_ameliorations.type as typeidee FROM profil LEFT JOIN idees_ameliorations
                            ON profil.id=idees_ameliorations.emmetteur
                            WHERE idees_ameliorations.id= ? ') ;
    $Query->execute(array($idee));
    $Query2= $bdd->prepare('SELECT  nom AS nom_sup, prenom AS prenom_sup FROM profil
          WHERE id= ? ');
    $Query2->execute(array($_SESSION['manager']));

      $Query3= $bdd-> prepare('SELECT  nom AS nom_respo, prenom AS prenom_respo FROM idees_ameliorations JOIN profil
            ON profil.id=idees_ameliorations.superviseur
            WHERE idees_ameliorations.id= ? ');
      $Query3->execute(array($idee));

      $emm=$Query->fetch();
      $sup=$Query2->fetch();
      $respo=$Query3->fetch();
      ?>

      <a href="details.php?vote=<?php echo (-$idee);?>" class="btn btn-default"><div class="alerte" >

          <div class="info_alerte">
              <div class="date_et_titre">
                  <h4 style="margin-top: 0px; font-size: 40px;">
                    <?php echo $emm['typeidee']; ?>
                    </h4>
              </div>

              <p><b>Emmeteur : </b><?php echo $emm['nom']; echo "  "; echo $emm["prenom"]; ?><br>
                  <b>Manager : </b>><?php echo $sup['nom_sup']; echo "  "; echo $sup["prenom_sup"]; ?><br>
                  <b>Nombre de vote : </b><?php echo $emm['vote'];?><br>
                  <b>Situation actuelle :</b><?php echo $emm['situation_actuelle'];?><br>
                  <b>Nobre d'idées qu'elle contient : </b><?php echo $emm['nbidees'];?><br>
                  <b><?php if($emm['valide']==1){echo "Cette idée a déja été validé par le manager ";}else{echo "L'idée n'a pas été validé par le manager";} ?><br>
                  <b>Situation proposée :</b><?php echo $emm['situation_proposee'];?><br><br><br>
                  <b><?php echo "Cliquez pour retirer vote";?></b><br></p>


          </div>

      </div></a>


<?php
}
}
}


drawFooter();
 ?>
