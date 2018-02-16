<?php
include_once "../needed.php";

include_once "needed.php";

drawheader();
drawMenu("alerte");


$recherche = "";
$state = '(0,1)';
$order = 'couleur';
if(isset($_GET['state'])){
    $state = $_GET['state'];
}
if(isset($_GET['order'])){
    $order = $_GET['order'];
}
if(isset($_GET['recherche'])){
    $recherche = $_GET['recherche'];
}

?>
<style>
    .conteneur_alerte{
        margin-left:-12.5%;
        margin-right:-12.5%;
        margin-top:20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        font-family:Arial;
    }
    .alerte{
        color: #000 ;
        font-size: 15px;
        background-color: #e3e3e3;
        border-color: #ccc;
        border-radius:6px;
        border-width: 1px;
        border-style: solid;
        margin: 5px;
    }
    .alerte:hover{
      opacity:0.7;
    }
    .info_alerte{
        margin: 10px;
        width: 320px;
        padding: 10px;
        border-radius:6px;
        background-color: #FFF;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }

    .couleur{
        margin: 10px;
        width: 320px;
        height: 20px;
        border-radius:3px;
        border-color: #ccc;
        border-width: 1px;
        border-style: solid;
    }
    .date_et_titre{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
</style>

<h2>Alerte composant</h2>


<div class="spacer" style="height: 10px;"></div>
<form class="form-inline">

    <div class="form-group">
        <label for="recherche">Recherche :</label>
        <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="id de l'alerte, sebango" value="<?php echo $recherche;?>">
    </div>
    <div class="form-group">

        <label>Etat :</label>
        <select name='state' id="state" class="form-control">
            <option value="(-1,0,1)" <?php if($state == "(-1,0,1)"){echo 'selected="selected"';}?> >Toutes</option>
            <option value="(-1)" <?php if($state == "(-1)"){echo 'selected="selected"';}?> >Anciennes</option>
            <option value="(0,1)" <?php if($state == "(0,1)"){echo 'selected="selected"';}?> >En cours</option>
            <option value="(1)" <?php if($state == "(1)"){echo 'selected="selected"';}?> >Non traité</option>
        </select>
    </div>
    <div class="form-group">

        <label>Trier par :</label>
        <select name='order' id="order" class="form-control">
            <option value="logistique_alerte.date" <?php if($order == "logistique_alerte.date" ){echo 'selected="selected"';}?> >Date</option>
            <option value="couleur" <?php if($order == "couleur" ){echo 'selected="selected"';}?> >Couleur</option>
            <option value="logistique_e_kanban.ligne" <?php if($order ==  "logistique_e_kanban.ligne" ){echo 'selected="selected"';}?> >Ligne</option>
        </select>
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
    <a class="btn btn-default pull-right" href="form_alerte.php">Ajouter une nouvelle alerte</a>
</form>

<div class="conteneur_alerte">
    <?php

    $requete = 'SELECT DISTINCT
                (CASE
                 WHEN logistique_alerte.id in (SELECT alerte FROM logistique_reponse_rouge) then 3
                 WHEN logistique_alerte.id in (SELECT alerte FROM logistique_reponse_orange) then 2
                 WHEN logistique_alerte.id in (SELECT alerte FROM logistique_reponse_jaune) then 1
                 ELSE 0 END) as couleur,
                logistique_alerte.id as id_alerte,
                logistique_alerte.state as state,
                logistique_alerte.date ,
                logistique_pieces.*,
                logistique_e_kanban.ligne,
                logistique_reponse_jaune.couverture_ligne as couverture_ligne
                FROM logistique_alerte
                LEFT JOIN logistique_pieces on logistique_alerte.piece=logistique_pieces.id
                LEFT JOIN logistique_e_kanban on logistique_e_kanban.piece=logistique_alerte.piece
                LEFT JOIN logistique_reponse_jaune on logistique_alerte.id=logistique_reponse_jaune.alerte
                WHERE state in ';
    $requete .= $state;

    $requete .= "AND (logistique_alerte.id LIKE '%";
    $requete .= $recherche;
    $requete .= "%' OR sebango LIKE '%";
    $requete .= $recherche;
    $requete .= "%')";

    $requete .= "ORDER BY ".$order." DESC";
    $Query = $bdd->prepare($requete);
    $Query->execute(array());
    while ($Data = $Query->fetch()) {
    ?>
        <a href="alerte.php?id=<?php echo $Data['id_alerte']?>"><div class="alerte" >

            <div class="info_alerte">
                <div class="date_et_titre">
                    <h4 style="margin-top: 0px; font-size: 40px;"><?php
                        if (! (is_null($Data['sebango']) || $Data['sebango']=="") ){
                            echo $Data['sebango'];
                        }else{
                            echo $Data['reference'];
                        }
                        ?></h4>
                    <?php
                    if ($Data['couverture_ligne'] == 0 and $Data['couleur'] == 1) {
                        ?>
                        <img src="ressources/attention.png" style="height: 40px;">
                        <?php
                    }
                    ?>
                </div>

                <p><b>Désignation : </b><?php echo substr($Data['description'],0,18);?><br>
                    <b>Date et heure : </b><?php echo date('d/m/y H:i',strtotime($Data['date']));?><br>
                    <b>Référence concernée : </b><?php echo $Data['reference'];?><br>
                    <b>Ligne : </b><?php echo $Data['ligne'];?></p>

            </div>
                <?php

                $Query2 = $bdd->prepare('SELECT *  FROM logistique_reponse_rouge WHERE alerte = ?');
                $Query2->execute(array($Data['id_alerte']));
                if ($Data2 = $Query2->fetch()){
                    echo '<div class="couleur" style="background-color: #da090d;"></div>';
                }else{
                    $Query2 = $bdd->prepare('SELECT *  FROM logistique_reponse_orange WHERE alerte = ?');
                    $Query2->execute(array($Data['id_alerte']));
                    if ($Data2 = $Query2->fetch()){
                        echo '<div class="couleur" style="background-color: #FF9C00;"></div>';
                    }else{
                        $Query2 = $bdd->prepare('SELECT *  FROM logistique_reponse_jaune WHERE alerte = ?');
                        $Query2->execute(array($Data['id_alerte']));
                        if ($Data2 = $Query2->fetch()){
                            echo '<div class="couleur" style="background-color: #fff700;"></div>';
                        }else{
                            echo '<div class="couleur" style="background-color: #2b669a;"></div>';
                        }
                    }
                }
                ?>
        </div></a>
    <?php
    }
        ?>



</div>
<meta http-equiv="Refresh" content="60">

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script> -->

<script>


    function bouger(){


        console.debug("descente");
        $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, $(document).height()*7);
        setTimeout(function() {

            console.debug("monte");
            $('html, body').animate({scrollTop:0}, $(document).height()*7);
        },$(document).height());


        timeoutHandle  = window.setTimeout(bouger, $(document).height()*14);



    }

    var timeoutHandle  = window.setTimeout(bouger, 30000);

    $("html, body").mousemove(function(event){

        console.info("reset");
        window.clearInterval(timeoutHandle );
        timeoutHandle  = window.setTimeout(bouger, 30000);

        $("html, body").stop();
    });


</script>
<?php
drawFooter();
