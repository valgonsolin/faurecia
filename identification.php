<?php
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
        header('Location: http://tavg2website.000webhostapp.com');
        exit();
      }
    }
      else
    {
      $errorMessage = 'Veuillez inscrire vos identifiants';
    }
  }
  include_once "needed.php";
	drawHeader('connexion');
?>
    <form action="/identification.php" method="post" style="padding-top:20px">
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
