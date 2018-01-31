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
      success('Ajouté','Le news a bien été ajouté.');
    }else{
      warning('Erreur','Il y a eu une erreur. Veuillez recommencer.');
    }
  }
}

if (isset($_GET["recherche"])){
    $recherche = $_GET["recherche"];
}
$all = 0;
if (isset($_GET["all"])){
    $all =1;
}
if ($all){
  $query = $bdd -> prepare('SELECT * FROM news WHERE nom LIKE :nom ORDER BY date DESC');
}else{
  $query = $bdd -> prepare('SELECT * FROM news WHERE nom LIKE :nom ORDER BY date DESC LIMIT 10');
}
$query ->bindValue(':nom','%'.$recherche.'%');
$query -> execute();
echo "<div class='row'>";
while($Data = $query -> fetch()){
  $file = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
  $file -> execute(array($Data['id_pdf']));
  $pdf = $file -> fetch();
  ?>
  <div class="col-md-6">
    <object data="<?php echo $pdf['chemin']; ?>" type="application/pdf" width="100%" height="100%">
      <iframe src="<?php echo $pdf['chemin']; ?>" style="border: none;" width="100%" height="100%">
        <p>Ce navigateur ne supporte pas les PDFs. <a href="<?php echo $pdf['chemin']; ?>">Télécharger le pdf</a></p></iframe>
  </object>
  </div>
  <?php
}
?>
</div>
<form class="form-inline">
  <div class="form-group">
    <label for="recherche">Recherche :</label>
    <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="News" value="<?php echo $recherche;?>">
    <label style="margin-left: 30px; margin-right:20px;"><input type="checkbox" name="all" <?php if($all){echo "checked";} ?>>Voir tout</label>
  </div>
  <button type="submit" class="btn btn-default">Rechercher</button>
    <div class="btn btn-default pull-right" data-toggle="modal" data-target="#ajout">Ajouter un news</div>
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
          <input type="text" class="form-control" name="nom">
          <div class="col-md-6">
            <label> PDF : </label>
            <input type="file" name="pdf">
          </div>
          <div class="row">
          <div class="col-md-6">
            <input type="submit" class="btn btn-default" value="Ajouter" name="add">
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
drawFooter();
