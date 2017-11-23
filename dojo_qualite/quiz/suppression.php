<?php
include_once "../needed.php";
include_once "../../needed.php";

drawHeader('dojo_qualite');
drawMenu('quiz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php?redirection=dojo_qualite/quiz/ajout.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au quizz</a>
<?php
}
else
{
  $recherche = "";

  if (isset($_GET["recherche"])){
      $recherche = $_GET["recherche"];
  }
  ?>
  <h2>Quiz</h2>
  <div class="boutons_nav" style="display: flex; justify-content: center;">
    <a href="ajout.php" class="bouton_menu" style="margin-right:20%">Ajout</a>
    <a href="suppression.php" class="bouton_menu bouton_nav_selected">Suppression</a>
  </div>

  <form class="form-inline">
    <div class="form-group">
      <label for="recherche">Recherche :</label>
      <input type="text" class="form-control" name = "recherche" id="recherche" placeholder="Question" value="<?php echo $recherche;?>">
    </div>
    <button type="submit" class="btn btn-default">Rechercher</button>
  </form>
  <table class="table">
  <thead class="thead">
  <tr>
      <th>Titre</th>
      <th>Type</th>
      <th>Question</th>
      <th></th>
  </tr>
  </thead>
  <tbody>

<?php
$query = $bdd -> prepare('SELECT * FROM qualite_quiz_question WHERE (question LIKE ? or titre LIKE ?)');
$query -> execute(array('%'.$recherche.'%','%'.$recherche.'%'));
  $maxi=20;
  if(isset($_GET['max'])){
    $maxi=$_GET['max'];
  }
  $i=0;

  while(($i < $maxi) && ($Data = $query->fetch())){
    $i=$i+1;
    if($i > $maxi-20){
  ?>
    <tr>
      <td><?php echo $Data['titre']; ?></td>
      <td><?php echo $Data['type']; ?></td>
      <td><?php echo $Data['question'];?></td>
      <td><a href="supprimer_question.php?id=<?php echo $Data['id']?>" class="btn btn-default">Voir</a></td>
    </tr>
  <?php
}} ?>
</tbody>

</table>
<?php
if($maxi > 20){?>
  <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;max=<?php echo $maxi-20;?>" class="btn btn-default">Elements précédents</a>
<?php
}
if($i == $maxi){?>
  <a href="suppression.php?recherche=<?php echo $recherche;?>&amp;max=<?php echo $maxi+20;?>" class="btn btn-default">Elements suivants</a>
<?php
}
}

drawFooter();
 ?>
