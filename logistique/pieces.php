<?php
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("pieces");

$recherche = "";
if ( isset($_GET['recherche'])) {
    $recherche = $_GET['recherche'];
}
?>
<h2>Pièces</h2>




<a href="editer_piece.php">Ajouter une pièce</a>
<div style="height: 20px;" class="spacer"></div>

<form class="form-inline">
    <div class="form-group">
        <label for="recherche">Recherche :</label>
        <input style="width: 500px;" type="text" class="form-control" name = "recherche" id="recherche" placeholder="Sebango, référence, description, ligne" value="<?php echo $recherche;?>">
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
</form>

<div style="height: 20px;" class="spacer"></div>

<table class="table"
<thead class="thead">
<tr>
    <th style="width: 50px">Reference</th>
    <th style="width: 50px">Sebango</th>
    <th>Description</th></th>
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
while ($Data = $Query->fetch()) {
    ?>

    <tr>
        <td><a href="/logistique/fiche_piece.php?id=<?php echo $Data['id']; ?>"><?php echo $Data['reference']; ?></a></td>
        <td><?php echo $Data['sebango']; ?></td>
        <td><?php echo $Data['description']; ?></td>
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
