<!DOCTYPE html>
<html lang="en">
<title>Apple</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {font-family: "Lato", sans-serif}
</style>
<body>

   <!-- Tableau des pages -->
   <?php
            $page = array ("init" => "init.php", "produits" => "produits.php", "formulaire" => "formulaire.php", "achat" => "achat.php");
   ?>


<!-- MENU -->
   <!-- MENU #1 -->
   <div class="w3-sidebar w3-bar-block w3-animate-left" style="display:none;z-index:5" id="mySidebar">
      <button class="w3-bar-item w3-button w3-xlarge" onclick="w3_close()">×</button>
      <a href="index.php?page=init.php" class="w3-bar-item w3-button">ACCUEIL</a>
      <a href="index.php?page=produits.php" class="w3-bar-item w3-button">PRODUITS</a>
      <a href="index.php?page=formulaire.php" class="w3-bar-item w3-button">FORMULAIRE</a>
   </div>
   <div class="w3-overlay w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>
   <div class= "w3-black">
      <button class="w3-button w3-black w3-xxlarge" onclick="w3_open()">&#9776;</button>
      <!-- MENU #2 -->
      <div class="w3-dropdown-hover">
         <button class="w3-button"><i class="fa fa-caret-down w3-xxlarge"></i></button>
         <div class="w3-dropdown-content w3-bar-block">
            <a href="index.php?page=init.php" class="w3-bar-item w3-button">ACCUEIL</a>
            <a href="index.php?page=produits.php" class="w3-bar-item w3-button">PRODUITS</a>
            <a href="index.php?page=formulaire.php" class="w3-bar-item w3-button">FORMULAIRE</a>
         </div>
      </div> 
   </div>


   <!-- Passage entre les pages -->
   <?php 
   if (isset($_GET['page'])){ 
      include($_GET['page']); 
   } else {    
      include('init.php'); 
   }
   ?>

   <!-- Pied de la page -->
   <footer class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge">
      <a href="https://www.facebook.com/apple"><i class="fa fa-facebook-official w3-hover-opacity"></i></a>
      <a href="https://www.instagram.com/apple/"><i class="fa fa-instagram w3-hover-opacity"></i></a>
      <a href="https://twitter.com/apple"><i class="fa fa-twitter w3-hover-opacity"></i></a>
      <p class="w3-medium">© YOURI NOVIKOV 2020</p>
   </footer>

</body>
</html>

<script>
// Ouvrir le menu #1
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}
// Fermer le menu #1
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
</script>