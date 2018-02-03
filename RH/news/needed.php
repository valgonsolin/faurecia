<?php
function drawMenu($selected){
    global $url?>
    <div id="nav_">
        <div id="boutons_nav">
            <a href="<?php echo $url; ?>/RH/news"><div class="bouton_menu<?php if($selected=='news'){echo ' bouton_nav_selected';} ?>">News</div></a>
            <a href="<?php echo $url; ?>/RH/news/liens.php"><div class="bouton_menu <?php if($selected=='liens'){echo ' bouton_nav_selected';} ?>">Liens</div></a>
       </div>
     </div>
<?php
}

function latestNews($nb,$recherche){
  global $bdd;
  global $url;
  $query = $bdd -> prepare('SELECT * FROM news WHERE nom LIKE :nom ORDER BY date DESC LIMIT '.$nb);
  $query ->bindValue(':nom','%'.$recherche.'%');
  $query -> execute();
  ?>
  <style>
  .news-conteneur{
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom:20px;
  }
  .news{
    color:#444;
    background-color: #e3e3e3;
    border-color: #ccc;
    border-radius:6px;
    border-width: 1px;
    border-style: solid;
    margin:2px;
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
  </style>
  <?php
  echo "<div class='row news-conteneur'>";
  while($Data = $query -> fetch()){
    $date=strtotime($Data['date']);
    $file = $bdd -> prepare('SELECT * FROM files WHERE id = ?');
    $file -> execute(array($Data['id_pdf']));
    $pdf = $file -> fetch();
    ?>
    <a href="<?php echo $url.'/RH/news/news.php?id='.$Data['id']; ?>">
      <div class="news">
        <h4 style="text-align:center;"><?php echo $Data['nom']; ?><small style="float:right; margin-right:5px;"><?php echo date('j/m/y', $date); ?></small></h4>
        <div class="news-content">
          <object data="<?php echo $pdf['chemin']; ?>" type="application/pdf" width="400px;" height="500px">
            <iframe src="<?php echo $pdf['chemin']; ?>" style="border: none;" width="400px" height="500px">
              <p>Ce navigateur ne supporte pas les PDFs. <a href="<?php echo $pdf['chemin']; ?>">Télécharger le pdf</a></p></iframe>
            </object>
          </div>
        </div>
      </a>
      <?php
    }
    ?>
  </div>
  <?php
}
