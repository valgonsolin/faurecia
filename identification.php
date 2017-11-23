<?php
  ob_start();
  include_once "needed.php";
  define('LOGIN','admin');
  define('PASSWORD','root');
  $errorMessage = '';

  if(!empty($_POST))
  {

    if(!empty($_POST['login']) && !empty($_POST['password']))
    {
      // Sont-ils les mêmes que les constantes ?
      if($_POST['login'] !== LOGIN)
      {
        $errorMessage = 'Mauvais login';
      }
        elseif($_POST['password'] !== PASSWORD)
      {
        $errorMessage = 'Mauvais password';
      }
        else
      {
		session_start();
        $_SESSION['login'] = true;
        ob_end_clean();
        if(isset($_GET['redirection'])){
          header('Location: '.$url."/".$_GET['redirection']);
        }else{
          header('Location: '.$url);
        }
        exit();
      }
    }
      else
    {
      $errorMessage = 'Veuillez inscrire vos identifiants';
    }
  }

	drawHeader('connexion');
  if(isset($_GET['redirection'])){
    $direction="/identification.php?redirection=".$_GET['redirection'];
  }else{
    $direction="/identification.php";
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
