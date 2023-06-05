#!/bin/bash
if [ $# -gt 2 ]; then
    if [ $1 == "IUT" ]; then
        prefix=""
    elif [ $1 == "AUTRE" ]; then
        prefix="bigpapoo/"
        echo "Installation de l'image ${prefix}sae103-html2pdf:latest"
        docker image pull ${prefix}sae103-html2pdf:latest
    else
        echo "Merci de preciser si vous êtes sur une machine de l'IUT ou non."
        exit -1
    fi

    if ! [ -d "PDF" ]; then
        mkdir PDF
    fi 

    for reg in data/*
    do
        code=$(basename $reg)
        code=${code^^}
        docker container run --rm -v $PWD:/work ${prefix}sae103-php -f modele.php $reg $2 $3> PDF/file.html
        docker container run --rm -v $PWD:/work ${prefix}sae103-html2pdf "html2pdf PDF/file.html PDF/PDF_$code"
        rm PDF/file.html
    done

else
    echo "Syntaxe: ./Lancement.sh (IUT | AUTRE) (Nom du dossier comportant les fichiers textes)";
    echo "Pour plus d'info ouvrez le fichier README.md";
fi