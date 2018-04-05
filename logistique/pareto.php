<?php
ob_start();
include_once "../needed.php";

drawheader();

?>
<h2>Pareto alerte</h2>

//SELECT logistique_pieces.fournisseur as f,COUNT(*) as count FROM logistique_alerte JOIN logistique_pieces ON logistique_alerte.piece = logistique_pieces.id  GROUP BY fournisseur ORDER BY count DESC

<?php
drawFooter();
ob_end_flush();
