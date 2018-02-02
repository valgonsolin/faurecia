<?php
include_once "../../needed.php";

include_once "needed.php";

drawHeader('RH');
drawMenu('news');
$recherche = "";

if(! empty($_FILES)){
  $pdf=upload($bdd,'pdf',"../../ressources","news",50485760,array( 'pdf' , 'PDF'));
  if($pdf < 0){
    warning('Erreur','Le fichier n\'a pas pu être importé.');
  }else{
    $add = $bdd -> prepare('INSERT INTO news (id_pdf,nom,date) VALUES (?,?,NOW())');
    if($add -> execute(array($pdf,$_POST['nom']))){
      success('Ajoutée','La news a bien été ajoutée.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
    }
  }
}
if(isset($_POST['delete'])){
  $remove = $bdd -> prepare('DELETE FROM news WHERE id= ?');
  if($remove -> execute(array($_POST['id']))){
    success("Supprimée","La news a bien été supprimée.");
  }else{
    warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
  }
}

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}
$nb = "10";
if (isset($_GET["all"])){
    $nb = "10000";
}
latestNews($nb,$recherche);
?>
<form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="News" value="<?php echo $recherche;?>">
    <label style="margin-left: 30px; margin-right:20px;"><input type="checkbox" name="all" <?php if($nb == "10000"){echo "checked";} ?>>Voir tout</label>
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
  <?php if(isset($_SESSION['login']) && $_SESSION['news']){ ?>
    <div class="btn btn-default pull-right" data-toggle="modal" data-target="#ajout">Ajouter une news</div>
  <?php } ?>
</form>
<div id="ajout" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ajouter un news</h4>
      </div>
      <div class="modal-body">
        <form method="post" class="form-group" enctype="multipart/form-data">
          <label>Nom :</label>
          <div class="row">
            <div class="col-md-12">
              <input type="text" class="form-control" name="nom">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label> PDF : </label>
              <input type="file" name="pdf">
            </div>
            <div class="col-md-6">
              <input type="submit" class="btn btn-default" style="display:block; margin:auto;margin-top:15px;" value="Ajouter" name="add">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
drawFooter();
