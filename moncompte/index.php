<?php
include_once "../needed.php";

drawHeader('moncompte');

echo crypt("root","faureciabeaulieu");
?>
<h2>Mon Compte</h2>
<?php
if(empty($_SESSION['login'])){
  echo "<a class='btn btn-default' href='identification.php'>Connexion</a><a href='".$url."' class='btn btn-default pull-right'>Accueil</a>";
}else{
  ?>
<p>Connecté en tant que <?php echo $_SESSION['nom']."  ".$_SESSION['prenom']; ?></p>
<form method="post" class="form-group">
  <label>Ancien mot de passe</label>
  <input type="password" name="old" class="form-control">
  <label>Nouveau mot de passe</label>
  <input type="password" name="new1" class="form-control">
  <label>Confirmation</label>
  <input type="password" name="new2" class="form-control">
  <input type="submit" class="btn btn-default" value="Modifier">
<a href="deconnexion.php" class="btn btn-default pull-right">Déconnexion</a>
</form>

<?php
}
drawFooter();
