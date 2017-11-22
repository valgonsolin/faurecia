<?php
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("pieces");

if (isset($_GET['id'])){
    $Query = $bdd->prepare('SELECT * FROM logistique_pieces WHERE id = ?');
    $Query->execute(array($_GET['id']));
    $Data = $Query->fetch();?>
    <h2>Fiche de pièce : <?php echo $Data['sebango'];?></h2>

    <div class="row">
        <div class="col-xs-6">
            <h4>Informations générales</h4>
            <p>

                <b>Référence : </b><?php echo $Data['reference'] ?><br>
                <b>Sebango : </b><?php echo $Data['sebango'] ?><br>
                <b>Description : </b><?php echo $Data['description'] ?><br>
            </p>


            <a href="editer_piece.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-default center-block" style="width: 100px;">Modifier</a>

            <h4>E-kamban</h4>

            <table class="table"
            <thead class="thead">
            <tr>
                <th style="width: 150px">Code barres</th>
                <th >Ligne</th>
                <th style="width: 120px">Quantité / UC</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $Query = $bdd->prepare('SELECT * FROM logistique_e_kanban WHERE piece = ?');
            $Query->execute(array($_GET['id']));
            while ($Data = $Query->fetch()) {
                ?>

                <tr>
                    <td><?php echo $Data['code_barres']; ?></td>
                    <td><?php echo $Data['ligne']; ?></td>
                    <td><?php echo $Data['quantite']; ?></td>

                </tr>


                <?php
            }
            ?>
            </tbody>
            </table>

        </div>
        <div class="col-xs-6">
            <h4>Liste des alertes</h4>

            <table class="table"
            <thead class="thead">
            <tr>
                <th style="width: 25px;">Id</th>
                <th style="width: 150px;">Date</th>
                <th style="width: 25px">Etat</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $Query = $bdd->prepare('SELECT * FROM logistique_alerte WHERE piece = ? ORDER BY logistique_alerte.date DESC');
            $Query->execute(array($_GET['id']));
            while ($Data = $Query->fetch()) {
                ?>

                <tr>
                    <td><a href="/logistique/alerte.php?id=<?php echo $Data['id']; ?>"><?php echo $Data['id']; ?></a></td>
                    <td><?php echo date('d/m/y H:i',strtotime($Data['date'])); ?></td>
                    <td><?php echo ""; ?></td>

                </tr>


                <?php
            }
            ?>
            </tbody>
            </table>
        </div>
    </div>



    <?php
}
drawFooter();