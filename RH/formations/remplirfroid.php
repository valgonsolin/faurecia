<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('mana');



if(!(empty($_POST))){
  $qyy = $bdd -> prepare('SELECT COUNT(*) as n FROM demande_formation WHERE formation= ? AND evalfroid= 1 ');
  $qyy->execute(array($_POST['id2']));
  $c=$qyy->fetch();
  if($c['n']>0){warning('Erreur','Vous avez déja validé la demande formation'); }else{
    // t'en etais à l'insert into+update (valide)
  $query = $bdd -> prepare('INSERT INTO evaluation_froid(demande,resultats,causes,plan)
  VALUES (:demande,:resultats,:causes,:plan)');

  if($query -> execute(array(
    'demande' => $_POST['id'],
    'resultats' =>$_POST['resultats'],
    'causes' =>$_POST['causes'],
    'plan'=>$_POST['plan'],
  )) )   {
    success('Ajouté','La demande a bien été effectuée.');
    $qyy=$bdd->prepare('UPDATE demande_formations SET evalfroid = 1 WHERE id= ? ');
    $qyy->execute(array($_POST['id']));
  }else{
    warning('Erreur','Imossible de faire la demande');

  }
}
}


if(isset($_GET['aremplir'])){
  $Query2 = $bdd->prepare('SELECT *,demande_formations.id as id1,formations_dispo.id as id2  FROM formations_dispo JOIN demande_formations ON formations_dispo.id=demande_formations.formation WHERE  demande_formations.id= :f ') ;

  $Query2 ->bindValue(':f',(int) $_GET['aremplir'], PDO::PARAM_INT);
  $Query2->execute();
  $Data= $Query2->fetch(); ?>


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
        opacity:1;
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
    <div class="conteneur_alerte">
          <div class="alerte" >
            <div class="info_alerte">
                  <div class="date_et_titre">
                      <h4 style="margin-top: 0px; font-size: 40px;">
                        <?php echo $Data['trainingtitle']; ?>
                        </h4>
                  </div>

                  <p><b>Date de début : </b><?php echo $Data['date_deb'];?><br>
                      <b>Date de fin: </b><?php echo $Data['date_fin'];?><br>
                      <b>Date d'ajout : </b><?php echo $Data['date_ajout'];?><br>
                      <b>Origine du besoin : </b><?php echo $Data['origine']; ?><br>
                      <br><br></p>


              </div>

          </div>


          <?php
          $qq=$bdd->prepare('SELECT * FROM profil WHERE id = ?');
          $qq->execute(array($Data['demandeur']));
          $Data2=$qq->fetch();
          ?>
          <div class="alerte" >
            <div class="info_alerte">
                  <div class="date_et_titre">
                      <h4 style="margin-top: 0px; font-size: 40px;">
                        <?php echo "Demandeur de la formation :" ; ?>
                        </h4>
                  </div>

                  <p><b>Nom : </b><?php echo $Data2['nom'];?><br>
                      <b>Prénom : </b><?php echo $Data2['prenom'];?><br>
                      <b>UAP : </b><?php echo $Data2['uap'];?><br>
                      <b>MO : </b><?php echo $Data2['mo']; ?><br>
                      <b>Tournée : </b><?php echo $Data2['tournee']; ?><br>
                      <br><br></p>


              </div>

          </div>

        </div>

        <form action="remplirfroid.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
        <div class="row">

          <div class="col-md-5">
          <div class="form-group">
            <INPUT TYPE="radio" NAME= "resultats" VALUE="atteints" > Atteints
            <INPUT TYPE="radio" NAME= "resultats" VALUE="partiellement atteints"> Partiellement atteints
            <INPUT TYPE="radio" NAME= "resultats" VALUE="non atteints">Non atteints
          </div>
        </div>
        <div class="col-md-5 col-md-offset-2">
          <div class="form-group">
            <label>Causes:     </label>
            <input name="causes" class="form-control" type="text">
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label>Plan d'action:     </label>
            <input name="plan" class="form-control" type="text">
          </div>
        </div>
        <div class="col-md-5 col-md-offset-2">
          <br><br><br>
          <input type="hidden" name="id" value="<?php echo $Data["id1"] ?>" >
          <input type="hidden" name="id2" value="<?php echo $Data["id2"] ?>" >
          <input value="Valider la demande de formation" class="btn btn-default" type="submit">

        </div>
      </div>

        </form>


<?php

}
drawFooter();
?>
