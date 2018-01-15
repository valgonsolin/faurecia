<?php
include_once "../needed.php";

drawheader('RH');
if(empty($_SESSION['login'])){ ?>
  <h2>Edition profil</h2>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=moncompte/editer_profil.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="/index.php" class="btn btn-default">Accueil</a>
<?php }else{
  if(! $_SESSION['admin']){
    echo "<h2>Profil</h2>";
    echo "<p>Vous n'avez pas les droits pour accéder à cette page. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a><p>";
  }else{
if(isset($_GET['id'])){
?>

    <h2>Modifier le profil</h2>

<?php
    $Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $Data = $Query->fetch();
    $nom = $Data['nom'];
    $prenom = $Data['prenom'];
    $identifiant = $Data['identifiant'];
    $mo = $Data['mo'];
    $uap = $Data['uap'];
    $tournee = $Data['tournee'];
    $supprime = $Data['supprime'];
    $admin = $Data['admin'];
    $hse = $Data['hse'];
    $rr = $Data['rr'];
    $kamishibai = $Data['kamishibai'];
    $logistique = $Data['logistique'];
    $qualite= $Data['qualite'];
    $idees=$Data['idees'];
    $launchboard=$Data['launchboard'];
}else{
?>
    <h2>Ajouter un profil</h2>

<?php
    $nom = "";
    $prenom = "";
    $identifiant = "";
    $mo = "";
    $uap = "";
    $tournee ="";
    $admin=0;
    $qualite=0;
    $hse=0;
    $rr=0;
    $idees=0;
    $kamishibai=0;
    $logistique=0;
    $launchboard=0;
}
?>
    <form class="form-horizontal" method="post" action="administration.php">
        <?php
        if(isset($_GET['id'])){ ?><input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"> <?php } ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="nom">Nom :</label>
            <div class="col-sm-10">
                <input type="text" name="nom" class="form-control" onkeyup="update()" id="nom" placeholder="Entrer le nom" value="<?php echo $nom; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="prenom">Prénom :</label>
            <div class="col-sm-10">
                <input type="text" name="prenom" onkeyup="update()" class="form-control" id="prenom" placeholder="Entrer le prénom"  value="<?php echo $prenom; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Identifiant :</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control" name="identifiant" id="identifiant" value="<?php echo $identifiant; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tournee">Tournée :</label>
            <div class="col-sm-10">
                <select name='tournee' id="tournee" class="form-control">
                    <option value="A" <?php if($tournee == "A"){echo 'selected="selected"';}?> >A</option>
                    <option value="B" <?php if($tournee == "B"){echo 'selected="selected"';}?> >B</option>
                    <option value="N" <?php if($tournee == "N"){echo 'selected="selected"';}?> >N</option>
                    <option value="J" <?php if($tournee == "J"){echo 'selected="selected"';}?> >J</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="uap">UAP :</label>
            <div class="col-sm-10">
                <select name='uap' id="uap" name="uap" class="form-control">
                    <option value="UAP1" <?php if($uap == 'UAP1'){echo 'selected="selected"';}?> >UAP1</option>
                    <option value="UAP2" <?php if($uap == 'UAP2'){echo 'selected="selected"';}?> >UAP2</option>
                    <option value="UAP3" <?php if($uap == 'UAP3'){echo 'selected="selected"';}?> >UAP3</option>
                    <option value="Support" <?php if($uap == 'Support'){echo 'selected="selected"';}?> >Support</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="mo">MO :</label>
            <div class="col-sm-10">
                <select name='mo' id="mo" name="mo" class="form-control">
                    <option value="MOI" <?php if($mo == "MOI"){echo 'selected="selected"';}?> >MOI</option>
                    <option value="MOD" <?php if($mo == "MOD"){echo 'selected="selected"';}?> >MOD</option>
                    <option value="GAP" <?php if($mo == "GAP"){echo 'selected="selected"';}?> >GAP</option>
                </select>
            </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2">Droits :</label>
          <div class="col-sm-10">
            <input type="hidden" name="admin" value="0">
            <input type="hidden" name="qualite" value="0">
            <input type="hidden" name="rr" value="0">
            <input type="hidden" name="hse" value="0">
            <input type="hidden" name="kamishibai" value="0">
            <input type="hidden" name="idees" value="0">
            <input type="hidden" name="logistique" value="0">
            <input type="hidden" name="launchboard" value="0">
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="admin" <?php if($admin){echo "checked";} ?>>Admin</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="qualite" <?php if($qualite){echo "checked";} ?>>Dojo Qualite</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="rr" <?php if($rr){echo "checked";} ?>>R&amp;R</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="hse" <?php if($hse){echo "checked";} ?>>HSE</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="kamishibai" <?php if($kamishibai){echo "checked";} ?>>Kamishibai</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="idees" <?php if($idees){echo "checked";} ?>>Idées améliorations</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="logistique" <?php if($logistique){echo "checked";} ?>>Logistique</label>
            <label class="control-label checkbox-inline"><input type="checkbox" value="1" name="launchboard" <?php if($launchboard){echo "checked";} ?>>Launchboard</label>
          </div>
        </div>
        <?php if(!isset($_GET['id'])){ ?>
        <div class="form-group">
          <label class="control-label col-sm-2">Mot de passe :</label>
          <div class="col-sm-10"><input type="password" name="new1" id="password" class="form-control" value="faurecia"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2">Confirmation :</label>
          <div id="print" class="col-sm-10">
            <input type="password" name="new2" id="confirm_password" class="form-control" value="faurecia">
            <small class="form-text text-muted">Par défault : Faurecia</small>
          </div>
        </div>

      <?php } ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="modifier" class="btn btn-default">Valider</button>
                <?php if(isset($_GET['id'])){ ?>
                <div class="btn btn-default" data-toggle="modal" data-target="#modal">Modifier le mot de passe</div>
              <?php } ?>
                <a href="administration.php" class="btn btn-default pull-right">Retour</a>
              <?php  if (isset($_GET['id'])){
                  if($supprime){ ?>
                    <button onclick="return confirm('Êtes-vous sûr de vouloir réactiver ce profil ? ')" type="submit" name="reactiver" class="btn btn-default pull-right">Réactiver le profil</button>
                    <?php
                  }else{
                    ?>
                    <button onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ? ')" type="submit" name="supprimer" class="btn btn-default pull-right">Supprimer le profil</button>
                    <?php
                  }
                }
                ?>
            </div>
        </div>
        <?php if(isset($_GET['id'])){ ?>
        <div id="modal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modifier le mot de passe</h4>
              </div>
              <div class="modal-body">
                <form id ="mdp" method="post" class="form-group" action="administration.php">
                  <label>Nouveau mot de passe</label>
                  <input type="password" name="new1" id="password" class="form-control">
                  <label>Confirmation</label>
                  <div id="print"><input type="password" name="new2" id="confirm_password" class="form-control"></div><br>
                  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                  <input type="submit" name="password" onclick="return confirm('Êtes-vous sûr de vouloir modifier le mot de passe ? ')" class="btn btn-default form-control" value="Modifier">
                </form>
              </div>
            </div>
          </div>
        </div><?php } ?>
    </form>
    <script>
    function update(){
      $("#identifiant").val($("#nom").val() + "." + $("#prenom").val());
    }
    window.onload = update();
    var password = document.getElementById("password")
      , confirm_password = document.getElementById("confirm_password"),
      div = document.getElementById("print");

    function validatePassword(){
      if(password.value != confirm_password.value) {
        div.classList.add("has-error");
        div.classList.remove("has-success");
      } else {
        div.classList.remove("has-error");
        div.classList.add("has-success");
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
    $('body').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) {
        e.preventDefault();
        return false;
      }
    });
    </script>

<?php }
}
drawFooter();
