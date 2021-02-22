<!-- PHP Vérification avant ajout produit -->

<?php
    // Tout est mis à 0
    $nomErr = $categorieErr = $prixErr = $imageErr = "";
    $nom = $categorie = $prix = $image = "";
    $n = 0;
    $succes = "";

    // Traitement d'ajout après soumission de formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // vérification nom
        if ( isset($_POST['nom']) && $_POST['nom'] != "" )
        {
            $fichier = file("bdd.txt");
            for ($i = 0; $i < count($fichier); $i++)
            {
                $explore = explode(":",$fichier[$i]);
                $nom = $_POST['nom'];
                $nom = strtolower($nom); // mettre en minuscule
                $explore[0] = strtolower($explore[0]);
                if ($nom == $explore[0])
                {
                    $n++;
                }
            }
            if ($n != 0)
            {
                $nomErr = " * Le nom saisi est déjà pris!";
                $nom = "";
            }
            else{
                $nom = $_POST['nom'];
                $n = 0;
            }
        }
        else
        {
            $nomErr = " * Saisissez le nom du produit";
        }

        // vérification catégorie:
        if ( isset($_POST['categorie']) && $_POST['categorie'] != "" )
        {
            $categorie = $_POST['categorie'];
        }

        // vérification prix
        if( isset($_POST['prix']) && $_POST['prix'] != "" )
        {
            $prix = $_POST['prix'];
            if ($prix < 0)
            {
                $prixErr = " * Le prix doit etre supérieur à 0!";
                $prix = "";
            }
        }
        else
        {
            $prixErr = " * Saisissez le prix du produit";
        }
        
        // vérification image
        if ( isset($_FILES['image']) && $_FILES['image']['name'] != "" )
        {
            $image = $_FILES['image']['name']; // Récupération du nom d'image
            $extensions = array('.png', '.jpg', '.jpeg'); // Création d'un tableau contenant les différents formats d'une image
            $extension = strrchr($_FILES['image']['name'], '.'); // Récupération du format d'image donnée
            if(!in_array($extension, $extensions)) // Vérification du format d'image
            {
                $imageErr = "Le format accepté .png | .jpg | .jpeg";
                $image = "";
            }
        }
        else
        {
            $imageErr = " * Choisissez l'image du produit!";
        }
    }
?>

<!-- PHP Ajout produit -->

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ( $nom != "" && $categorie != "" && $prix != "" && $image != "")
        {
            $dossier = 'images/';
            $fichier = basename($_FILES['image']['name']); // Récupération du nom d'image
            move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier); // Déplacement d'image dans /images
            $fp = fopen("bdd.txt", "a");
            $savestring = "\n".$nom.":".$categorie .":".$prix.":".$image.":"; // Ligne du nouveau produit
            fwrite($fp, $savestring); // Insértion du nouveau produit
            fclose($fp);
            $nom = $categorie = $prix = $image = "";
            $succes = "ajout";
        }
    }
?>

<!-- PHP Modification produit -->

<?php
    // Traitement avant modification de produit
    if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" )
    {
        $modifier = $_GET['modifier']; // Récupération du nom du produit à modifier
        $nom = $modifier;
        $fichier = file("bdd.txt");
        for ($i = 0; $i < count($fichier); $i++) 
        {
            $explore = explode(":",$fichier[$i]);
            $nomf = strtolower($nom); // Mettre en minuscule
            $explore[0] = strtolower($explore[0]);
            if ($nomf == $explore[0])
            {
                $categorie = $explore[1]; // Récupération du catégorie depuis bdd
                $prix = $explore[2]; // Récupération du prix depuis bdd
                $image = $explore[3]; // Récupération du nom d'image depuis bdd
            }
        }
    }
    // Traitement de modification de produit
    if ( !empty($_GET['soumis']) && $_GET['soumis'] == "true" )
    {
        $nom = $_GET['nom']; // Récupération du nom après la soumission
        $categorie = $_POST['categorie']; // Récupération de catégorie après la soumission
        $prix = $_POST['prix']; // Récupértion du prix après la soumission
        $image = $_POST['image']; // Récupération du nom d'image après la soumission
        $fichier = file("bdd.txt"); // Traitement bdd
        for ($i = 0; $i < count($fichier); $i++) // Boucle pour trouver le bon produit avec le nom choisi
        {
            $explore = explode(":",$fichier[$i]); // Traitement des lignes de bdd
            if ($nom == $explore[0]) // Si nom du produit choisi correspond au nom de la ligne > traitement de la ligne
            {
                if ($categorie != $explore[1] || $prix != $explore[2] || $image != $explore[3]) // Vérification si quelque chose a été modifié
                {
                    $ptr = fopen("bdd.txt", "r"); // Traitement bdd
                    $contenu = fread($ptr, filesize("bdd.txt")); // Duplicat de bdd
                    fclose($ptr);
                    $contenu = explode(PHP_EOL, $contenu);  // Saut à la ligne utilisé sur le serveur
                    $contenu[$i] = $nom.":".$categorie .":".$prix.":".$image.":"; // Remplacement de la ligne par une autre ligne
                    $contenu = array_values($contenu); // Ré-indexe l'array
                    $contenu = implode(PHP_EOL, $contenu); 
                    $ptr = fopen("bdd.txt", "w"); // Ouverture pour écriture
                    fwrite($ptr, $contenu); // Insértion de bdd modifié
                    fclose($ptr);
                }
            }
        }
        $succes = "modif";
        $nomErr = $categorieErr = $prixErr = $imageErr = "";
        $nom = $categorie = $prix = $image = "";
    }
