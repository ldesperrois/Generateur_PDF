#!/bin/bash
if [ $# -gt 2 ]; then
    if [ $1 == "IUT" ]; then
        prefix=""
    elif [ $1 == "AUTRE" ]; then
        if ! [ -x "$(command -v docker)" ]; then
            echo "Installation de Docker..."
            curl -fsSl https://get.docker.com -o get-docker.sh
            sh get-docker.sh
            rm get-docker.sh
        else
            echo "Docker est déjà installé !"
        fi
        
        prefix="bigpapoo/"
        echo "Installation des images nécessaires..."
        echo "Installation de l'image ${prefix}sae103-php:latest"
        docker image pull ${prefix}sae103-php:latest
    else
        echo "Merci de preciser si vous êtes sur une machine de l'IUT ou non."
        exit -1
    fi

    if ! [ -d "$2" ]; then
        echo "Le dossier specifier n'a pas étais trouvé !"
        exit -1
    fi 


    if [ $(ls $2/*.txt 2> /dev/null | wc -l) -eq 0 ]; then
        echo "Aucun fichier .txt n'est présent dans le dossier spécifier."
        exit -1
    fi 

    if ! [ -d "$3" ]; then
        echo "Le dossier, contenant les images, specifier n'a pas étais trouvé !"
        exit -1
    fi

    if [ $(ls $3/*.svg 2> /dev/null | wc -l) -eq 0 ]; then
        echo "Aucun fichier .svg n'est présent dans le dossier spécifier."
        exit -1
    fi 

    if ! [ -d "$4" ]; then
        echo "Le dossier contenant les logos n'a pas étais trouvé !"
        exit -1
    fi

    # installation image sae php
    if [ -f "./region.php" ] 
    then
        for fic in "$2"/*.txt
        do
            docker container run --rm -v "$PWD:/work" ${prefix}sae103-php -f region.php "$fic" "$3"
        done

        echo "Traitement des images..."
        ./TraitementImage.sh $1 $3

        echo "Création des QRCodes..."
        ./QRCode.sh $1 $PWD/data

        echo "Création des PDF..."
        ./HTML2PDF.sh $1 $3 $4

        tar czvf PDF_REGION.tar.gz PDF/
        
        echo "Vos PDF se trouve dans l'archive PDF_REGION.tar.gz !"
        #Creation des QRCODES

    else
        echo "Erreur, le fichier region.php est introuvable !";
    fi
else
    echo "Syntaxe: ./Lancement.sh (IUT | AUTRE) (Nom du dossier comportant les fichiers textes) (Nom du dossier comportant les images svg) (Nom du dossier comportant les logos des régions.";
    echo "Pour plus d'info ouvrez le fichier README.md";
fi