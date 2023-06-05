#!/usr/bin/php
<?php
    if (!(count($argv) > 2)){  
        echo "Erreur, vous devez renseigner un nom de fichier existant.\n";
        return;
    }
    $fileName = $argv[1];
    $imgFile = $argv[2];
    $file = file($fileName);
    $j = 0;
    while ($j < count($file) && trim($file[$j]) == ""){
        $j = $j + 1;
    }
    $idLine = trim(explode("=", $file[$j])[1]);
    if (!is_dir("data")){
        mkdir("data");
    }
    if (!is_dir("data/$idLine")){
        mkdir("data/$idLine");
    }
    $idLine = "data/$idLine";
    creeFichier("$idLine/text.dat");
    creeFichier("$idLine/tableau.dat");
    creeFichier("$idLine/comm.dat");

    for ($i = 1; $i < count($file); $i++){

        $stri = formatToCompare($file[$i]);
        if (startWith(formatToCompare($file[$i]), "TITRE=")){
            $titre = trim(explode("=", $file[$i])[1]);
            ecrireFichier("$idLine/text.dat", "<h1>$titre</h1>");
        }

        if (startWith(formatToCompare($file[$i]), "SOUS_TITRE=")){
            $titre = trim(explode("=", $file[$i])[1]);
            ecrireFichier("$idLine/text.dat", "<h2>$titre</h2>");
        }

        if (formatToCompare(trim($file[$i])) == "DEBUT_TEXTE"){
            $i++;
            $chaine = "<p>";
            while (formatToCompare(trim($file[$i])) != "FIN_TEXTE"){
                $texte_replace = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', rtrim($file[$i]));
                $chaine .= $texte_replace;
                $i++;
            }
            $chaine .= "</p>";
            ecrireFichier("$idLine/text.dat", $chaine); 
        }

        if (formatToCompare(trim($file[$i])) == "DEBUT_STATS"){
            $i++;
            $table = "<table><tr><th>Nom du Produit</th><th>Vente du trimestre</th><th>Chiffre d'affaires du trimestre</th><th>Vente du mêmle trimestre année précédente</th><th>CA du même trimestre année précédente</th><th>Evolution du CA en %</th></tr>";
            while (formatToCompare(trim($file[$i])) != "FIN_STATS"){
                $row = explode(",", $file[$i]);
                $valeur_final = intval($row[2]);
                $valeur_initial = intval($row[4]);
                $valeur_absolue = ($valeur_final - $valeur_initial);
                $taux_evol = $valeur_absolue / $valeur_initial;
                $table .= "<tr>";
                foreach ($row as $data){

                    $table .= "<td>".trim($data)."</td>";
                }
                if ($valeur_absolue > 0){
                    $table .= "<td class=\"positif\">".round($taux_evol, 2)."% - $valeur_absolue</td>";
                } else {
                    $table .= "<td class=\"negatif\">".round($taux_evol, 2)."% - ".abs($valeur_absolue)."</td>";
                }
                $table .= "</tr>";
                $i++;
            }
            $table .= "</table>";
            ecrireFichier("$idLine/tableau.dat", $table); 
        }

        if (startWith(formatToCompare($file[$i]), "MEILLEURS:")){
            $vendeurs = explode(",", deleteStarting($file[$i], "MEILLEURS:"));
            for ($j = 0; $j < count($vendeurs); $j++){
                $chaineIni = $vendeurs[$j];
                if (strpos($chaineIni, "/") !== false){
                    $vendeurs[$j] = explode("/", $chaineIni);
                    $vendeurs[$j][0] = strtolower($vendeurs[$j][0]);
                    $vendeurs[$j][1] = explode("=", explode("/", $chaineIni)[1])[0];
                    $vendeurs[$j][2] = intval(str_replace("K€", "", explode("=", explode("/", $chaineIni)[1])[1]));
                }
            }
            uasort($vendeurs, 'comparaison');
            
            $texte = "<div class=\"flex-row\">"; 

            $max_i = (count($vendeurs) >= 3) ? 3 : count($vendeurs); 

            for($j = 0; $j < $max_i; $j++){
                $texte .= "<figure><img src=../${imgFile}/{$vendeurs[$j][0]}.png><figcaption>{$vendeurs[$j][1]},{$vendeurs[$j][2]} 000€</figcaption></figure>";
            }
            $texte .= "</div>";
            ecrireFichier("$idLine/comm.dat", $texte);
        }
    }

    function comparaison($a, $b){
        if ($a[2] == $b[2]){
            return 0;
        } 
        return ($a[2] < $b[2]) ? -1 : 1;
    }

    function creeFichier($nom){
        $f = fopen($nom, "w+");
        fclose($f);
    }

    function ecrireFichier($nom, $contenue){
        $f = fopen($nom, "a");
        fwrite($f, $contenue."\n");
        fclose($f);
    }

    function startWith($chaine, $debut){
        $taille = strlen($debut);
        return (substr($chaine, 0, $taille) === $debut);
    }

    function deleteStarting($chaine, $delete){
        $taille = strlen($delete);
        return substr($chaine, $taille);
    }

    function formatToCompare($string){
        $str = $string;
        $str = str_replace('é', 'e', $str);
        $str = strtoupper($str);
        return $str;
    }
?>

