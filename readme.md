# RAPPORT SITE WEB :

---

## Comment executer ?

Vous devez installer un serveur web (Apache ou autre). Pour la simplicité, nous allons choisir Wamp (windows) ou Lampp (unix).

Installez la base de données "db.sql" sur Php my admin ou mysql.

Lancez votre serveur web. Tout est prêt !

---

Site Web écrit en PHP Orienté Objet.

La connexion à la base de données est écrite en PDO.

L'architecture du site contient les classes utilitaires Classes/...
initialisation du site Core/init.php
fonction utilitaire Functions/sanitize.php
Leur fonctionnement est décrit plus bas.

Le site est protégé des injections SQL / Attaques CSRF. Les mots de passe sont concaténés a un "sel" et hachés en sha256 (md5 étant très ancien).

Le code est commenté et indenté.

Résumé: Fonctionnalités obligatoires réalisées : Toutes;
Fonctionnalités facultatives réalisées :
— Un (ou deux) bouton(s) permettant de signaler à l’administrateur des contenus inappropriés
ou faux.
— Un système de vote augmentant la visibilité des jeux appréciés par les utilisateurs.
— Un système de catégories (histoire, langues, . . .) pour les jeux. La création de nouvelles
catégories pourra être réservée à l’administrateur.
— Un système de scores donnés à l’utilisateur pour l’apprentissage d’un jeu donné. On peut
imaginer un score calculé comme un pourcentage de réponses justes, ou donner un nombre
de points différents selon les cartes, ou encore prendre aussi en ligne de compte la rapidité
des réponses. On peut aussi imaginer un best-off des meilleurs scores pour ce jeu, soit
anonyme, soit sous réserve de l’accord des utilisateurs concernés.
— Le site se rappelle quel est le dernier jeu sélectionné par l’utilisateur.
— Le site se rappelle quelles cartes du dernier jeu l’utilisateur doit refaire ou faire (s’il ne les
a pas faites).
— On peut aussi envisager de donner un niveau de difficulté globale à chaque jeu de cartes.

    	Base de données : 	cards : Les cartes en elles mêmes.
    						category : Les catégories de cartes.
    						games : Les jeux de cinq cartes.
    						groups : Les permissions (admins etc).
    						requests : Les requêtes pour les admins.
    						users : Les utilisateurs enregistrés.
    						users_session : Les utilisateurs ayant choisi de se connecter automatiquement.
    						fichier : db.sql

Ainsi que d'autres fonctionnalités décrites ci dessous.

---

---

## Fonctionnalités BACK-END:

-Classe DB qui effectue toutes les requêtes SQL en language naturel, tout en étant sécurisé (protégé contre les injections SQL) par des prepare.
Exemples: DB::getInstance()->get('users', array('username', '=', 'rayan'));
DB::getInstance()->update('cards', array('front' => 'hotel?', 'back' => 'trivago'));
Etc...

-Classe Cookie qui permet d'ajouter, supprimer, vérifier l'existence de manière intuitive des cookies.
Exemples: Cookie::add('dernier_jeu', '3', '86000');

-Classe Hash qui effectue la cryptographie des mots de passe en sha-256 (le md5 étant très ancien) et qui génère du sel, chaines de charactères aléatoires renforcant les mots de passe.
Exemples: Hash::make('mypass', Hash::salt(16));

-Classe Input qui gère tous les inputs sur toutes les pages. Elle vérifie, avec l'aide de la classe Validate, si ils sont corrects (pas de char spéciaux, réglage de taille min/max, de champ nécessaire ou non, unique dans une base de données ou non, et si il est identique a un autre champ).
Exemples: Voir register.php, sur le formulaire d'enregistrement.

-Classe Token qui génère un token aléatoire et qui peux le vérifier. Le token est généré automatiquement a chaque actualisation de la page en input hidden et en session php. Il permet ainsi d'éviter a un site externe d'effectuer des actions avec les données de nos cookies, car il n'a pas en sa possession le token.

-Classe Session qui gère la variable session, et qui permet d'afficher des messages qui seront affichés une seule fois.

