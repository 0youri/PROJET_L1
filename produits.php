<?php
    // Traitement PHP pour afficher des produits
    $fichier = file("bdd.txt"); // Traitement bdd
    $anom = array(); // Création du tableau avec les noms de produits
    $acategorie = array(); // Création du tableau avec les catégories de produits
    $aprix = array(); // Création du tableau avec les prix de produits
    $aimage = array(); // Création du tableau avec les noms des images de produits
    for ($i = 0; $i < count($fichier); $i++) // Boucle de récupération de données de bdd
    {
      $explore = explode(":",$fichier[$i]); // Création de ligne i
      $anom[$i] = $explore[0]; // Stockage du nom de la ligne i
      $acategorie[$i] = $explore[1]; // Stockage de la catégorie de la ligne i
      $aprix[$i] = $explore[2]; // Stockage du prix de la ligne i
      $aimage[$i] = $explore[3]; // Stockage du nom d'image de la ligne i
    }
    // Tout est mis à 0
    $nomErr = $categorieErr = $prixErr = $imageErr = ""; 
    $nom = $categorie = $prix = $image = "";
?>

<!-- Affichage des produits de PHP à HTML  -->
<div class="w3-black">
  <div class="w3-container w3-content w3-padding-64">
    <h2 class="w3-wide w3-center">NOS PRODUITS<br>
    <!-- Filtre par catégorie  -->
    <select class="w3-center" id="filtre" onchange="filtre()">
    <option value="Tout" >Tout</option>
    <option value="Mac" >Mac</option>
    <option value="iPad" >iPad</option>
    <option value="iPhone" >iPhone</option>
    <option value="Watch" >Watch</option>
    <option value="TV" >TV</option>
    </select>
    </h2>
  
      <div class="w3-row-padding w3-padding-32">
        <?php
          // Affichage des produits
          if (count($fichier) != 0) // Tant que tous les produits ne seront affichés
          {
            for ($i = 0; $i < count($fichier); $i++) // Boucle d'affichage
            {
              echo "<div class='w3-third w3-margin-bottom' id='produit$i'>";
              echo "<div class='w3-display-container w3-hover-opacity' style='width:100%'>";
              echo "<img src='images/" . $aimage[$i] . "' style='width:100%' class='w3-border'>";
              echo "<div class='w3-display-middle w3-display-hover w3-xlarge'>
                    <button class='w3-button w3-black w3-margin-bottom w3-round'>
                    <a style='text-decoration:none' href='index.php?page=produits.php&acheter=".$anom[$i]."'>Acheter</a></button>";
              echo "</div>";
              echo "</div>";
              echo "<div class='w3-container w3-white'>";
              echo "<p><b>" . $anom[$i] . "</b></p>";
              echo "<p class='w3-opacity' id='categorie$i'>" . $acategorie[$i] . "</p>";
              echo "<p>" . $aprix[$i] . "$</p>";
              echo "<button class='w3-button w3-black w3-margin-bottom w3-mobile'>
                    <a style='text-decoration:none' href='index.php?page=produits.php&modifier=".$anom[$i]."'>Modifier</a></button>";
              echo "<button class='w3-button w3-black w3-margin-bottom w3-right w3-mobile'>
                    <a style='text-decoration:none' href='index.php?page=produits.php&supprimer=".$anom[$i]."'>Supprimer</a></button>";
              echo "</div>";
              echo "</div>";
            }
          }
        ?>
        <!-- Pour éviter que footer (pied de la page) bouge  -->
        </br></br></br></br></br></br></br></br></br></br></br></br></br> 
      </div>
  </div>
</div>


<?php
  // Suppression de produit
  if ( !empty($_GET['supprimer']) && $_GET['supprimer'] != "")
  {
    $supprimer = $_GET['supprimer']; // Récupération du nom de produit
    $fichier = file("bdd.txt"); // Traitement bdd
    for ($i = 0; $i < count($fichier); $i++) // Boucle de la suppression
    {
      $explore = explode(":",$fichier[$i]); // Récupération de la ligne i pour trouver la bonne ligne
      if ($supprimer == $explore[0]) // Si le nom du produit correspond avec le nom du produit récupéré pour supprimer
      {
        $fimage = "images/$explore[3]"; 
        if( file_exists ($fimage) ) // Vérification d'existance d'image pour le produit
        {
          unlink($fimage); // Suppression d'image
        }
        // Récupération de bdd
        $ptr = fopen("bdd.txt", "r"); 
        $contenu = fread($ptr, filesize("bdd.txt"));
        fclose($ptr);
        $contenu = explode(PHP_EOL, $contenu); // Saut à la ligne utilisé sur le serveur
        unset($contenu[$i]); // Suppression de la ligne choisie
        $contenu = array_values($contenu); // Ré-indexe l'array
        $contenu = implode(PHP_EOL, $contenu);
        $ptr = fopen("bdd.txt", "w");
        fwrite($ptr, $contenu); // Insértion du bdd sans la ligne supprimé
        fclose($ptr);
        echo("<meta http-equiv='refresh' content='0; url=index.php?page=produits.php'>"); // Lien sans &supprimer...
      }
    }
  }

  // Lien pour modification de produit
  else if ( !empty($_GET['modifier']) && $_GET['modifier'] != "")
  {
    $modifier = $_GET['modifier']; // Récupération du nom du produit
    echo("<meta http-equiv='refresh' content='0; url=index.php?page=formulaire.php&modifier=$modifier'>"); // Redirection vers formulaire modifier avec le nom à modifier
  }

  else if ( !empty($_GET['acheter']) && $_GET['acheter'] != "")
  {
    $acheter = $_GET['acheter']; // Récupération du nom du produit
    echo("<meta http-equiv='refresh' content='0; url=index.php?page=achat.php&acheter=$acheter'>"); // Redirection vers formulaire acheter avec le nom à acheter
  }
?>

<script language="Javascript">
// Filtrage par catégorie
function filtre()
{
  let select = document.getElementById("filtre").value; // Récupération de la catégorie choisie
  let i = 0;
  let stop = "false";
  while (stop != "true")
  {
    if ( document.getElementById("produit" + [i]) ) // Vérification si produit* existe
    {
      let produit = document.getElementById("produit" + [i]); // Récupération du produit*
      let categorie = document.getElementById("categorie" + [i]).innerHTML; // Récupération de catégorie du produit*
      // Vérification si catégorie correspond au filtrage
      if (categorie != select && select != "Tout")
        produit.style.display = "none";
      else if (categorie == select && select != "Tout") 
        produit.style.display = "block";
      else
        produit.style.display = "block";
    }
    else
    {
      stop = "true";
    }
    i++;
  }
}
</script>