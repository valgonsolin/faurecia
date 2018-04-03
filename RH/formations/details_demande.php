<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('collab');



if(!(empty($_POST))){
  $qyy = $bdd -> prepare('SELECT COUNT(*) as n FROM demande_formations WHERE formation= ? and demandeur= ? ');
  $qyy->execute(array($_POST['id'], $_SESSION['id']));
  $c=$qyy->fetch();
  if($c['n']>0){warning('Erreur','Vous avez déja demandé la formation'); }else{
  $query = $bdd -> prepare('INSERT INTO demande_formations(formation,demandeur,origine) VALUES (:f,:d,:o)');

  if($query -> execute(array(
    'f' => $_POST['id'],
    'd' =>$_SESSION['id'],
    'o' =>$_POST['origine'],
  )) )   {

    success('Ajouté','La demande a bien été effectuée.');
  }else{
    warning('Erreur','Imossible de faire la demande');

  }
}
}


if(isset($_GET['demande'])){
  $Query2 = $bdd->prepare('SELECT * FROM formations_dispo WHERE  id= :f ') ;

  $Query2 ->bindValue(':f',(int) $_GET['demande'], PDO::PARAM_INT);
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
      <div class="row">
        <div class="col-md-5">
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
                      <b>Date d'ajout : </b><?php echo $Data['date_ajout'];?>
                      <br><br><br></p>


              </div>

          </div>
        </div>
        </div>

        <div class="col-md-5 col-md-offset-2">
          <form action="details_demande.php" method="post" style="margin-top:20px;" enctype="multipart/form-data">
            <div class="form-group">
          		<label>Origine du besoin :     </label>
                  <select name="origine" class="form-control">
                  <option value="PLan formation" selected="selected">Plan formation</option>
                  <option value="Entretien individuel" >Entretien individuel</option>
                  <option value="Staffing review">Staffing review</option>
                  <option value="Autre">Autre</option>
                  </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" >
            <input value="Demander la formation" class="btn btn-default" type="submit">
          </form>
        </div>


      </div>


<?php

}
drawFooter();
?>