-Classe User qui possède tous les attributs d'un utilisateur. Le constructeur sans argument receuillera les données de l'utilisateur courant et effectuer toutes sortes d'actions (déconnexion, update des valeurs, supprimer le compte, ajouter des valeurs dans la database, avoir toutes les valeurs dans la database etc..).
Les attributs sont faciles d'accès, exemple : $user = new User(); $user->data()->id contient l'id
Et s'il n'est pas login, permettera de le login / register ou de checker s'il est login actuellement. Le constructeur avec l'argument id permettera de récolter toutes les informations de l'utilisateur avec cet id, et permet d'effectuer les mêmes actions que citées précédemment.

-Classes CardGroup, CategoryGroup et GamesGroup gèrent les Catégories de cartes, les groupes de cartes et l'affichages des cartes ainsi que le questionnaire et le calcul des points. Un affichage d'une carte est très facile :
CardGroup::addCard($front, $back, $idC, $card, $tem); ($idC, $card, $tem) sont des variables qui gèrent l'affichage en ordre et en catégories.

-Classe Redirect qui est juste utilitaire, permet de faire Redirect::to('index'); par exemple.

-Core/Init.php Fera charger toutes les classes dans toutes les pages, et avec Config.php, elle rendra les variables récurrentes faciles d'accès.

-Accept.php/reject.php, permettent d'accepter ou de refuser des utilisateurs au rang d'admin entre autres.

-del_acc.php permet de supprimer le compte courant de l’utilisateur.

-logout.php est la page qui logout l'utilisateur.

-changerole.php permet à l’admin de changer les différents niveaux de responsabilités des utilisateurs (mettre un créateur en admin et inversement).

-del_user.php permet à un admin de supprimer le compte de n’importe quel utilisateur

-sanitize.php qui échappe les caractères spéciaux

-cookie_del qui va supprimer les cookies des jeux de cartes d'un utilisateur

Fonctionnalités FRONT-END:
(testé sur Chrome et Firefox)

-index.php est une page d'acceuil qui affichera les catégories de jeux de cartes et les paquets, ainsi que les pages scoreboard, login et register, et si l'utilisateur est connecté : les pages create, logout et account (en cachant login et register).

-games.php permet de naviguer dans un jeu de cartes, et se rappelle du dernier jeu de cartes sélectionné par un utilisateur connecté et redirige vers ce même jeu si l'utilisateur le souhaite tant qu’il n’est pas terminé (et bloque l'accès aux autres jeux tant qu'il n'est pas terminé ou annulé). Les cartes sont cliquables pour les retourner et on peut les parcourir grâce aux boutons Next et Previous. Puis on à un questionnaire sur ce même jeu qui vérifie que vous avez répondu juste et vous donne votre note et la moyenne. Votre score augmente également de 1pt par bonne réponse avec le total multiplié par la difficulté du jeu (ex : j’ai 80% de bonne réponse sur un jeu de niveau 2 alors j’aurais 4\*2 pts ajouté à mon score total)
Il est possible de signaler les cartes, elles seront affichées chez les admins sur mygames. On peut également mettre un like au paquet de carte pour mieux les référencer (étant donné que le paquet ayant le plus de likes se retrouve en haut de page et que les paquets dans chaque catégorie sont affichés par nombre de likes).

-account.php est une page gérant le compte courant. Elle permet de changer le nom de l'utilisateur, son pseudo, son mot de passe, son rang (sauf admin), et lui permettera de demander à être admin. Un admin pourra accepter ou refuser sa requête sur la page requests.
Les admins ont des fonctionnalités multiples, dont supprimer un utilisateur, enlever le grade admin etc...

-login.php gère le login utilisateur. Elle n'est accessible que si l'utilisateur n'est pas login. Elle va vérifier les champs, et sécuriser la requête. Sécurisée CSRF par token.
Login.php utilise un champ 'se rappeler de moi' qui permet de login l'utilisateur automatiquement la prochaine fois sans passer par la page login.
LOGINS administrateur (pour testing) : username : superuser
password : superuser

-register.php gère les registers. Elle vérifie les champs aussi, et est autant sécurisée que login. Sécurisée CSRF par token.

-manage.php gère les cartes. Un utilisateur par défaut ne pourra pas accèder a la page, mais un rédacteur peut ajouter des jeux de cartes personnalisés (un nom, 5 questions et la difficulté), et un admin peut même ajouter des catégories de cartes. Tout apparait automatiquement sur la page index.php.

