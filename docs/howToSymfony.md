# How to ...

## create and start project

```bash
composer create-project symfony/skeleton challenge
mv ./challenge/* ./challenge/.* .
rmdir ./challenge
composer require twig
composer require maker
composer require annotations
composer require symfony/asset
composer require --dev symfony/profiler-pack
composer require --dev symfony/debug-bundle
composer require --dev symfony/var-dumper
composer require symfony/orm-pack
composer require symfony/form
composer require symfony/validator
composer require security-csrf

```

notre projet est presque prêt à démarrer

### partie BDD

Il faut aller dans Adminer pour créer la BDD avec son user

Ensuite il faut paramétrer Doctrine pour se connecter à la BDD
il faut faire un `.env.local` et y mettre le `DATABASE_URL`

on vérifie que Doctrine accède à la BDD : doctrine:schema:validate

```bash
bin:console d:s:v
```

## make first entity

il nous faut un MCD, même papier, de tête

à partir du MCD, on a x entités avec y relations

on commence par `make:entity` avec QUE les propriétés simple, pas de relations

qunad on fini avec les x entités, on passe au y relations

Pour une relation 1-N : on doit savoir à qui appartient la relation (owning side)
la règle est : j'ai la clé étrangère, c'est à moi qu'appartient la relation
Donc je fait un `make:entity` sur l'entité qui porte la relation

Pour une relation N-N, la question se pose si on a des propriétés liées à la relation.
Si aucune propriété, on créer directement la relation sur une entité existante.
Si il y a des propriétés, on décompose la relation en deux relations 1-N pour 3 entités, et on applique la règle des relations 1-N

maintenant que la structure est faites en objet, on demande à doctrine de générer le SQL (migration) `make:migration`
Puis on execute la migration : `doctrine:migrations:migrate`

Pour la forme on se permet de refaire un `doctrine:schema:validate`

## make first route

objectif afficher un truc
on va tenter d'afficher toutes les données d'une entité

On créer un controller `make:controller`

On créer une nouvelle méthode, dans laquelle on va travailler

On a besoin d'acceder à la BDD, à une entité en particulier, on utilise donc le repository correspondant.
=> injection de dépendance
On utilise le repository pour aller chercher toutes les données : `findAll()`

on passe le données à la vue, via le `render()`

Dnas twig on utilise `{% for (item in array)%}` pour faire des boucles
Dans twig on utilise `{{ entity.property }}` pour écrire des infos dans la page
Dans twig on utilise `{{ entity.subentity.property }}` pour écrire des infos d'une sous-entité dans la page
Dans twig on utilise `{{ path('route_name') }}` pour faire des liens entre nos routes
Dans twig on utilise `{{ asset('directory/file.ext') }}` pour faire des liens vers des ressources du dossier `public`

## make first form

J'ai 2 entités avec une relation 1-N
Je dois faire un formulaire pour créer/modifier un des deux entités

Je génère un formulaire sur cette entité : `make:form entity`

Pour afficher le formulaire, je génère un controller/ou un controller existant : `make:controller entity`

dans ce controller, je créer une route pour afficher le formulaire.

dans la méthode de cette route :

* je créer une instance de l'entité ciblée
* je créer un formulaire `createForm()` en donnant en paramètre:
  * le nom de mon formulaire (celui que l'on a générer)
  * l'instance de l'objet de l'on a créé
* je fournit le formulaire à ma vue avec `renderForm()`

dans twig, on affiche le formulaire

* `form_start`
* `form_widget`
* le button submit html
* `form_end`

je teste mon affichage
normalement j'ai une erreur car je n'ai pas modifié/personnalisé le formulaire

On modifie notre formulaire
notre erreur vient de la relation, on modifie cette partie en premier : `EntityType`

Notre affichage devrait fonctionner.

Il faut faire la gestion du formulaire coté controller

* le formulaire doit prendre les infos de request
* on vérifie que le formulaire est soumis ET valide
  * si il est soumis ET valide, on fait un persist+flush
  * on redirige vers notre route en GET
