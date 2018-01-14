<?php
include_once "../needed.php";

drawHeader('moncompte');
function img($int){
    if($int>0){
      return '<img src="ressources/checked.png" style="height: 20px;" >';
    }else{
        return '<img src="ressources/cancel.png" style="height: 20px;" >';
    }
}
echo "<h2>Mon Compte</h2>";

if(!empty($_POST)){
  if(isset($_POST['password'])){
  $query = $bdd -> prepare('SELECT * FROM profil WHERE id = ?');
  $query -> execute(array($_SESSION['id']));
  $Data = $query -> fetch();
  if(crypt($_POST['old'],"faurecia_beaulieu") == $Data['password']){
    if($_POST['new1'] == $_POST['new2']){
      $q = $bdd -> prepare('UPDATE profil SET password = ? WHERE id = ?');
      if($q -> execute(array(crypt($_POST['new1'],"faureciabeaulieu"),$_SESSION['id']))){
        success('Modifié','Le mot de passe a été modifié.');
      }else{
        warning('Erreur','Erreur de session. Veuillez réessayez.');
      }
    }else{
      warning('Erreur','Les mots de passe ne sont pas identiques');
    }
  }else{
    warning('Erreur','Mauvais de mot de passe.');
  }
  }
  if(isset($_POST['submit'])){
    $Query = $bdd->prepare('UPDATE profil SET nom = ?, prenom = ?, mo = ?, uap = ?, tournee = ? WHERE id = ?');
    if($Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_SESSION["id"]))){
      success('Modifié','Le profil a bien été modifié');
    }else{
      warning('Erreur','Erreur de session. Veuillez réessayer.');
    }
  }
}

?>
<?php
if(empty($_SESSION['login'])){
  echo "<a class='btn btn-default' href='identification.php'>Connexion</a><a href='".$url."' class='btn btn-default pull-right'>Accueil</a>";
}else{
  $Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
  $Query->execute(array($_SESSION['id']));
  $Data = $Query->fetch();
  $nom = $Data['nom'];
  $prenom = $Data['prenom'];
  $mo = $Data['mo'];
  $uap = $Data['uap'];
  $tournee = $Data['tournee'];
  ?>
<p>Connecté en tant que <?php echo $_SESSION['nom']."  ".$_SESSION['prenom']; ?></p>
<p><h4>Droits :</h4><b>
  &emsp;&emsp;Admin : <?php echo(img($_SESSION['admin'])); ?>&emsp;&emsp;
  Dojo qualité : <?php echo(img($_SESSION['qualite'])); ?>&emsp;&emsp;
  R&amp;R : <?php echo(img($_SESSION['rr'])); ?>&emsp;&emsp;
  HSE : <?php echo(img($_SESSION['hse'])); ?>&emsp;&emsp;
  Kamishibai : <?php echo(img($_SESSION['kamishibai'])); ?>
  Idées améliorations : <?php echo(img($_SESSION['idees'])); ?>
  Logistique : <?php echo(img($_SESSION['logistique'])); ?></b>
</p>
<div class="btn btn-default" data-toggle="modal" data-target="#modal">Modifier le mot de passe</div>
<div data-toggle="modal" data-target="#modal2" class="btn btn-default">Modifier mon profil</div>
<a href="deconnexion.php" class="btn btn-default pull-right">Déconnexion</a>
<div id="modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le mot de passe</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group">
          <label>Ancien mot de passe</label>
          <input type="password" name="old" class="form-control">
          <label>Nouveau mot de passe</label>
          <input type="password" name="new1" id="password" class="form-control">
          <label>Confirmation</label>
          <div id="print"><input type="password" name="new2" id="confirm_password" class="form-control"></div><br>
          <input type="submit" name="password"class="btn btn-default form-control" value="Modifier">
        </form>
      </div>
    </div>
  </div>
</div>
<div id="modal2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifier le profil</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label class="control-label col-sm-2" for="nom">Nom :</label>
                <div class="col-sm-10">
                    <input type="text" name="nom" class="form-control" id="nom" placeholder="Entrer le nom" value="<?php echo $nom; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="prenom">Prénom :</label>
                <div class="col-sm-10">
                    <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Entrer le prénom"  value="<?php echo $prenom; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="tournee">Tournée :</label>
                <div class="col-sm-10">
                    <select name='tournee' id="tournee" class="form-control">$
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
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submit" class="btn btn-default">Valider</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
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
</script>

<?php
}
drawFooter();
?>
