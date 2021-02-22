<!-- PHP Vérification avant ajout produit -->

<?php
    // Tout est mis à 0
    $nbcbErr = $dateErr = $cdsErr = $nomXErr = $prenomXErr = "";
    $nom = $prix = $type = $nbcb = $mois = $year = $cds = $prenomX = $nomX = "";
    $succes = "";

    // Traitement d'ajout après soumission de formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // vérification type cb:
        if ( isset($_POST['type']) && $_POST['type'] != "" )
        {
            $type = $_POST['type'];
        }

        // vérification carte bancaire
        if( isset($_POST['nbcb']) && $_POST['nbcb'] != "" )
        {
            $nbcb = $_POST['nbcb'];
            if ( strlen($nbcb) != 16 ) // Si l'ensemble n'est pas égal à 16
            {
                $nbcbErr = " * Le numéro de la carte bancaire doit avoir 16 chiffres!";
                $nbcb = "";
            }
        }
        else
        {
            $nbcbErr = " * Saisissez le numéro de votre carte bancaire!";
        }
        
        // vérification la date 
        if( isset($_POST['mois']) && $_POST['mois'] != "" && isset($_POST['year']) && $_POST['year'] != "" )
        {
            $mois = $_POST['mois'];
            $year = $_POST['year'];
            if ( $mois < 1 && $mois > 12 || $year < 2019) // vérification si les données de la carte sont respectés
            {
                $dateErr = " * La date de votre carte bancaire est incorrecte!";
                $mois = "";
                $year = "";
            }
            else if ( $mois < 6 && $year == 2019 ) // vérification si la carte n'est pas expirée
            {
                $dateErr = " * Votre carte bancaire est expirée!";
                $mois = "";
                $year = "";
            }

        }
        else
        {
            $dateErr = " * Saisissez la date d'expiration de votre carte bancaire!";
        }

        // vérification de code de sécurité
        if( isset($_POST['cds']) && $_POST['cds'] != "" )
        {
            $cds = $_POST['cds'];
            if ( strlen($cds) != 3 )
            {
                $cdsErr = " * Le code de sécurité doit avoir 3 chiffres!";
                $cds = "";
            }
        }
        else
        {
            $cdsErr = " * Saisissez le code de sécurité de votre carte bancaire!";
        }

        // vérification nom et prénom
        if( isset($_POST['nomX']) && $_POST['nomX'] != "" && isset($_POST['prenomX']) && $_POST['prenomX'] != "" )
        {
            $prenomX = $_POST['prenomX'];
            $nomX = $_POST['nomX'];
        }


    }
?>

<!-- PHP Achat du produit -->

<?php
    // Traitement avant achat du produit
    if ( !empty($_GET['acheter']) && $_GET['acheter'] != "" )
    {
        $acheter = $_GET['acheter']; // Récupération du nom du produit à modifier
        $nom = $acheter;
        $fichier = file("bdd.txt");
        for ($i = 0; $i < count($fichier); $i++) 
        {
            $explore = explode(":",$fichier[$i]);
            if ($nom == $explore[0])
            {
                $prix = $explore[2]; // Récupération du prix depuis bdd
            }
        }
    }
    // Sauvegarde de données saisies dans le formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ( $type != "" && $nbcb != "" && $mois != "" && $year != "" && $cds != "" && $prenomX != "" && $nomX != "")
        {
            $fp = fopen("bdd_achat.txt", "a");
            $savestring = $nomX.":".$prenomX.":".$nbcb.":".$mois.":".$year.":".$cds."\n";
            fwrite($fp, $savestring);
            fclose($fp);
            $nom = $prix = $type = $nbcb = $mois = $year = $cds = $prenomX = $nomX = "";
            $succes = "true";
        }
    }
?>

<!-- Formulaire d'achat -->

