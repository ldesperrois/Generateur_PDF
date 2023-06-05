if [ $# -gt 1 ]
then
    if [ $1 == "IUT" ]; then
        prefix=""
    elif [ $1 == "AUTRE" ]; then
        prefix="bigpapoo/"
        echo "Installation de l'image ${prefix}sae103-qrcode:latest"
        docker image pull ${prefix}sae103-qrcode:latest
    else
        echo "Merci de preciser si vous êtes sur une machine de l'IUT ou non."
        exit -1
    fi
    for folder in $2/*
    do
        code=$(echo ${folder^^} | cut -d/ -f6)
        docker container run --rm -v $folder:/work ${prefix}sae103-qrcode qrencode -o qrcode.png "https://bigbrain.biz/$code" 
    done
else
    echo "Syntaxe ./QRCode.sh (IUT|AUTRE) <dossier_data>"
fi