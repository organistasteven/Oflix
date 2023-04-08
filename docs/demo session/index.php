
<?php
// pour que PHP récupère les informations de session
session_start();
var_dump($_SESSION);
?>
<hr>
<a href="ajout.php?s=nouvelleValeurFromIndex">ajout</a><br>
<a href="destroy.php">déconnecte</a>