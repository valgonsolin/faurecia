<?php
include_once "../needed.php";
drawHeader('RH');
echo "<h2>Gestion des Profils</h2>";

function img($int){
    if($int>0){
        return '<img src="ressources/cancel.png" style="height: 20px;" class="center-block">';
    }else{
        return '<img src="ressources/checked.png" style="height: 20px;" class="center-block">';
    }
}
if(empty($_SESSION['login'])){ ?>
  <h4>Vous devez être connecté pour accéder à cette partie.</h4>
  <a href="/moncompte/identification.php?redirection=moncompte/administration.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="<?php echo $url; ?>" class="btn btn-default">Accueil</a>
<?php }else{
  if(!$_SESSION['admin']){
    echo "<p>Vous n'avez pas les droits pour accéder à cette partie. <a href='".$url."' class='btn btn-default pull-right'>Accueil</a></p>";
  }else{
    if (isset($_POST['modifier'])){
        if (isset($_POST['id'])){
            $Query = $bdd->prepare('UPDATE profil SET nom = ?, prenom = ?,identifiant = ?,mail = ?, mo = ?, uap = ?, tournee = ?, admin = ?, qualite = ?, rr = ?, hse = ?, kamishibai = ?, logistique = ?, idees = ?, launchboard = ?, manager = ?, news = ?, formation= ? WHERE id = ?');
            if($Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['identifiant'],$_POST['mail'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_POST['admin'],$_POST['qualite'],$_POST['rr'],$_POST['hse'],$_POST['kamishibai'],$_POST['logistique'],$_POST['idees'],$_POST['launchboard'],$_POST['manager'],$_POST['news'],$_POST['formations'],$_POST['id'])) ) {
              success('Modifié',"Le profil a été modifié.");
            }else{
              warning("Erreur","Veuillez réessayer.");
            }
        }else{
            if($_POST['new1'] == $_POST['new2']){
              $Query = $bdd->prepare('INSERT INTO profil SET nom = ?, prenom = ?,identifiant = ?,mail = ? , mo = ?, uap = ?, tournee = ?, admin = ?, qualite = ?, rr = ?, hse = ?, kamishibai = ?, logistique = ?, idees = ?, launchboard = ?, manager = ?,news = ?,formations=?,password = ?');
              if($Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['identifiant'],$_POST['mail'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_POST['admin'],$_POST['qualite'],$_POST['rr'],$_POST['hse'],$_POST['kamishibai'],$_POST['logistique'],$_POST['idees'],$_POST['launchboard'],$_POST['manager'],$_POST['news'],$_POST['formations'],crypt(strtolower($_POST['new1']),"faureciabeaulieu")))){
                success("Ajouté","Le profil a bien été ajouté.");
              }else{
                warning("Erreur","Veuillez réessayer.");
              }
            }else{
              warning("Erreur","Les mots de passe ne sont pas identiques.");
            }
        }

    }

    if (isset($_POST['supprimer'])){
        if (isset($_POST['id'])){
            $Query = $bdd->prepare('UPDATE profil SET supprime = 1 WHERE id = ?');
            if($Query->execute(array($_POST["id"]))){
              success('Supprimé','Le profil a été supprimé.');
            }else{
              warning("Erreur","Veuillez réessayer.");
            }
        }
    }
    if(isset($_POST['reactiver'])){
      if (isset($_POST['id'])){
          $Query = $bdd->prepare('UPDATE profil SET supprime = 0 WHERE id = ?');
          if($Query->execute(array($_POST["id"]))){
            success("Réactivé","Le profil a été réactivé.");
          }else{
            warning("Erreur","Veuillez réessayer.");
          }
      }
    }
    if(isset($_POST['password'])){
      if($_POST['new1'] == $_POST['new2']){
        $q = $bdd -> prepare('UPDATE profil SET password = ? WHERE id = ?');
        if($q -> execute(array(crypt(strtolower($_POST['new1']),"faureciabeaulieu"),$_POST['id']))){
          success('Modifié','Le mot de passe a été modifié.');
        }else{
          warning('Erreur','Erreur de session. Veuillez réessayez.');
        }
      }else{
        warning('Erreur','Les mots de passe ne sont pas identiques.');
      }
    }
    $recherche = "";

    if (isset($_GET["recherche"])){
        $recherche = $_GET["recherche"];
    }
    $supprime =false;
    if(isset($_GET['supprime'])){
      $supprime=true;
    }
    ?>
    <form class="form-inline">
      <div class="form-group">
        <label for="recherche">Recherche :</label>
        <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Nom, Prénom" value="<?php echo $recherche;?>">
        <label style="margin-left: 30px;"><input type="checkbox" name="supprime" <?php if($supprime){echo "checked";} ?>>  Supprimé</label>
      </div>
      <button type="submit" class="btn btn-default">Rechercher</button>
      <a href="editer_profil.php" class="btn btn-default pull-right">Ajouter un profil</a>
    </form>
    <table class="table">
      <thead class="thead">
        <tr>
          <th>Nom</th>
          <th>Prenom</th>
          <th>UAP</th>
          <th>MO</th>
          <th>Actif</th>
        </tr>
      </thead>
      <tbody>  <?php
      if($supprime){
        $query = $bdd -> prepare('SELECT * FROM profil WHERE (nom LIKE :nom or prenom like :prenom)');
        $query ->bindValue(':prenom','%'.$recherche.'%');
        $query ->bindValue(':nom','%'.$recherche.'%');
        $query -> execute();
      }else{
        $query = $bdd -> prepare('SELECT * FROM profil WHERE (nom LIKE :nom or prenom like :prenom) AND supprime = 0');
        $query ->bindValue(':prenom','%'.$recherche.'%');
        $query ->bindValue(':nom','%'.$recherche.'%');
        $query -> execute();
      }
        while($Data = $query -> fetch()){ ?>
          <tr class="clickable" onclick="window.location='editer_profil.php?id=<?php echo $Data['id']; ?>'">
            <td><?php echo $Data['nom']; ?></td>
            <td><?php echo $Data['prenom']; ?></td>
            <td><?php echo $Data['uap']; ?></td>
            <td><?php echo $Data['mo']; ?></td>
            <td style="width:30px;"><?php echo img($Data['supprime']) ?></td>
        <?php }
        ?>
      </tbody>
    </table>

<?php
  }
}
 drawFooter();
 ?>
