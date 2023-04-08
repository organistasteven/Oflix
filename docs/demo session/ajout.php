
<?php
// pour que PHP récupère les informations de session
session_start();
// me permet de manipuler une valeur dans ma variable SESSION
// si j'apelle la page : ajout.php?s=nouvelleValeur
if (isset($_GET) && !empty($_GET["s"])) {
    $_SESSION["session"] = $_GET["s"];
}
var_dump($_SESSION);
?>

<hr>

<a href="index.php">index</a><br>
<a href="destroy.php">déconnecte</a>