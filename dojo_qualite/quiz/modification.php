<?php
include_once "../../needed.php";

include_once "../needed.php";

drawHeader('dojo_qualite');
drawMenu('quiz');

if(empty($_SESSION['login']))
{ ?>
  <h2>Quiz</h2>
  <h4>Vous devez être connecté en tant qu'administrateur pour accéder à cette partie.</h4>
  <a href="/identification.php"><button class="btn btn-default">Se connecter</button></a>
  <a href="index.php"> Retourner au quizz</a>
<?php
}
else
{ ?>
    <h2>Quiz</h2>
    <h4> Vous êtes bien un administrateur</h4>
<?php
}
?>





<?php
drawFooter();
 ?>
