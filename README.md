# S4 Framework project Aelis Sagot

repo github: https://github.com/torsina/S4_web

## INSTALLATION

Les datafixtures doivent être utilisées pour rendre le projet fonctionnel!

Exemple des commentaires uniquement dans le premier article

compte admin: root:root

compte utilisateur: foo:bar

## Descriptif du projet

Mon but pour ce projet est de créer un blog qui me permettra de docummenter ce que j'apprends et ce quie je créer au sein de mon groupe de Gestion de projet où nous faisons un prototype de jeux video.

Ce projet est une démo technique, elle ne contiendra aucune vrai données.

## Outils utilisés

Pour ce projet, j'utilise Symfony avec les différents bundle dans composer.json pour gérer entre autre:
+ la traduction des dates (langue du projet reglable dans `./config/packages/translation.yaml`) avec `twig/intl-extra`
+ Une API REST / GraphQL gràce à `api-platform/api-pack`
+ Un backoffice avec `easycorp/easyadmin-bundle`
+ la base de données avec `symfony/orm-pack`
+ l'upload des fichiers avec `vich/uploader-bundle`

J'ai également utiliser `webpack encore` afin de pouvoir build le js et le css de mon projet.

J'ai utiliser le template [sparrow](https://www.styleshout.com/free-templates/sparrow/) disponible et utilisable gratuitement tant que les crédits ne sont pas supprimer. Cela m'as permis de me concentrer sur la partie php et vuejs

### Ajouts VueJS pour le rendu du 10/04/2020:

libraries utilisé:
+ vue
+ vuetify
+ axios
+ LexikJWTAuthenticationBundle
+ @mdi/font pour les icones Material Design

## Résultats

Le résultat (pour le rendu du 13/03/2020) est un site comportant :
+ un acceuil montrant les derniers articles parus
+ une page avec la liste paginée des articles
+ le début d'un système de tag pour les articles que l'on pourra utiliser pour une recherche
+ la création d'articles avec une image, une description et un contenu, ainsi qu'un espace commentaire pouvant contenir des commentaires répondant à d'autres commentaires
+ la création d'utilisateurs avec une image de profile (uploadé par l'utilisateur ou une image par défaut)
+ un backoffice gérant les images correctement
+ une API REST/GraphQL
+ un système de management des fichiers uploader, possèdant une catégorie diserner leur utilisation. Non limitée à l'upload d'images

### Ajouts VueJS pour le rendu du 10/04/2020:
+ Utilisation du framework Vue, librairie Vuetify, axios, Vuex. Le tout "compiler" avec webpack encore
+ Ajout d'un système de vote positif et négatif pour les post gérer par des component vue avec des points d'API REST dédiés, avec interaction spéciale si l'utilisateur n'est pas connecté
+ Refonte du form de création de post en utilisant un composant vue et en traitant manuellement les données du form dans la route POST /post/create. Cette refonte m'as permis de supprimer ``bootstrap-input`` de la liste de mes dépendance. Ce nouveau form permet maintenant l'auto-complétion pour des tag à partir de tag déjà existant. Ce nouveau form comporte toujours un upload d'image
+ Mise en place des configuration necessaires à la création de token JWT
+ Création d'un composant exemple permetant de demander un jeton JWT et de le stoquer dans le $store de l'application vue. Ce composant ne sera pas utilisé car mes besoins d'authentifications ont toujours été statefull, et non stateless
+ Utilisation d'API REST au lieu de données statiques JSON pour tout les composants vue
+ Utilisation du système de refs de vuejs pour communiquer entre un composant parent et enfant

## Remarque

+ l'entité AttachmentUsage n'existe en fait que dans le but de rendre la création d'Attachment dans le backoffice plus stricte et ne pas avoir de possible érreures de syntax(comparé à un simple field string)
+ Je n'ai pas eu besoin de créer une entité pour gérer les dates en fonction de la localisation car un bundle officiel de twig s'en charge pour moi gràce à la fonction ``format_datetime``.
+ BEAUCOUP TROP de bundle symfony ne sont pas encore à jour pour symfony 5, et je regrète énormément de ne pas avoir pu utilisé Symfony 4 qui possède un écosystème beaucoup plus mature.

### Ajouts VueJS pour le rendu du 10/04/2020:

+ J'ai remarquer que les endpoints de l'API REST du site étaient extrèmement lent (1s+/request) quand j'ai introduit l'auto-complétion pour les tag. J'ai vu dans le profiler que dans le cadre d'une requète sur ressource paginée, ApiPlatform va query chaque id de la page individuellement, ce qui est presque la pire chose à faire.
+ J'ai fait le choix de créer une entitée Upvote pour signaler un vote car je voulais avoir la possibilité de créer un downvote, ce qui dépasse la simple relation ManyToOne entre User et Post. Un ajout possible à cette entitée serai un field de date du vote à des fins analytiques, mais ceci dépasse le cadre de ce projet.
+ Les temps de compilations de webpack encore maintenant obligatoires dù à la configuration de mon projet sont extrèmement long (10.55s average à cause des 14 000 package npm total) et rendent le développement beaucoup moins fluide. Une solution pour un build plus modulaire avec un seul serveur serai à rechercher.

DETAIL IMPORTANT: Voulant garder un seul serveur opérationel au lieu d'un serveur symfony + un serveur de dev vue, à chaque modification de mes fichiers js/vue je dois executer la commande ``encore dev`` pour build mon js et ne peut pas utiliser les outils de vue-cli.

## Conclusion

Je n'ai aucune idée quant à savoir si symfony nétoie les entrées utilisateur et si oui comment et où.

l'autowiring est très utile mais on se lasse assez rapidement de la "magie" de symfony qui devient très vite un problème quand on veut étendre les fonctionalités du framework et qu'il faut d'un seul coup comprendre toutes les intéractions entre les composants sous le capot(ex: le DataTransformer pour mes Tags)

ApiPlatform est horrible à utiliser en tant qu'API REST et devrai être requalifiée en tant qu'API primairement GraphQL et médiocre en tant qu'API REST. 

Après plusieurs jours de recherche, j'ai vu qu'il était pratiquement impossible de changer l'iri d'entitées reliées par juste leur id, et que la seule option permetant cela est vivement non recommendée(ce qui fait du sens pour GraphQL, mais aucun pour du REST!). 

J'ai apris à me servir rudimentairement de webpack.

### Ajouts VueJS pour le rendu du 10/04/2020:

J'ai apris à me servir de vue et de vuetify à travers ce mini-projet, et suis maintenant en train de les utiliser pour un projet personnel(web console pour monitoring d'une page web gérant le commerce d'un jeu auquel j'ai injecter un client websocket via mitm)






