<?php

global $domain_name;
$url = "http://localhost/Beaulieu";
//$bdd = new PDO('mysql:host=localhost;dbname=faurecia_beaulieu;charset=utf8', 'root', '');

?>

    <!DOCTYPE html>


    <html lang="fr">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=0.635, user-scalable=yes"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="robots" content="noindex, nofollow, noarchive"/>


        <link rel="stylesheet" href="/bootstrap/css/bootstrap.css"/> 

    <link rel="stylesheet" href="/css/base.css"/>


        <title>Faurecia Beaulieu</title>


    </head>
<body>
<?php

function drawHeader()
{
global $url;
?>

    <div id="header_banner">
        <a href="<?php echo $url; ?>/index.php" class="lien_accueil"><img src="<?php echo $url; ?>/images/logo.png"></a>
        <div id="menu">

            <a href="/presentation_usine/chiffres_cle.php"><div class="bouton_menu" >Présentation de l'usine</div></a>
            <a href="/dojo_qualite/ok_1er_piece.php"><div class="bouton_menu" >Dojo qualité </div></a>
            <a><div class="bouton_menu" >Dojo HSE </div></a>
            <a href="/logistique/index.php"><div class="bouton_menu" >Logistique</div></a>
            <a href="/codir/kamishibai/index.php"><div class="bouton_menu" >Codir</div></a>
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
        <div style="height: 30px"></div>
    </div>
    <div id='footer'>




        <img src="/images/background-bottom.jpg">


        <div class="copyright">&copy; Faurecia Beaulieu 2017 - <?php echo date("Y"); ?> <br>
            Tous droits réservés - <a href="a_propos.php">À propos</a></div>

    </div>

</body>
</html>

<script>
    window.onresize = function () {
        document.getElementById("design").style.top = document.getElementById("header_banner").clientHeight + "px";
        document.getElementById("footer").style.top = document.getElementById("header_banner").clientHeight + "px";
    };
    document.getElementById("design").style.top = document.getElementById("header_banner").clientHeight + "px";
    document.getElementById("footer").style.top = document.getElementById("header_banner").clientHeight + "px";
</script>

<?php
}
