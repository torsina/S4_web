# S4 Framework project Aelis Sagot

## INSTALLATION

Les datafixtures doivent être utilisées pour rendre le projet fonctionnel!

Exemple des commentaires uniquement dans le premier article

compte admin: root:root

compte utilisateur: foo:bar

## Descriptif du projet

Mon but pour ce projet est de créer un blog qui me permettra de docummenter ce que j'apprends et ce quie je créer au sein de mon groupe de Gestion de projet ou nous faisons un prototype de jeux video.

## Outils utilisés

Pour ce projet, j'utilise Symfony avec les différents bundle dans composer.json pour gérer entre autre:
+ la traduction des dates (langue du projet reglable dans `./config/packages/translation.yaml`) avec `twig/intl-extra`
+ Une API REST / GraphQL gràce à `api-platform/api-pack`
+ Un backoffice avec `easycorp/easyadmin-bundle`
+ la base de données avec `symfony/orm-pack`
+ l'upload des fichiers avec `vich/uploader-bundle`

J'ai également utiliser `webpack encore` afin de pouvoir build le js et le css de mon projet.

J'ai utiliser le template [sparrow](https://www.styleshout.com/free-templates/sparrow/) disponible et utilisable gratuitement tant que les crédits ne sont pas supprimer. Cela m'as permis de me concentrer sur la partie php et vuejs

## Résultats

Le résultat actuel (pour le rendu du 13/03/2020) est un site comportant :
+ un acceuil montrant les derniers articles parus
+ une page avec la liste paginée des articles
+ le début d'un système de tag pour les articles que l'on pourra utiliser pour une recherche
+ la création d'articles avec une image, une description et un contenu, ainsi qu'un espace commentaire pouvant contenir des commentaires répondant à d'autres commentaires
+ la création d'utilisateurs avec une image de profile (uploadé par l'utilisateur ou une image par défaut)
+ un backoffice gérant les images correctement
+ une API REST/GraphQL
+ un système de management des fichiers uploader, possèdant une catégorie diserner leur utilisation. Non limitée à l'upload d'images

## Remarque

l'entité AttachmentUsage n'existe en fait que dans le but de rendre la création d'Attachment dans le backoffice plus stricte et ne pas avoir de possible érreures de syntax(comparé à un simple field string)

La vulnérabilité de sécurité qu'indique npm pour ``bootstrap-tagsinput`` n'est pas viable ici (pas d'attaque XSS par les tag), les balises de script entrées pour les tag ne sont pas exécutées.

Je n'ai pas eu besoin de créer une entité pour gérer les dates en fonction de la localisation car un bundle officiel de twig s'en charge pour moi gràce à la fonction ``format_datetime``.

BEAUCOUP TROP de bundle symfony ne sont pas encore à jour pour symfony 5, et je regrète énormément de ne pas avoir pu utilisé Symfony 4 qui possède un écosystème beaucoup plus mature.

## Conclusion

Je n'ai aucune idée quant à savoir si symfony nétoie les entrées utilisateur et si oui comment et où.

Le Système de recherche par tag sera présent dans la version du rendu du 10/04/2020

l'autowiring est très utile mais on se lasse assez rapidement de la "magie" de symfony qui devient très vite un problème quand on veut étendre les fonctionalités du framework et qu'il faut d'un seul coup comprendre toutes les intéractions entre les composants sous le capot(ex: le DataTransformer pour mes Tags)

ApiPlatform est horrible à utiliser en tant qu'API REST et devrai être requalifiée en tant qu'API primairement GraphQL et médiocre en tant qu'API REST. 

Après plusieurs jours de recherche, j'ai vu qu'il était pratiquement impossible de changer l'iri d'entitées reliées par juste leur id, et que la seule option permetant cela est vivement non recommendée(ce qui fait du sens pour GraphQL, mais aucun pour du REST!). 

J'ai apris à me servir rudimentairement de webpack.







