<?php
session_start();
global $domain_name;
$url = "http://localhost";
$bdd = new PDO('mysql:host=localhost;dbname=faurecia_beaulieu;charset=utf8', 'tavg', 'lacolloc');

?>

    <!doctype html>


    <html lang="fr">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=0.635, user-scalable=yes"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="robots" content="noindex, nofollow, noarchive"/>
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png"/>


        <link rel="stylesheet" href="/bootstrap/css/bootstrap.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="/bootstrap/js/bootstrap.min.js"></script>




        <link rel="stylesheet" href="/css/base.css"/>
		<link rel="stylesheet" href="/css/dropdown.css"/>


        <title>Faurecia Beaulieu</title>


    </head>

<?php

function drawHeader($selected='')
{
global $url;
?>
<body>
	<div id="page">
    <div id="header_banner">
        <a href="<?php echo $url; ?>/index.php" class="lien_accueil"><img src="<?php echo $url; ?>/images/logo.png"></a>
        <div id="menu">

            <div class="dropdown">
            <a href="/presentation_usine/chiffres_cle.php" class="bouton_menu  <?php if($selected=='presentation_usine'){echo ' bouton_menu_selected';} ?> dropbtn" >Présentation de l'usine</a>
                <div class="dropdown-content">
                    <a href="/presentation_usine/chiffres_cle.php" class="bouton_dropdown" >Chiffres clés</a>
                    <a href="/presentation_usine/historique.php" class="bouton_dropdown">L'historique</a>
                    <a href="/presentation_usine/organigramme.php" class="bouton_dropdown">Organigramme</a>
                    <a href="/presentation_usine/moyen_production.php" class="bouton_dropdown">Les moyens de production</a>
                    <a href="/presentation_usine/nouveaux_projets.php" class="bouton_dropdown">Les nouveaux projets</a>
                    <a href="/presentation_usine/being_faurecia.php" class="bouton_dropdown">Being Faurecia</a>
                </div>
            </div>
            <div class="dropdown">
            <a href="/dojo_qualite/ok_1er_piece.php" class="bouton_menu <?php if($selected=='dojo_qualite'){echo ' bouton_menu_selected';} ?>">Dojo qualité </a>
				<div class="dropdown-content">
                    <a href="<?php echo $url; ?>/dojo_qualite/ok_1er_piece.php" class="bouton_dropdown" >OK 1ère pièce</a>
                    <a href="<?php echo $url; ?>/dojo_qualite/poka_yoke.php" class="bouton_dropdown">Poka Yoke</a>
                    <a href="<?php echo $url; ?>/dojo_qualite/auto_controle.php" class="bouton_dropdown">Auto contrôle</a>
                    <a href="<?php echo $url; ?>/dojo_qualite/controle_final.php" class="bouton_dropdown">Contrôle final</a>
                    <a href="<?php echo $url; ?>/dojo_qualite/bacs_rouge.php" class="bouton_dropdown">Bacs rouge</a>
                    <a href="<?php echo $url; ?>/dojo_qualite/retouche_sous_controle.php" class="bouton_dropdown">Retouche sous contrôle</a>
					<a href="<?php echo $url; ?>/dojo_qualite/qrci.php" class="bouton_dropdown">QRCI</a>
					<a href="<?php echo $url; ?>/dojo_qualite/quiz/index.php" class="bouton_dropdown">Quiz</a>
                </div>
			</div>
            <a class="bouton_menu <?php if($selected=='dojo_hse'){echo ' bouton_menu_selected';} ?>">Dojo HSE </a>

			<div class="dropdown">
			<a href="/logistique/index.php" class="bouton_menu <?php if($selected=='logistique'){echo ' bouton_menu_selected';} ?>">Logistique</a>
				<div class="dropdown-content">
                    <a href="<?php echo $url; ?>/logistique/index.php" class="bouton_dropdown" >Alerte composant</a>
                    <a href="<?php echo $url; ?>/logistique/pieces.php" class="bouton_dropdown">Pièces</a>
                </div>
			</div>
			<a href="/codir/kamishibai/index.php" class="bouton_menu <?php if($selected=='codir'){echo ' bouton_menu_selected';} ?>">Codir</a>
            <a class="bouton_menu <?php if($selected=='connexion'){echo ' bouton_menu_selected';} ?>"" href=<?php if(!empty($_SESSION['login'])){echo "/deconnexion.php";}else{echo "/identification.php";} ?>><?php if(!empty($_SESSION['login'])){echo "Déconnexion";}else{echo "Connexion";} ?></a>
		</div>
    </div>


    <div id="design">
		<div id="content">

<?php
}



function drawFooter(){
global $url;
?>

	</div>
    </div>
	</div>

    <div id='footer'>




        <img src="/images/background-bottom.jpg">


        <div class="copyright">&copy; Faurecia Beaulieu 2017 - <?php echo date("Y"); ?> <br>
            Tous droits réservés - <a href="/a_propos.php">À propos</a></div>

    </div>
</body>
</html>

<?php
}

function upload($bdd,$index,$category,$maxsize=FALSE,$extensions=FALSE)
{
    global $url;
     if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return 0;
     if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return 0;
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
     if ($extensions !== FALSE AND !in_array($ext,$extensions)) return 0;
     $nom = md5(uniqid(rand(), true));
     $chemin= "/storage/ssd3/053/3602053/public_html/ressources/$nom.$ext";
     if(!move_uploaded_file($_FILES[$index]['tmp_name'],$chemin)) return 0;
     $query= $bdd -> prepare('INSERT INTO files(chemin, categorie, taille, date_ajout) VALUES (:chemin, :categorie, :taille, NOW())');
     $query -> execute(array(
       'chemin' => $chemin,
       'categorie' => $category,
       'taille' => $_FILES[$index]['size']
     ));
     return $bdd ->lastInsertId();
}
