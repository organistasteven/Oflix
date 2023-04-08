# SESSION en PHP

on a implemente un mechanisme pour ouvrir une session d'un user , le serveur garde la trace.

Une navigation privee est une 2e session differente.

ca permet de coller un post it

tableau "SESSION" pour un "user" précis dont on ne peut pas se défaire

c'est un id unique qui est donné par apache, on le garde tout le temps à moins qu'il soit détruit par une méthode spécifique. Cela permet de stocker des informations dans cette session pour s'en resservir au besoin

en gros si on met la fonction session_start() sur tous nos fichiers ou juste sur l'index si c'est notre seul point d'entré on peut mettre depuis n'importe quel fichier une information via la methode de notre choix dans le tableau $_SESSION qui sera accessible partout
