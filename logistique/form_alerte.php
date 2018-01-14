<?php
ob_start();
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("alerte");

if (isset($_POST['submit'])){


    $piece = -1;
    $e_kanban = -1;
    $Query = $bdd->prepare('SELECT * FROM logistique_pieces WHERE reference = ?');
    $Query->execute(array($_POST['ref_code_barres']));
    if ($Data = $Query->fetch()){
        $piece = $Data['id'];
    }
    $Query = $bdd->prepare('SELECT * FROM logistique_e_kanban WHERE code_barres = ?');
    $Query->execute(array($_POST['ref_code_barres']));
    if ($Data = $Query->fetch()){
        $piece = $Data['piece'];
        $e_kanban = $Data['id'];
    }

    $Query = $bdd->prepare('SELECT * FROM logistique_alerte WHERE piece = ? and state <> -1');
    $Query->execute(array($piece));

    if (! $Data = $Query->fetch()) {
        if ($piece != -1) {
            if (isset($_GET['id'])) {
                $Query = $bdd->prepare('UPDATE logistique_alerte SET piece=?, e_kanban=?,  train=?, uc_restant_en_ligne=? WHERE id = ?');
                $Query->execute(array($piece, $e_kanban, $_POST['train'], $_POST['uc_en_ligne'], $_GET["id"]));
                ob_end_clean();
                header('Location: ' . $url . "/logistique/alerte.php?id=" . $_GET['id']);

            } else {
                $Query = $bdd->prepare('INSERT INTO logistique_alerte SET piece=?, e_kanban=?,  train=?, uc_restant_en_ligne=?');
                $Query->execute(array($piece, $e_kanban, $_POST['train'], $_POST['uc_en_ligne']));
                ob_end_clean();
                header('Location: ' . $url . "/logistique/index.php");
            }

        } else {
          warning("Erreur","La référence ou le code barre choisi n'éxiste pas.");
        }
    }else{
        warning("Erreur","Une alerte est déjà en cours sur cette pièce.");
    }
}




$ref_e_kanban = "";
$train = "";
$uc_en_ligne ="";

if (isset($_GET['id'])){
    ?>
        <h2>Modifier l'alerte</h2>

    <?php
    $Query = $bdd->prepare('SELECT * FROM logistique_alerte WHERE id = ?');
    $Query->execute(array($_GET["id"]));
    $Data = $Query->fetch();
    $train = $Data['train'];
    $uc_en_ligne = $Data['uc_restant_en_ligne'];

    if ($Data['e_kanban'] == -1){
        $Query = $bdd->prepare('SELECT * FROM logistique_pieces WHERE id = ?');
        $Query->execute(array($Data["piece"]));
        $Data = $Query->fetch();
        $ref_e_kanban = $Data['reference'];
    }else{


        $Query = $bdd->prepare('SELECT * FROM logistique_e_kanban WHERE id = ?');
        $Query->execute(array($Data["e_kanban"]));
        $Data = $Query->fetch();
        $ref_e_kanban = $Data['code_barres'];
    }
}else{
    ?>
    <h2>Ajouter une alerte</h2>

    <?php
}
?>

<form id="form_alerte" class="form-horizontal" method="post">
    <div class="form-group">
        <label class="control-label col-sm-2" for="ref_code_barres">Code barres :</label>
        <div class="col-sm-10">
            <input type="text" name="ref_code_barres" class="form-control" id="ref_code_barres" placeholder="Entrer le code barres de la pièce ou sa référence si l'e kanban n'éxiste pas" value="<?php echo $ref_e_kanban; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="train">Train :</label>
        <div class="col-sm-10">
            <select name='train' id="train" name="uap" class="form-control">
                <option value="Ceramique" <?php if($train == 'Ceramique'){echo 'selected="selected"';}?> >Céramique</option>
                <option value="Jaune" <?php if($train == 'Jaune'){echo 'selected="selected"';}?> >Jaune</option>
                <option value="Orange" <?php if($train == 'Orange'){echo 'selected="selected"';}?> >Orange</option>
                <option value="Violet" <?php if($train == 'Violet'){echo 'selected="selected"';}?> >Violet</option>
                <option value="L02/L03" <?php if($train == 'L02/L03'){echo 'selected="selected"';}?> >L02/L03</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="uc_en_ligne">UC en ligne :</label>
        <div class="col-sm-10">
            <input type="text" name="uc_en_ligne" class="form-control" id="uc_en_ligne" placeholder="Entrer le nombre d'UC en ligne"  value="<?php echo $uc_en_ligne; ?>">
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="submit" class="btn btn-default">Valider</button>
        </div>
    </div>




    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

    <script>


        var input = document.getElementById('ref_code_barres');

        input.focus();
        input.select();

        $('#form_alerte').submit(function(event){

            console.debug("bip");
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/&/g,'1')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/é/g,'2')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/"/g,'3')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/'/g,'4')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace("(",'5')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/-/g,'6')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/è/g,'7')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/_/g,'8')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/ç/g,'9')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/à/g,'0')
            document.getElementById('ref_code_barres').value = document.getElementById('ref_code_barres').value.replace(/s/g,'S')


            if ($('#uc_en_ligne').val().length == 0)
            {
                event.preventDefault();
            }
        });

        window.scrollTo(0,document.body.scrollHeight);

    </script>

<?php
drawFooter();
ob_end_flush();
