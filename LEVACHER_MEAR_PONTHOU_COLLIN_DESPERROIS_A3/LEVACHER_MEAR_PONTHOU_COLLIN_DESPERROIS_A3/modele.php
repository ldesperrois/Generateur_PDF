<?php
   if (!(count($argv) > 3)){  
      echo "Erreur, vous devez renseigner un nom de fichier existant.\n";
      return;
   }
   $fileName = $argv[1];
   $code = strtoupper(explode("/", $fileName)[1]);
   $texte = file("$fileName/text.dat");
   $tableau = file("$fileName/tableau.dat");
   $comm = file("$fileName/comm.dat");

   $imgFolder = $argv[2];
   $logoFolder = $argv[3];

   $date = date('d-m-Y H:i');
   $dateYYYY = date('Y');
   $trimestre = intval(date('m'));
   $trimestre = intval($trimestre / 4)+1;

   $regionConf = file("region.conf");
   $region = preg_grep("/^$code/", $regionConf);
   
   if (!isset($region) || !($region != NULL)){
      return;
   } 
   $region = explode(":", array_shift($region));
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="../modele.css">
</head>
<body>
   <section class="pageun">

         <div class="milieu">
            <div class="gros-titre">
               <h1><?php echo $region[1]?></h1>
            </div>
            <div class="logo">
               <img src="<?php echo "../$logoFolder/${code}.png"?>">
            </div>
            <div class="details">
               <p><?php echo $region[2]?> Personnes</p>
               <p><?php echo $region[3]?> km²</p>
               <p><?php echo $region[4]?> départements</p>
            </div>

         </div>
         <div class="date"><?php echo "$date"?></div>
   </section>

   <section class="texte">
      <div class="titre">
      <h1>Résultats trimestriels <?php echo "$trimestre-$dateYYYY"?></h1>
      </div>
      
      <div class="paragraphe">
         <?php
            for ($i = 0; $i < count($texte); $i++){
               echo $texte[$i];
            }
         ?>
      </div>
      <div class="tableau">
         <?php echo $tableau[0] ?>
      </div>
      <div class="date"><?php echo "$date"?></div>
      
   </section>
   <section>
      <div class="titre">
         <h1>Nos meilleurs vendeurs du trimestre</h1>
      </div>
      
      <?php echo str_replace(",", "<br>", $comm[0])?>
      <div class="date"><?php echo "$date"?></div>
   </section>
   <section class="qrcode">
      <a href="https://bigbrain.biz/<?php echo "$code"?>">Pour en apprendre plus sur la région...</a>
      <img src="<?php echo "../$fileName/qrcode.png"?>" alt="QRCode" title="QRcode">
      <div class="date"><?php echo "$date"?></div>
   </section>
</body>
</html>