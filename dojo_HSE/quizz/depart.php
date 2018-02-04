<?php
ob_start();


include_once "../../needed.php";

include_once "../needed.php";


$Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
$Query->execute(array($_SESSION['id']));
$mo = $Query->fetch()['mo'];
if ($mo == "MOD"){
    $type = 0;
}else{
    $type = 1;
}

$Query = $bdd->prepare('SELECT * FROM qualite_hse_session WHERE personne = ? and fin is NULL');
$Query->execute(array($_SESSION['id']));
if ($Data = $Query->fetch()) {
    ob_end_clean();
    header('Location: '.$url."/dojo_HSE/quizz/question.php?id=".$Data['id']);
}else{
    $Query = $bdd->prepare('INSERT INTO qualite_hse_session SET personne = ?, qualite_hse_session.type = ?');
    $Query->execute(array($_SESSION['id'],$type));
    ob_end_clean();
    header('Location: '.$url."/dojo_HSE/quizz/question.php?id=".$bdd->lastInsertId());
}
ob_end_flush();