-requests.php gère les requêtes utilisateurs pour être admins. Elle n'est accessible que par un administateur.

-mygames.php est inaccessible pour les simples utilisateurs. Les créateurs voient les jeux qu’ils ont crées et peuvent les modifier (Que ceux qu’ils ont créés). Les administrateurs peuvent voir les jeux de tout le monde ainsi que les modifier dans l'ordre du nombre de reports.

-settings.php est uniquement accessible par les admins (les utilisateurs n’ont aucun accès et les créateurs sont redirigés vers mygames.php). Il y a dessus l’accès a 4 liens : mygames.php, categories.php, manage_users.php et requests.php.

-categories.php (admin seulement) affiche l’id et le nom de chacune des catégories ainsi qu’une option pour en supprimer.

-manage_users.php (admin seulement) affiche l’id, le pseudo, le nom, le rôle, la date + heure de création du compte de chaque utilisateur avec l’option de donner un rôle supérieur ou inférieur (tel que user < creator < admin) ou alors supprimer un compte. Il y a la possibilité d’afficher le tableau dans n’importe quel ordre : croissant ou décroissant valable pour tout : id, pseudo, nom, rôle, date de création.

-scoreboard.php affiche le nom, le score et la place de chaque utilisateur.

---

---

## PARCOURIR LE SITE:

Vous disposez de 2 comptes: superuser, creator (avec le mot de passe similaire au pseudo).

Vous pouvez en premier naviguer sur le site sans vous connecter. Vous avez accès aux jeux de cartes, au scoreboard (que vous pouvez trier) ainsi qu'au login et au register. Vous ne pouvez accéder à aucune autre page. Vous n'êtes pas obliger de finir les jeux de cartes et ça ne rapporte aucun points (seulement un score temporaire) et vous ne pouvez pas mettre de like.
Vous pouvez ensuite créer un compte. Vous êtes redirigé sur la page de login où vous pouvez soit vous connecter avec ce nouveau compte ou alors avec le creator. Dans les deux cas vous ne serez qu'un simple utilisateur.

En tant qu'utilisateur vous pouvez voir votre nom dans le scoreboard. Deux nouveaux liens s'affichent "create", "My account" et "logout". En cliquant sur "create" on est redirigé sur index en nous demandant d'être un créateur. "logout" va simplement déconnecter l'utilisateur. Maintenant quand un jeu est lancé, même si on le quitte (même en se déconnectant) en allant sur d'autre lien il y aura un message sur la page index proposant à l'utilisateur de continuer ou non le jeu. "No" va supprimer les cookies, et l'utilisateur pourra faire d'autres jeux, "Yes" va le rediriger vers la dernière question où il était. "My account" affiche les infos de l'utilisateur avec possibilité de les modifier (sauf la date d'arrivée, normal) sans pouvoir choisir un utilisateur déjà existant. On peut ensuite changer le rôle en tant que "Creator".

Il y a deux options supplémentaires: créer des cartes (Un nom, une description, 5 questions/réponses, la difficulté et la catégorie) et "manage" (settings.php) qui redirige un créateur vers mygames.php, une page affichant les jeux créés par cet utilisateur ainsi que la possibilité de les modifier/supprimer (que les siens). Vous pouvez ensuite vous connecter sur le compte superuser.

Avec un compte admin, manage (settings.php) ne vous redirige pas et vous avez 4 liens:
-My games (mygames.php) avec vos jeux et ceux des autres utilisateurs (possibilité de les modifier/supprimer) dans l'ordre des signalements (vous pouvez remarquer qu'il y a un jeu avec 19 signalements "Prog :)" qui est totalement incohérent).
-Categories (categories.php) avec l'affichage de toutes les catégories et possibilité de les supprimer
-Users (manage_users.php) qui affiche toutes les infos des utilisateurs (sauf mot de passe) avec la possibilité de les rank up/down ou de supprimer leurs comptes
-Requests (requests.php) qui affiche les requêtes des utilisateurs pour devenir admin avec possibilité de les accepter ou refuser
Dans create (manage.php) vous pouvez aussi créer de nouvelles catégories qui seront visibles par tout le monde
