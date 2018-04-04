<?php
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("pieces");

echo "<h1 id='message' style='text-align:center; display:none;'>Chargement...</h1>";
echo "<script>document.getElementById('message').style.display = 'block';</script>";
ob_flush();
flush();
ob_flush();
$recherche = "";
if ( isset($_GET['recherche'])) {
    $recherche = $_GET['recherche'];
}
?>
<h2>Pièces</h2>

<form class="form-inline">
    <div class="form-group">
        <label for="recherche">Recherche :</label>
        <input style="width: 500px;" type="text" class="form-control" name = "recherche" id="recherche" placeholder="Sebango, référence, description, ligne" value="<?php echo $recherche;?>">
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
    <a class="btn btn-default pull-right" href="editer_piece.php">Ajouter une pièce</a>
</form>
<hr>
<table class="table">
<thead class="thead">
<tr>
    <th style="width: 50px">Reference</th>
    <th style="width: 50px">Sebango</th>
    <th>Description</th>
    <th>Fournisseur</th>
</tr>
</thead>
<tbody>

<?php
$debut=0;
if(isset($_GET['nb'])){
  $debut=$_GET['nb'];
}
$Query = $bdd->prepare('SELECT logistique_pieces.*, ligne FROM logistique_pieces LEFT JOIN logistique_e_kanban ON logistique_e_kanban.piece=logistique_pieces.id WHERE reference LIKE :reference or description LIKE :description or ligne LIKE :ligne GROUP BY id LIMIT 50 OFFSET :nb');
$Query ->bindValue(':reference','%'.$recherche.'%');
$Query ->bindValue(':description','%'.$recherche.'%');
$Query ->bindValue(':ligne','%'.$recherche.'%');
$Query ->bindValue(':nb',(int) $debut, PDO::PARAM_INT);
$Query->execute();
echo "<script>document.getElementById('message').style.display = 'none';</script>";
ob_flush();
flush();
ob_flush();
while ($Data = $Query->fetch()) {
    ?>

    <tr class="clickable" onclick="window.location='/logistique/fiche_piece.php?id=<?php echo $Data['id']; ?>'">
        <td><?php echo $Data['reference']; ?></td>
        <td><?php echo $Data['sebango']; ?></td>
        <td><?php echo $Data['description']; ?></td>
        <td><?php echo $Data['fournisseur']; ?></td>
    </tr>


    <?php
}
?>
</tbody>
</table>


<?php
if($debut > 49){
  ?>
  <a href="pieces.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut-50;?>" class="btn btn-default">Elements précédents</a>
<?php
}
$test = $bdd->prepare('SELECT logistique_pieces.*, ligne FROM logistique_pieces LEFT JOIN logistique_e_kanban ON logistique_e_kanban.piece=logistique_pieces.id WHERE reference LIKE :reference or description LIKE :description or ligne LIKE :ligne GROUP BY id LIMIT 1 OFFSET :nb');
$test ->bindValue(':reference','%'.$recherche.'%');
$test ->bindValue(':description','%'.$recherche.'%');
$test ->bindValue(':ligne','%'.$recherche.'%');
$test ->bindValue(':nb',(int) $debut+50, PDO::PARAM_INT);
$test->execute();
if($test -> fetch()){ ?>
  <a href="pieces.php?recherche=<?php echo $recherche;?>&amp;nb=<?php echo $debut+50;?>" class="btn btn-default">Elements suivants</a>
<?php
}
drawFooter();
