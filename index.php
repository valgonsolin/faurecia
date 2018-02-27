<?php

include_once "needed.php";
include_once "RH/news/needed.php";

drawheader();

?>

  <style>
  .news{
    color:#444;
    background-color: #e3e3e3;
    border-color: #ccc;
    border-radius:6px;
    border-width: 1px;
    border-style: solid;
    margin:2px;
  }
  .page{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;  
  }
  .news-content{
    margin: 0 10px 10px 10px;
    width: 400px;
    height: 500px;
    padding:0;
    overflow:hidden;
    border-radius:6px;
    background-color: #FFF;
    border-color: #ccc;
    border-width: 1px;
    border-style: solid;
  }
  .carousel-control{
    background: none !important;
    color:black !important;
  }
  .carousel-control.left{
    margin-left:-100px;
  }
  .carousel-control.right{
    margin-right:-100px;
  }
  </style>


<img src="/images/background-3.png" style="width:125%;margin-left:-12.5%;margin-right:-12.5%;" >

<h1 style="text-align:center;"><a href="RH/news" style="color:grey;">News</a></h1>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
<?php
  $query = $bdd -> prepare('SELECT news.nom as nom, files.chemin as chemin,news.id as news,news.date as date FROM news JOIN files ON files.id=news.id_pdf ORDER BY date DESC LIMIT 10');
  $query -> execute();
  $test=1;
  while($Data = $query -> fetch()){
    ?>
    <div class="item <?php if($test){echo "active"; $test=0;} ?>">
    <div class="page">
      <a href="<?php echo $url.'/RH/news/news.php?id='.$Data['news']; ?>">
      <div class="news">
        <h4 style="text-align:center;"><?php echo $Data['nom']; ?><small style="float:right; margin-right:5px;"><?php echo date('j/m/y', strtotime($Data['date'])); ?></small></h4>
        <div class="news-content">
          <object data="<?php echo $Data['chemin']; ?>" type="application/pdf" width="400px;" height="500px">
            <iframe src="<?php echo $Data['chemin']; ?>" style="border: none;" width="400px" height="500px">
              <p>Ce navigateur ne supporte pas les PDFs. <a href="<?php echo $Data['chemin']; ?>">Télécharger le pdf</a></p></iframe>
            </object>
          </div>
        </div>
      </a>
      <?php if($Data = $query -> fetch()){
      ?>
      <a href="<?php echo $url.'/RH/news/news.php?id='.$Data['news']; ?>">
      <div class="news">
        <h4 style="text-align:center;"><?php echo $Data['nom']; ?><small style="float:right; margin-right:5px;"><?php echo date('j/m/y', strtotime($Data['date'])); ?></small></h4>
        <div class="news-content">
          <object data="<?php echo $Data['chemin']; ?>" type="application/pdf" width="400px;" height="500px">
            <iframe src="<?php echo $Data['chemin']; ?>" style="border: none;" width="400px" height="500px">
              <p>Ce navigateur ne supporte pas les PDFs. <a href="<?php echo $Data['chemin']; ?>">Télécharger le pdf</a></p></iframe>
            </object>
          </div>
        </div>
      </a><?php } ?>
      </div>
    </div>

      <?php
    }
    ?>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<div id="menu_principal">
	<a href="/presentation_usine/chiffres_cle.php" class="bouton_principal"><img src="/presentation_usine/ressources/beaulieupicture.jpg"><figcaption>L'usine</figcaption></a>
	<a href="/dojo_qualite/ok_1er_piece.php" class="bouton_principal"><img src="/dojo_qualite/ressources/Basic01.PNG"><figcaption>Dojo Qualité</figcaption></a>
	<a href="/dojo_HSE/mandatory_rules.php" class="bouton_principal"><img src="/dojo_HSE/ressources/hse.jpg"><figcaption>Dojo HSE</figcaption></a>
	<a href="/logistique/index.php" class="bouton_principal"><img src="/logistique/ressources/logistique.jpg"><figcaption>Logistique</figcaption></a>
	<a href="/codir/kamishibai/index.php" class="bouton_principal"><img src="/codir/kamishibai/ressources/codir.jpg"><figcaption>Codir</figcaption></a>
</div>

</div>
<?php
drawFooter();

?>
