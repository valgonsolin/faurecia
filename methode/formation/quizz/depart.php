<?php
ob_start();


include_once "../../../needed.php";

include_once "../needed.php";


$Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
$Query->execute(array($_GET["id_personne"]));
$mo = $Query->fetch()['mo'];
if ($mo == "MOD"){
    $type = 0;
}else{
    $type = 1;
}

$Query = $bdd->prepare('SELECT * FROM formation_session WHERE personne = ? and fin is NULL');
$Query->execute(array($_GET["id_personne"]));
if ($Data = $Query->fetch()) {
    ob_end_clean();
    header('Location: '.$url."/methode/formation/quizz/question.php?id=".$Data['id']);
}else{
    $Query = $bdd->prepare('INSERT INTO formation_session SET personne = ?, formation_session.type = ?');
    $Query->execute(array($_GET["id_personne"],$type));
    ob_end_clean();
    header('Location: '.$url."/methode/formation/quizz/question.php?id=".$bdd->lastInsertId());
}
ob_end_flush();
