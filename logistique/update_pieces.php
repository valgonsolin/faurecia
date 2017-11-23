<?php
include_once "../needed.php";
include_once "needed.php";

drawHeader('logistique');
drawMenu('update');

echo "<h2>Logistique</h2>";

if(empty($_POST)){ ?>
  <form>
  <input type="text" name="file">
  </form>
<?php }









drawFooter(); ?>
