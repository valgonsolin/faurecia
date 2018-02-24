<?php
include_once "needed.php";
include_once "../../needed.php";

drawHeader('RH');
drawMenu('admin');
$datetime = date("Y-m-d");
?>

<script src="jquery1.js"></script>
<script src="jquery2.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker({ minDate: -20, maxDate: "+48M +10D", changeMonth: true,
      changeYear: true });
  } );
</script>


<br><h2>Ajout de formations </h2>

<?php


if(empty($_SESSION['login']))
{ ?>
  <h2>Idées</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=RH/formations/index.php"><button class="btn btn-default">Se connecter</button></a>
<?php
}


if(!empty($_POST)){
  echo $_POST['date_deb'];
}

if(!$_SESSION['formations']){
  echo "<p> Vous n'avez pas accées à cette partie, seuls les RH ont accés à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
}else{ ?>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="suppression.php" class="bouton_menu">Modification/Suppression</a>
  </div>

  <br><br>
  <form method="post" style="margin-top:20px;" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group">

          <label>Training Title</label>
            <select name="title" class="form-control">
              <option value="Recyclage CACES 2 et 3n" selected="selected">Recyclage CACES 2 et 3</option>
              <option value="Recyclage SST Port des EPI">Recyclage SST Port des EPI</option>
              <option value="Port des EPI">Port des EPI</option>
              <option value="Recyclage habilitations électriques BR et HRs">Recyclage habilitations électriques BR et HR</option>
              <option value="Recyclage BE manœuvre+ initiale H0V">Recyclage BE manœuvre+ initiale H0V</option>
              <option value="Anglais">Anglais</option>
              <option value="Recyclage BE manœuvre">Recyclage BE manœuvre</option>
              <option value="Recyclage SST">Recyclage SST</option>
              <option value="Welding Technology">Welding Technology</option>
              <option value="FES Outils : QRCI 8D">FES Outils : QRCI 8D</option>
              <option value="Programmation cintrage">Programmation cintrage</option>
              <option value="EE Fundamentals">EE Fundamentals</option>
              <option value="Young female Manager Program">Young female Manager Program</option>
            </select>
        </div>
      </div>
      <div class="col-md-3">
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <p>La date de fin de formation pendra automatiquement la valeur date de début +6mois</p>
      </div>
      <div class="col-md-3">
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="form-group"><label>Date de début de la formation </label>
          <input type="text" id="datepicker" name="date_deb" class="form-control">
        </div>
      </div>
      <div class="col-md-3">
      </div>
    </div>

  </form>

<br><br><br><br><br><br><br><br>
<?php  }

?>



<?php
drawFooter();
?>
