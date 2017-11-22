<?php



include_once "../../needed.php";

include_once "../needed.php";


$Query = $bdd->prepare('SELECT * FROM profil WHERE id = ?');
$Query->execute(array($_GET["id_personne"]));
$mo = $Query->fetch()['mo'];
if ($mo == "MOD"){
    $type = 0;
}else{
    $type = 1;
}

$Query = $bdd->prepare('SELECT * FROM qualite_quiz_session WHERE personne = ? and fin is NULL');
$Query->execute(array($_GET["id_personne"]));
if ($Data = $Query->fetch()) {

    header('Location: '.$url."/dojo_qualite/quiz/question.php?id=".$Data['id']);
}else{
    $Query = $bdd->prepare('INSERT INTO qualite_quiz_session SET personne = ?, qualite_quiz_session.type = ?');
    $Query->execute(array($_GET["id_personne"],$type));

    header('Location: '.$url."/dojo_qualite/quiz/question.php?id=".$bdd->lastInsertId());
}
