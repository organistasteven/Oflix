
<?php
// pour que PHP récupère les informations de session
session_start();

var_dump($_SESSION);

session_destroy(); // ancienne manière pas suffisante
unset($_SESSION); // detruit la variable

var_dump($_SESSION); // ERREUR car $_SESSION n'existe pas
?>
<hr>
<a href="ajout.php?s=nouvelleValeurFromDestroy">ajout</a><br>
<a href="destroy.php">déconnecte</a>