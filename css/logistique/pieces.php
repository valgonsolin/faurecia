<?php
include_once "../needed.php";

include_once "needed.php";

drawheader('logistique');
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
$Query = $bdd->prepare('SELECT logistique_pieces.*, ligne FROM logistique_pieces LEFT JOIN logistique_e_kanban ON logistique_e_kanban.piece=logistique_pieces.id WHERE reference LIKE ? or description LIKE ? or ligne LIKE ? GROUP BY id');
$Query->execute(array('%'.$recherche.'%', '%'.$recherche.'%', '%'.$recherche.'%'));
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
drawFooter();