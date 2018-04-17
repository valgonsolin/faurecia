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
    if(isset($_POST['delete'])){
      $delete= $bdd -> prepare('DELETE FROM services WHERE id = ?');
      if($delete -> execute(array($_POST['delete']))){
        success('Supprimé',"Le service a été supprimé.");
      }else{
        warning("Erreur","Veuillez réessayer.");
      }
    }
    if(isset($_POST['add_service'])){
      $delete= $bdd -> prepare('INSERT INTO services SET service = ?');
      if($delete -> execute(array($_POST['service_value']))){
        success('Ajouté',"Le service a été ajouté.");
      }else{
        warning("Erreur","Veuillez réessayer.");
      }
    }
    if (isset($_POST['modifier'])){
      if (isset($_POST['id'])){
            $Query = $bdd->prepare('UPDATE profil SET nom = ?, prenom = ?,identifiant = ?,mail = ?, mo = ?, uap = ?, tournee = ?, admin = ?, qualite = ?, rr = ?, hse = ?, kamishibai = ?, logistique = ?, idees = ?, launchboard = ?, manager = ?, news = ?, formation= ?, services= ? WHERE id = ?');
            if($Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['identifiant'],$_POST['mail'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_POST['admin'],$_POST['qualite'],$_POST['rr'],$_POST['hse'],$_POST['kamishibai'],$_POST['logistique'],$_POST['idees'],$_POST['launchboard'],$_POST['manager'],$_POST['news'],$_POST['formations'],$_POST['services'],$_POST['id'])) ) {
              success('Modifié',"Le profil a été modifié.");
            }else{
              warning("Erreur","Veuillez réessayer.");
            }
        }else{
          if($_POST['new1'] == $_POST['new2']){
              $Query = $bdd->prepare('INSERT INTO profil SET nom = ?, prenom = ?,identifiant = ?,mail = ? , mo = ?, uap = ?, tournee = ?, admin = ?, qualite = ?, rr = ?, hse = ?, kamishibai = ?, logistique = ?, idees = ?, launchboard = ?, manager = ?,news = ?,formation=?,services=?,password = ?');
              if($Query->execute(array($_POST['nom'],$_POST['prenom'],$_POST['identifiant'],$_POST['mail'],$_POST['mo'],$_POST['uap'],$_POST['tournee'],$_POST['admin'],$_POST['qualite'],$_POST['rr'],$_POST['hse'],$_POST['kamishibai'],$_POST['logistique'],$_POST['idees'],$_POST['launchboard'],$_POST['manager'],$_POST['news'],$_POST['formations'],$_POST['services'],crypt(strtolower($_POST['new1']),"faureciabeaulieu")))){
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
      <a data-toggle="modal" data-target="#modal"class="btn btn-default pull-right">Gestion Services</a>

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

<div id="modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Gestion Services</h4>
      </div>
      <div class="modal-body">
        <h4>Ajouter un service </h4>
        <form method="post" class="form-group row">
          <div class="col-md-12">
            <label for="services">Service : </label>
          </div>
          <div class="col-md-10">
            <input type="text" id="services" name="service_value" class="form-control">
          </div>
          <div class="col-md-2">
            <input type="submit" value="Ajouter" name="add_service" class="btn btn-default">
          </div>
        </form>
        <h4>Supprimer un service</h4>
        <div class="row">
          <form method="post" class="col-md-6">
            <ul>
              <?php
              $serv = $bdd -> query("SELECT * FROM services");
              while($service = $serv->fetch()){ ?>
                <li><?php echo $service['service']; ?>
                <input id="delete<?php echo $service['id']; ?>" type="submit" name="delete" value="<?php echo $service['id']; ?>" style="display:none;" onclick="confirm('Supprimer le service <?php echo $service['service']; ?> ?')">
                <label style="margin:0;" class="pull-right" for="delete<?php echo $service['id']; ?>"><img src="ressources/cancel.png" style="max-height:15px;"></label>
                </li>
              <?php } ?>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  }
}
 drawFooter();
 ?>
