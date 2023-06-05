#!/bin/bash
#!/bin/bash
if [ $# -gt 1 ]; then
    if [ $1 == "IUT" ]; then
        prefix=""
    elif [ $1 == "AUTRE" ]; then
        prefix="bigpapoo/"
        echo "Installation de l'image ${prefix}sae103-imagick:latest"
        docker image pull ${prefix}sae103-imagick:latest
    else
        echo "Merci de preciser si vous êtes sur une machine de l'IUT ou non."
        exit -1
    fi

    if ! [ -d "$2" ]; then
        echo "Le dossier, contenant les images, specifier n'a pas étais trouvé !"
        exit -1
    fi

    #A verifier
    if [ $(ls $2/*.svg 2> /dev/null | wc -l) -eq 0 ]; then
        echo "Aucun fichier .svg n'est présent dans le dossier spécifier."
        exit -1
    fi 

    for img in "$2"/*.svg
    do
        echo "Traitement de l'image $img..."
        img_name=$(basename $img .svg)
        docker container run --rm -v $PWD/$2:/work ${prefix}sae103-imagick "magick $img_name.svg $img_name.png"
        docker container run --rm -v $PWD/$2:/work ${prefix}sae103-imagick "magick convert $img_name.png -crop 555x555+22+0 $img_name.png"
        docker container run --rm -v $PWD/$2:/work ${prefix}sae103-imagick "magick convert $img_name.png -resize 200x200 $img_name.png"
        docker container run --rm -v $PWD/$2:/work ${prefix}sae103-imagick "magick $img_name.png -colorspace gray $img_name.png"
        rm $2/$img_name.svg  
    done
else
    echo "Syntaxe: ./TraitementImage.sh (IUT | AUTRE) (Nom du dossier comportant les SVG)>"
fi