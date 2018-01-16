<?php
include_once "../../needed.php";
$query = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
$query -> execute(array($_GET['id']));
$Data = $query -> fetch();

$ext = substr(strrchr($Data['chemin'],'.'),1);
$nouveau_nom_de_fichier = $_GET['name'].".".$ext;

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"".$nouveau_nom_de_fichier."\"");
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . $Data['taille']);
ob_clean();
flush();
readfile($Data['chemin']);
exit;
