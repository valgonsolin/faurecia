<?php
  ob_start();
  include_once "../needed.php";
  $errorMessage = '';

  if(!empty($_POST))
  {
    if(!empty($_POST['login']) && !empty($_POST['password']))
    {
      $query = $bdd -> prepare('SELECT * FROM profil WHERE identifiant = ?');
      $query -> execute(array($_POST['login']));
      if($Data = $query -> fetch()){
        if(crypt($_POST['password'],"faureciabeaulieu") == $Data['password']){
          session_start();
          $_SESSION['login'] = true;
          $_SESSION['id'] = $Data['id'];
          $_SESSION['nom'] = $Data['nom'];
          $_SESSION['prenom'] = $Data['prenom'];
          $_SESSION['mo'] = $Data['mo'];
          $_SESSION['uap'] = $Data['uap'];
          $_SESSION['admin']= $Data['admin'];
          $_SESSION['qualite'] = $Data['qualite'];
          $_SESSION['rr'] = $Data['rr'];
          $_SESSION['hse'] = $Data['hse'];
          $_SESSION['kamishibai'] = $Data['kamishibai'];
          ob_end_clean();

          if(isset($_GET['redirection'])){
            header('Location: '.$url."/".$_GET['redirection']);
          }else{
            header('Location: '.$url."/moncompte");
          }
          exit();
        }else{
          $errorMessage = 'Mauvais password';
                  }
      }else{
        $errorMessage = 'Mauvais login';
      }
    }
      else
    {
      $errorMessage = 'Veuillez inscrire vos identifiants';
    }
  }

	drawHeader('moncompte');
  if(isset($_GET['redirection'])){
    $direction="/moncompte/identification.php?redirection=".$_GET['redirection'];
  }else{
    $direction="/moncompte/identification.php";
  }
?>
    <form action=<?php echo $direction?> method="post" style="padding-top:20px">
      <fieldset>
        <legend>Identifiez-vous</legend>
        <?php
          if(!empty($errorMessage))
          {
            echo '<p>', htmlspecialchars($errorMessage) ,'</p>';
          }
        ?>
       <p>
          <label for="login">Login :</label>
          <input type="text" name="login" id="login" value="" />
        </p>
        <p>
          <label for="password">Password :</label>
          <input type="password" name="password" id="password" value="" />
          <input type="submit" name="submit" value="Connexion" />
        </p>
      </fieldset>
    </form>


<?php
	drawFooter();
?>