?>

<!-- Formulaire d'ajout/modification -->

<div class="w3-white" id="formulaire" style="height:640px">
    <div class="w3-container w3-content w3-padding-64" style="<?php if ($succes != "") echo "display:none;"; ?>">
            <section class="w3-card-4 w3-margin">
                <div class="w3-container w3-black">
                    <?php
                        if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" )
                            echo "<h2>Formulaire de modification</h2>";
                        else
                            echo "<h2>Formulaire d'ajout</h2>";
                    ?>
                </div>
                <form method="POST" id="form" class="w3-container" enctype="multipart/form-data" action="
                <?php
                    if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" )
                        echo "index.php?page=formulaire.php&nom=$nom&soumis=true";
                    else
                        echo 'index.php?page=formulaire.php';
                ?>">
                    <p>
                    <label for="nom">Nom du produit:</label>
                    <span class="error" style = "color: #FF0000"><?php echo $nomErr;?></span>
                        <input type="text" name="nom" id="nom" class="w3-input w3-border" placeholder="Ex: Iphone 11" onfocus="this.placeholder='Ex: Iphone 11'" 
                        onMouseout="this.placeholder='Ex: Iphone 11'" value="<?php echo $nom; ?>" <?php 
                        if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" ) echo "disabled"; ?> />
                    </p>
                    <p>
                    <label for="categorie">Catégorie:</label>
                        <select name="categorie" id="categorie" class="w3-select">
                            <option value="Mac" <?php if (isset($categorie) && $categorie == "Mac") echo "selected";?> >Mac</option>
                            <option value="iPad" <?php if (isset($categorie) && $categorie == "iPad") echo "selected";?> >iPad</option>
                            <option value="iPhone" <?php if (isset($categorie) && $categorie == "iPhone") echo "selected";?> >iPhone</option>
                            <option value="Watch" <?php if (isset($categorie) && $categorie == "Watch") echo "selected";?> >Watch</option>
                            <option value="TV" <?php if (isset($categorie) && $categorie == "TV") echo "selected";?> >TV</option>
                        </select>
                    </p>
                    <p>
                    <label for="prix">Prix (en $): <span id="erreurs" style = "color: #FF0000; display: none;"></span></label>
                    <span class="error" style = "color: #FF0000"><?php echo $prixErr;?></span>
                        <input type="number" name="prix" id="prix" class="w3-input w3-border" placeholder="Ex: 999$" onfocus="this.placeholder='Ex: 999$'" 
                        onMouseout="this.placeholder='Ex: 999$'" value="<?php echo $prix; ?>"/>
                    </p>
                    <p>
                    <label for="image" >Image:</label>       
                    <span class="error" style = "color: #FF0000"><?php echo $imageErr;?></span>
                    <br>
                        <?php
                            if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" )
                                echo "<input  type='text' class='w3-input w3-border' id='image' name='image' value='$image'/>";
                            else
                                echo '<input  type="file" id="image" name="image"/>';
                        ?>
                    </p>
                    <p class="w3-center">
                        <button type="button" class="w3-btn w3-black w3-mobile w3-border" id="bouton" onclick="<?
                        if ( !empty($_GET['modifier']) && $_GET['modifier'] != "" )
                            echo 'SoumettreModif();';
                        else
                            echo 'Soumettre();';
                        ?>">Soumettre</button>
                        <button type="reset" class="w3-btn w3-black w3-mobile w3-border">Annuler</button>
                    </p>
                </form>
            </section>
    </div>
    <?php
                if($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    if ($succes == "ajout")
                    {   
                        echo "  <div class='w3-panel w3-green w3-round w3-center w3-display-middle'>
                                <h3 class='w3-center'>Votre produit a été ajouté avec succès!</h3>
                                <p><a href='index.php?page=produits.php' class='w3-button w3-border w3-border-white w3-round-large'>Voir les produits</a></p>
                                </div>";
                    }
                    else if ($succes == "modif")
                    {   
                        echo "  <div class='w3-panel w3-green w3-round w3-center w3-display-middle'>
                                <h3 class='w3-center'>Votre produit a été modifié avec succès!</h3>
                                <p><a href='index.php?page=produits.php' class='w3-button w3-border w3-border-white w3-round-large'>Voir les produits</a></p>
                                </div>";
                    }
                }
    ?>
</div>



<script language="Javascript">
    // Vérification pour modification
    function VerifPrix(){
        let prix = document.getElementById("prix").value; // Récuperation du prix
        let erreur = document.getElementById("erreurs");
        if (prix >= 0)
        {
            return true;
        }
        else
        {
            erreur.innerHTML = " * Le prix doit etre supérieur à 0!"; // Ecriture d'erreur
            erreur.style.display = "block"; // Affichage du block avec erreur
            return false;
        }
    }

    // Soumission pour modification
    function SoumettreModif()
    {
        let prix = VerifPrix(); 
        if (prix == true)
        {
            document.getElementById("form").submit();
        }
    }

    // Soumission pour ajout
    function Soumettre()
    {
        document.getElementById("form").submit();
    }

</script>