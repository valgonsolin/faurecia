<?php
include_once "../needed.php";

function drawMenu($selected){
    global $url;
    global $bdd;?>
    <div id="nav_">

        <div id="boutons_nav">
          <?php
          if(isset($_SESSION['login'])){
          $Query = $bdd -> prepare('SELECT * FROM profil
                              LEFT JOIN (SELECT id as id_reponse, profil as id_profil FROM codir_kamishibai_reponse WHERE cloture = 0 GROUP BY id_profil) AS reponse
                              ON reponse.id_profil = profil.id
                              WHERE supprime = 0 and profil.id = ?');
          $Query->execute(array($_SESSION['id']));
          $Data = $Query -> fetch();
          if (is_null($Data['id_reponse'])) {
              ?>
              <a href="<?php echo $url; ?>/codir/kamishibai/tirer_carte.php?profil=<?php echo $Data['id']; ?>"><div class="bouton_nav   <?php if($selected=='carte'){echo ' bouton_nav_selected';} ?>"> Tirer une carte</div></a>
              <?php
          }else {
              ?>
              <a href="<?php echo $url; ?>/codir/kamishibai/reponse.php?id=<?php echo $Data['id_reponse']; ?>"><div class="bouton_nav   <?php if($selected=='carte'){echo ' bouton_nav_selected';} ?>">Voir ma carte</div></a>
              <?php
          }}
              ?>


            <a href="<?php echo $url; ?>/codir/kamishibai/index.php"><div class="bouton_nav   <?php if($selected=='kamishibai'){echo ' bouton_nav_selected';} ?>"> Kamishibai</div></a>
            <?php if(isset($_SESSION['login']) && $_SESSION['kamishibai']){ ?>
            <a href="<?php echo $url; ?>/codir/kamishibai/gestion.php"><div class="bouton_nav   <?php if($selected=='gestion'){echo ' bouton_nav_selected';} ?>"> Gestion cartes</div></a>
          <?php } ?></div>
    </div>
<?php
}
