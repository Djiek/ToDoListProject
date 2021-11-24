# Contribuer à TODOLISTPROJECT
## Sommaire
***
Ce document explique comment contribuer à TODOLISTPROJECT. Ces instructions supposent que vous avez un compte GitHub.com, donc si vous n'en avez pas, vous devrez en créer un. Vos modifications de code proposées seront publiées dans votre propre fork du projet TODOLISTPROJECT et vous soumettrez une Pull Request pour que vos modifications soient ajoutées.
***

***
Les issues sont un moyen rapide de signaler un bogue. Si vous trouvez un bogue ou une erreur de documentation dans TODOLISTPROJECT, veuillez d'abord vérifier quelques points :
*   Qu'il n'y ai pas encore de issues ouvertes pour cette erreur
*   Que le problème n'a pas déjà été résolu (vérifiez la branche de développement ou recherchez les problèmes fermés)
*   Est-ce que ce n'est pas quelque chose que vous pouvez réparer vous-même ?
***

## Compatibilité
***
TODOLISTPROJECT recommande l'utilisation de PHP 7.3.12 
***

## Guide d'instruction - Forker Le code
***
Dans votre navigateur, accédez à : https://github.com/Djiek/ToDoListProject
"Forkez" le référentiel en cliquant sur le bouton 'Fork' en haut à droite. Le fork se produira et vous serez redirigé vers votre propre fork du référentiel. Copiez l'URL du référentiel Git en cliquant sur le presse-papiers à côté de l'URL sur le côté droit de la page sous « URL de clonage HTTPS ». Vous collerez cette URL lors de l'exécution de la git clonecommande suivante.
***
*   Sur votre ordinateur, suivez ces étapes pour configurer un référentiel local pour travailler sur ToDoListProject :
***
```
$ git clone https://github.com/YOUR_ACCOUNT/ToDoListProject
$ cd ToDoListProject
$ git remote add upstream https://github.com/Djiek/ToDoListProject
$ git checkout master
$ git fetch upstream
$ git rebase upstream/master
```

## Faire des changements
***
Il est important que vous créiez une nouvelle branche sur laquelle apporter des modifications et que vous ne modifiiez pas la branche master(autre que pour rebaser les modifications à partir de upstream/master). Dans cet exemple, je suppose que vous apporterez vos modifications à une branche appelée feature/my-new-feature. Cette branche sera créée sur votre référentiel local et sera poussée vers votre référentiel fork sur GitHub. Une fois que cette branche est sur votre fork, vous allez créer une Pull Request pour que les modifications soient ajoutées au projet ToDoListProject.

Il est recommandé de créer une nouvelle branche chaque fois que vous souhaitez contribuer au projet et de suivre uniquement les modifications de cette demande d'extraction dans cette branche.
```
$ git checkout -b feature/my-new-feature
(faites vos modifications)
$ git statut
$ git add . 
$ git commit -a -m " message de validation descriptif pour vos modifications "
$ git push 
```
Faire un test codacy avant d'envoyer la demande de fusion. Un badge B vous sera demandé.
Verifier que votre projet respecte bien les norme psr (Avec codesniffer par exemple)
Cliquez sur la pull request et créez la demande de fusion.
Nous seront alertés du changement et au moins un membre de l'équipe répondra. Si votre modification ne respecte pas les directives, elle sera rejetée ou des commentaires vous seront fournis pour vous aider à l'améliorer.

Une fois que nous seront satisfait, la demande sera fusioné dans dévelop et votre correctif fera partie de la prochaine version.
***

## Rebase feature/my-new-featurepour inclure les mises à jour de upstream/master
***
Il est important que vous mainteniez une masterbranche à jour dans votre référentiel local. Cela se fait en rebasant les modifications de code depuis upstream/master(le référentiel officiel du projet Okta PHP Samples) dans votre référentiel local. Vous voudrez le faire avant de commencer à travailler sur une fonctionnalité ainsi que juste avant de soumettre vos modifications en tant que demande d'extraction. Je vous recommande de faire ce processus périodiquement pendant que vous travaillez pour vous assurer que vous travaillez sur le code de projet le plus récent.

Ce processus effectuera les opérations suivantes :

Consultez votre master succursale locale
Synchronisez votre masterbranche locale avec le upstream/master afin que vous ayez toutes les dernières modifications du projet
Rebase le dernier code du projet dans votre feature/my-new-featurebranche afin qu'il soit à jour avec le code en amont
```
$ git checkout master
$ git fetch upstream
$ git rebase upstream/master
$ git checkout feature/my-new-feature
$ git rebase master
```
Maintenant, votre branche feature/my-new-feature est à jour avec tout le code au format upstream/master.
***

## Nettoyage après une pull request réussie
***
Une fois que la branche feature/my-new-feature a été ajouté dans la branche upstream/master, votre branche feature/my-new-feature locale et la branche origin/feature/my-new-feature ne sont plus nécessaires. Si vous souhaitez apporter des modifications supplémentaires, redémarrez le processus avec une nouvelle branche.

IMPORTANT : Assurez-vous que vos modifications on bien été envoyé vers upstream/master avant de supprimer vos branches feature/my-new-feature et origin/feature/my-new-feature !

Vous pouvez supprimer ces branches obsolètes avec les éléments suivants :
```
$ git checkout master
$ git branch -D feature/my-new-feature
$ git push origin :feature/my-new-feature
```
***