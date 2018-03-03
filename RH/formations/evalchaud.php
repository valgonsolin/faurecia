<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('mana');



if(!(empty($_POST))){
  $qyy = $bdd -> prepare('SELECT COUNT(*) as n FROM demande_formations WHERE formation= ? AND valide= 1 ');
  $qyy->execute(array($_POST['id2']));
  $c=$qyy->fetch();
  if($c['n']>0){warning('Erreur','Vous avez déja validé la demande formation'); }else{
    // t'en etais à l'insert into+update (valide)
  $query = $bdd -> prepare('INSERT INTO validation_formations(interne,priorite,impact,resultat,objectif,decembre,novembre,octobre,septembre,aout,juillet,juin,mai,avril,mars,fevrier,janvier,demande)
  VALUES (:interne,:priorite,:impact,:resultat,:objectif,:decembre,:novembre,:octobre,:septembre,:aout,:juillet,:juin,:mai,:avril,:mars,:fevrier,:janvier,:demande)');

  if($query -> execute(array(
    'interne' => $_POST['interne'],
    'priorite' =>$_POST['priorite'],
    'impact' =>$_POST['impact'],
    'resultat'=>$_POST['resultats'],
    'objectif'=>$_POST['objectifs'],
    'decembre'=>$_POST['decembre'],
    'novembre'=>$_POST['novembre'],
    'octobre' => $_POST['octobre'],
    'septembre'=>$_POST['septembre'],
    'aout'=>$_POST['aout'],
    'juillet'=>$_POST['juillet'],
    'juin'=>$_POST['juin'],
    'mai'=>$_POST['mai'],
    'avril'=>$_POST['avril'],
    'mars'=>$_POST['mars'],
    'fevrier'=>$_POST['fevrier'],
    'janvier'=>$_POST['janvier'],
    'demande'=>$_POST['id'],
  )) )   {
    success('Ajouté','La demande a bien été effectuée.');
    $qyy=$bdd->prepare('UPDATE demande_formations SET valide = 1 WHERE id= ? ');
    $qyy->execute(array($_POST['id']));
  }else{
    warning('Erreur','Imossible de faire la demande');

  }
}
}


if(isset($_GET['avalide'])){
  $Query2 = $bdd->prepare('SELECT *,demande_formations.id as id1,formations_dispo.id as id2  FROM formations_dispo JOIN demande_formations ON formations_dispo.id=demande_formations.formation WHERE  demande_formations.id= :f ') ;

  $Query2 ->bindValue(':f',(int) $_GET['avalide'], PDO::PARAM_INT);
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

        <form action="evalchaud.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
        <div class="row">



        <div class="col-md-5 col-md-offset-1">

          <div class="row">
            <div class="form-group">
              <label>Interne <br>( externe par defaut) :     </label><label style="margin-left:20px">
                <input type="hidden" value="0" name="interne">
                <input name="interne" type="checkbox" value="1"> Oui</label>

            </div>
          </div>
        </div>

        <div class="col-md-5 col-md-offset-1">
          <div class="row">
            <div class="form-group">
              <label>Objectifs :     </label>
                <input name="objectifs" class="form-control" type="text">
              </div>
          </div>
          <div class="row">
            <div class="form-group">
              <label>Résultats :     </label>
                <input name="resultats" class="form-control" type="text">
              </div>
          </div>
        </div>

        </div>

        <br><br>



        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label>Impact de la formation :     </label>
              <input name="impact" class="form-control" type="text">
            </div>
          </div>
          <div class="col-md-5 col-md-offset-2">
            <div class="form-group">
            <label>Priorité:</label> <br><label style="margin-left:20px">
              <input type="hidden" value="0" name="priorite">
              <input name="priorite" type="checkbox" value="1"> indispensable</label>
            </div>
          </div>
        </div>


        <br><br>


        <div class="row">
          <div class="col-md-4 ">
            <div class="row">
              <b>Mois souhaités:</b>
              <br>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Jan <br></label>
                  <input type="hidden" value="0" name="janvier">
                  <input name="janvier" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Fev</label>
                  <input type="hidden" value="0" name="fevrier">
                  <input name="fevrier" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Mar </label>
                  <input type="hidden" value="0" name="mars">
                  <input name="mars" type="checkbox" value="1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Avr <br></label>
                  <input type="hidden" value="0" name="avril">
                  <input name="avril" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Mai</label>
                  <input type="hidden" value="0" name="mai">
                  <input name="mai" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Juin </label>
                  <input type="hidden" value="0" name="juin">
                  <input name="juin" type="checkbox" value="1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Juil <br></label>
                  <input type="hidden" value="0" name="juillet">
                  <input name="juillet" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Aout</label>
                  <input type="hidden" value="0" name="aout">
                  <input name="aout" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Sept </label>
                  <input type="hidden" value="0" name="septembre">
                  <input name="septembre" type="checkbox" value="1">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Oct <br></label>
                  <input type="hidden" value="0" name="octobre">
                  <input name="octobre" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Nov</label>
                  <input type="hidden" value="0" name="novembre">
                  <input name="novembre" type="checkbox" value="1">
                </div>
              </div>
              <div class="col-md-3 col-md-offset-1">
                <div class="form-group">
                <label>Dec </label>
                  <input type="hidden" value="0" name="decembre">
                  <input name="decembre" type="checkbox" value="1">
                </div>
              </div>
            </div>

          </div>


        <div class="col-md-3 col-md-offset-5">
          <br><br><br>
          <input type="hidden" name="id" value="<?php echo $Data["id1"] ?>" >
          <input type="hidden" name="id2" value="<?php echo $Data["id2"] ?>" >
          <input value="Valider la demande de formation" class="btn btn-default" type="submit">

        </div>

        </form>


<?php

}
drawFooter();
?>
