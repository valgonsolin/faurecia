<?php
include_once "../../needed.php";

include_once "needed.php";

drawHeader('RH');
drawMenu('news');

if(! isset($_GET['id'])){

}else{
  $query = $bdd -> prepare('SELECT * FROM news JOIN files ON files.id=news.id_pdf WHERE news.id = ?');
  $query -> execute(array($_GET['id']));
  $Data = $query -> fetch();
  $date = strtotime($Data['date']);
  ?>
  <h1 style="text-align:center;"><?php echo $Data['nom']; ?><small style="float:right;"><?php echo date('j/m/y', $date); ?></small></h1>
  <object data="<?php echo $Data['chemin']; ?>" type="application/pdf" width="100%" height="1000px">
    <iframe src="<?php echo $Data['chemin']; ?>" style="border: none;" width="100%" height="1000px">
      <p>Ce navigateur ne supporte pas les PDFs. <a href="<?php echo $data['chemin']; ?>">Télécharger le pdf</a></p>
    </iframe>
  </object>
  <?php
  if(isset($_SESSION['login']) && $_SESSION['news']){
    ?>
    <form method="post" action="index.php">
      <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
      <input type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette news ? ')" class="btn btn-default" value="Supprimer">
    </form>
    <?php
  }
}
?>

<?php
drawFooter();
