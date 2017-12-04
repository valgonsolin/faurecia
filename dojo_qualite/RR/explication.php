<?php
ob_start();


include_once "../../needed.php";

include_once "../needed.php";

drawHeader('dojo_qualite');
drawMenu('RR');


if (! isset($_GET["id"])){
    ?>
    <h2>Quiz</h2>
    <h4>OUPS... Votre identité est inconnu.</h4>
    <a href="index.php"> Retourner au R&amp;R</a>
    <?php
}else {
    $Query = $bdd->prepare('SELECT * FROM qualite_RR_session WHERE personne = ? and fin is NULL');
    $Query->execute(array($_GET["id"]));
    if (! $Data = $Query->fetch()) {
        $Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
        $Query->execute(array($_GET["id"]));
        $Data = $Query->fetch();
        if ($Data['mo'] == "MOD"){
            ?>


            <h2>R&amp;R</h2>
            <p>Bonjour !<br>
                Le R&amp;R sera composé de 20 questions, il faut avoir 14 bonnes réponses ce qui est équivalent à 70% pour valider votre formation.<br>
                Pour répondre cliquez sur l'image pour choisir si elle est valide ou non.</p>
            <?php
        }else{
            ?>


            <h2>R&amp;R</h2>
            <p>Bonjour !<br>
                Le R&amp;R sera composé de 39 questions, il faut avoir 24 bonnes réponses ce qui est équivalent à 60% pour valider votre formation. <br>
                Pour répondre cliquez sur l'image pour choisir si elle est valide ou non.</p>
                

            <?php
        }
        ?>

        <div class="col-md text-center" style="margin: 50px;">
            <a href="depart.php?id_personne=<?php echo $_GET["id"]; ?>" class="btn btn-default">Démarrer le test</a>
        </div>
        <?php
    }else{
        ob_end_clean();
        header('Location: '.$url."/dojo_qualite/RR/depart.php?id_personne=".$_GET['id']);
    }
}
drawFooter();
ob_end_flush();
