<?php
ob_start();


include_once "../../../needed.php";

include_once "../needed.php";

drawHeader('methode');
drawMenu('quizz');


if (! isset($_GET["id"])){
    ?>
    <h2>Quiz</h2>
    <h4>Erreur... Votre identité est inconnu.</h4>
    <a href="index.php"> Retourner au quiz</a>
    <?php
}else {
    $Query = $bdd->prepare('SELECT * FROM formation_session WHERE personne = ? and fin is NULL');
    $Query->execute(array($_GET["id"]));
    if (! $Data = $Query->fetch()) {
        $Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
        $Query->execute(array($_GET["id"]));
        $Data = $Query->fetch();
        if ($Data['mo'] == "MOD"){
            ?>


            <h2>Quiz</h2>
            <p>Bonjour !<br>
                Le quiz sera composé de 20 questions, il faut avoir 14 bonnes réponses ce qui est équivalent à 70% pour valider votre formation.<br>
                Pour répondre cochez la ou les bonne(s) réponse(s).</p>
            <?php
        }else{
            ?>


            <h2>Quiz</h2>
            <p>Bonjour !<br>
                Le quiz sera composé de 39 questions, il faut avoir 24 bonnes réponses ce qui est équivalent à 60% pour valider votre formation. <br>
                Pour répondre cochez la ou les bonne(s) réponse(s).</p>


            <?php
        }
        ?>

        <div class="col-md text-center" style="margin: 50px;">
            <a href="depart.php?id_personne=<?php echo $_GET["id"]; ?>" class="btn btn-default">Démarrer le test</a>
        </div>
        <?php
    }else{
        ob_end_clean();
        header('Location: '.$url."/methode/formation/quizz/depart.php?id_personne=".$_GET['id']);
    }
}
drawFooter();
ob_end_flush();