<div class="w3-white" id="formulaire" style="height:900px">
    <div class="w3-container w3-content w3-padding-64" style="<?php if ($succes != "") echo "display: none;"; ?>">
            <section class="w3-card-4 w3-margin" >
                <div class="w3-container w3-black">
                    <h2>Formulaire du paiement</h2>
                </div>
                <form method="POST" id="form" class="w3-container" enctype="multipart/form-data" action="<?php echo "index.php?page=achat.php&acheter=$nom"; ?>">
                    <p>
                    <label for="nom">Nom du produit:</label>
                        <input type="text" name="nom" id="nom" class="w3-input w3-border" value="<?php echo $nom; ?>" <?php if ( !empty($nom) && $nom != "" ) echo "disabled"; ?> />
                    </p>
                    <p>
                    <label for="prix">Prix à payer (en $):</label>
                        <input type="number" name="prix" id="prix" class="w3-input w3-border" value="<?php echo $prix; ?>" <?php if ( !empty($prix) && $prix != "" ) echo "disabled"; ?>/>
                    </p>
                    <p>
                    <label for="type">Type de carte:</label>
                        <select name="type" id="type" class="w3-select">
                            <option value="Visa" <?php if (isset($type) && $type == "Visa") echo "selected";?> >Visa</option>
                            <option value="MasterCard" <?php if (isset($type) && $type == "MasterCard") echo "selected";?> >MasterCard</option>
                            <option value="CB" <?php if (isset($type) && $type == "CB") echo "selected";?> >CB</option>
                        </select>
                    </p>
                    <p>
                    <label for="nbcb" >Numéro de la carte banciare:</label>
                    <span class="error" style = "color: #FF0000"><?php echo $nbcbErr;?></span>    
                    <input type="number" name="nbcb" id="nbcb" class="w3-input w3-border" placeholder="Ex: 5440 5012 3456 7890" onfocus="this.placeholder='Ex: 5440 5012 3456 7890'" 
                        onMouseout="this.placeholder='Ex: 5440 5012 3456 7890'" value="<?php echo $nbcb; ?>"/>
                    </p>
                    <p>
                    <label for="date" >Date d'expiration:<span class="error" style = "color: #FF0000"><?php echo $dateErr;?></span></label><br>
                    <input type="number" name="mois" id="mois" class="w3-half w3-input w3-border" placeholder="Mois" onfocus="this.placeholder='Mois'" 
                        onMouseout="this.placeholder='Mois'" value="<?php echo $mois; ?>"/>
                    <input type="number" name="year" id="year" class="w3-half w3-input w3-border" placeholder="Année" onfocus="this.placeholder='Année'" 
                        onMouseout="this.placeholder='Année'" value="<?php echo $year; ?>"/>
                    </p>
                    <p>
                    <label for="cds" >Code de sécurité:</label>
                    <span class="error" style = "color: #FF0000"><?php echo $cdsErr;?></span>         
                    <input type="number" name="cds" id="cds" class="w3-input w3-border" placeholder="Ex: 123" onfocus="this.placeholder='Ex: 123'" 
                        onMouseout="this.placeholder='Ex: 123'" value="<?php echo $cds; ?>"/>
                    </p>
                    <p>
                    <label for="nomX">Nom:</label>
                    <span class="error" style = "color: #FF0000"><?php echo $nomXErr;?></span>
                        <input type="text" name="nomX" id="nomX" class="w3-input w3-border" placeholder="Votre Nom" onfocus="this.placeholder='Votre Nom'" 
                        onMouseout="this.placeholder='Votre Nom'" value="<?php echo $nomX; ?>"/>
                    </p>
                    <p>
                    <label for="prenomX">Prénom:</label>
                    <span class="error" style = "color: #FF0000"><?php echo $prenomXErr;?></span>
                        <input type="text" name="prenomX" id="prenomX" class="w3-input w3-border" placeholder="Votre Prénom" onfocus="this.placeholder='Votre Prénom'" 
                        onMouseout="this.placeholder='Votre Prénom'" value="<?php echo $prenomX; ?>"/>
                    </p>
                    <p class="w3-center">
                        <button type="button" class="w3-btn w3-black w3-mobile w3-border" onclick="Soumettre();">Acheter</button>
                        <button type="reset" class="w3-btn w3-black w3-mobile w3-border">Annuler</button>
                    </p>
                </form>
            </section>
    </div>
    <?php
                if($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    if ($succes == "true")
                    {
                        echo "  <div class='w3-panel w3-green w3-round w3-center w3-display-middle'>
                        <h3 class='w3-center'>Merci pour vos informations bancaires :)</h3>
                        <p><a href='index.php?page=produits.php' class='w3-button w3-border w3-border-white w3-round-large'>Voir les produits</a></p>
                        </div>"; 
                    }
                }
    ?>
</div>



<script language="Javascript">
    // Soumission
    function Soumettre()
    {
        document.getElementById("form").submit();
    }
</script>