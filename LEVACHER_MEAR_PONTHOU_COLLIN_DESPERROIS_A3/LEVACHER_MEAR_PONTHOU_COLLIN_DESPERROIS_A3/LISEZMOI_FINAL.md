# Documentation du fichier "region.php".

## Présentation 

Ce script permet la transformaton de fichier texte vers des PDF's.

## Installation Pré-Requise

Si vous vous trouvez sur les PC de l'IUT, vous devez installer les différentes images Docker vous même pour cela tappez les commandes suivantes une par une: 

```bash
docker image pull sae103-php:latest
docker image pull sae103-imagick:latest
docker image pull sae103-qrcode:latest
docker image pull sae103-html2pdf:latest
```

## Execution du Script

Créer un dossier ou vous travaillerais, si vous travailler sur un PC de l'IUT, vous devez vous placer dans le dossier `/Docker/<votre_login>/`.

Placez l'**ensemble** des fichiers **contenues** dans l'archive que vous avez téléchargé.

Puis creer un dossier, nommé le comme vous voulez, où vous placerez vos fichiers **textes** et faites de même pour les **images**, ainsi que les **logos** des régions.

Ensuite accordez les droits sur l'ensemble des fichiers contenues dans le dossier de travail, pour ce faire, placez vous dans le dossier de travail est éxecuter: `chmod -R 777 .`

Enfin lancer le script via la commande : `./Lancement.sh AUTRE <nom_du_dossier_texte> <nom_du_dossier_image> <nom_du_dossier_logo>` si vous êtes sur une machine hors IUT.

Si vous êtes à l'IUT lancé le script via la commande : 
`./Lancement.sh IUT <nom_du_dossier_texte> <nom_du_dossier_image> <nom_du_dossier_logo>`

Finalement vous retrouverez vos PDF dans le dossier `PDF/` ainsi que dans l'archive `PDF_REGION.tar.gz`.

(Bash ne nous permet pas de modifier les permissions des fichiers du moins pas d'après mes tests d'où la création du dossier manuellement.)