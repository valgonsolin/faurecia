<?php
ob_start();
include_once "../../needed.php";
ob_clean();
function exportCSV(PDO $bdd) {
  $datetime = date("Y-m-d");

      $date = date_parse($datetime);
      $jour = $date['day'];
      $mois = $date['month'];
      $annee = $date['year'];

    $stmt = $bdd->prepare('SELECT idees_ameliorations.id,nom,date_rea,date_val,type,situation_actuelle,situation_proposee,valide,nbidees   FROM idees_ameliorations JOIN profil ON profil.id=idees_ameliorations.emmetteur WHERE YEAR(date_rea)= ? AND MONTH(date_rea)= ? ');
    $stmt->execute(array($annee,$mois));
    $stmt->setFetchMode(PDO::FETCH_NUM);

    header("Content-type: application/octet-stream"); // force le téléchargement du fichier
    header("Content-disposition: attachment; filename=IA_".$datetime.".csv");

    $file = fopen('php://output', 'a+');
    if(gettype($file) != 'resource')
        throw new RuntimeException('Echec d\'accès en écriture au nouveau fichier CSV.');
    fputcsv($file, array('Id', 'Emmetteur','Date publication','Date realisation','Type','Situation Actuelle','Situation Proposee','Validation','Nombre idees contenues'), ';');

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
