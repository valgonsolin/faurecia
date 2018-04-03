<?php
ob_start();
include_once "../../needed.php";
ob_clean();

function exportCSV(PDO $bdd) {
    $query = ('SELECT profil.nom as Name,profil.prenom as FirstName,formations_dispo.trainingtitle as Training_Title,
              demande_formations.origine as Origine_du_besoin,validation_formations.objectif as Objectif,
              validation_formations.resultat as Resultats,validation_formations.impact as Training_Impact,
              validation_formations.priorite as Priorite,validation_formations.interne as Interne,
              formations_dispo.date_deb as Date_Debut,formations_dispo.date_fin as Date_Fin,
              janvier,fevrier,mars,avril,mai,juin,juillet,aout,septembre,octobre,novembre,decembre
              FROM demande_formations JOIN formations_dispo ON demande_formations.formation=formations_dispo.id
              JOIN profil ON profil.id=demande_formations.demandeur
              JOIN profil as T ON profil.manager=T.id
              JOIN validation_formations ON validation_formations.demande=demande_formations.id
              WHERE demande_formations.valide=1');
    $stmt = $bdd->query($query);
    $stmt->setFetchMode(PDO::FETCH_NUM);
    $datetime = date("Y-m-d");

    header("Content-type: application/octet-stream"); // force le téléchargement du fichier
    header("Content-disposition: attachment; filename=FORMATIONS_Validées".$datetime.".csv");

    $file = fopen('php://output', 'a+');
    if(gettype($file) != 'resource')
        throw new RuntimeException('Echec d\'accès en écriture au nouveau fichier CSV.');
    fputcsv($file, array('Name', 'FirstName','Training Title','Origine du Besoin','Objectifs','Resultats','Training Impact','Priorite','Interne',
                          'Date de debut','Date fin',
                        'janvier','fevrier','mars','avril','mai','juin','juillet','aout','septembre','octobre','novembre','decembre'), ';');

    foreach($stmt as $row) {
        fputcsv($file, $row, ';');
    }
    fclose($file);
}

try {
    exportCSV($bdd);
} catch (RuntimeException $ex) { // Inclut les PDOException
    echo $ex->getMessage();
}
