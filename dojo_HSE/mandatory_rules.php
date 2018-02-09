<?php
include_once "../needed.php";

include_once "needed.php";

drawHeader('dojo_hse');
drawMenu('mandatory_rules');
?>
        <div class="">
          <object data="dojo_hse.pdf" type="application/pdf" width="100%" height="1500px">
            <iframe src="dojo_hse.pdf" style="border: none;" width="100%" height="1500px">
              <p>Ce navigateur ne supporte pas les PDFs. <a href="dojo_hse.pdf">Télécharger le pdf</a></p></iframe>
            </object>
          </div>

<?php
drawFooter();
