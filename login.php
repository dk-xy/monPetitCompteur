<?php
namespace ch\comem;

require_once 'Database.php';
use ch\comem\Database as Database;


ini_set('display_errors', 1);

//initiation session + connexion à la bdd
session_start();  
try  
{ 
     $connect = new Database;

     //connexion à la page
     if(isset($_POST["connexion"]))  
     {  
          if(empty($_POST["login"]) || empty($_POST["motDePasse"]))  
          {  
               $message = '<label>Veuillez remplire tous les champs</label>';  
          }  
          else  
          {
               $userExists = $connect->utilisateurExiste($_POST['login'], $_POST['motDePasse']);
               $tabInfoUser = $userExists;
               if($userExists) {
                     $_SESSION["login"] = $_POST["login"];
                     $_SESSION["id"] = $tabInfoUser[0]; 
                     header("location:dashboard.php");      
               }
               else {  
                    $message = '<div class="alerteConnexion">Les données entrées ne sont pas valide</div>';  
               } 
          } 
     }
}  
 catch(PDOException $error)  
 {  
      $message = $error->getMessage();  
 }  
 ?>  
 <!--Formulaire de login---------------------------------------------------------------------------------------------->
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Compteur de boisson</title> 
           <link rel="stylesheet" href="css/Style.css"> 
           <link rel="stylesheet" href="css/loginStyle.css">
      </head>  
      <body>   
           <div class="container" style="width:500px;">
            <img class ="logo" src="img/logoCompteur.png" alt="Logo Mon Petit Compteur" width="300"> 
            <a href="inscription.php">Inscription</a> | <a href="dashboard.php">Déjà membre?</a> 
                <?php  
                if(isset($_SESSION['userInscrit'])){
                    if($_SESSION['userInscrit']){
                         echo '<div class="alerteConnexion">Inscription réussie ! Veuillez entrer vos identifiants pour vous connecter</div>';
                         $_SESSION['userInscrit'] = false;
                    }
               }

                if(isset($message))  
                {  
                     echo '<label class="text-danger">'.$message.'</label>';  
                }  
                ?>  
                <h3 align="">Bienvenue, veuillez vous connecter</h3><br />  
                <form method="post" class="formulaire">    
                     <input placeholder="Nom d'utilisateur" type="text" name="login" class="form-control" />  
                     <br />  
                     <input placeholder="Mot de pass" type="password" name="motDePasse" class="form-control" />  
                     <br />  
                     <input type="submit" name="connexion" class="btn btn-info" value="Connexion" />  
                </form>  
           </div>  
           <br />  
      </body> 
      <footer>
      	<svg height="100%" width="100%" id="svg" viewBox="0 0 1440 400" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150"><path d="M 0,400 C 0,400 0,133 0,133 C 73.11961722488039,101.38277511961724 146.23923444976077,69.76555023923446 236,86 C 325.7607655502392,102.23444976076554 432.16267942583715,166.3205741626794 544,172 C 655.8373205741628,177.6794258373206 773.1100478468901,124.95215311004785 885,123 C 996.8899521531099,121.04784688995215 1103.3971291866028,169.87081339712918 1195,180 C 1286.6028708133972,190.12918660287082 1363.3014354066986,161.56459330143542 1440,133 C 1440,133 1440,400 1440,400 Z" stroke="none" stroke-width="0" fill="#00d4f088" class="transition-all duration-300 ease-in-out delay-150"></path><path d="M 0,400 C 0,400 0,266 0,266 C 115.70334928229664,253.2918660287081 231.40669856459328,240.58373205741626 336,233 C 440.5933014354067,225.41626794258374 534.0765550239234,222.95693779904306 610,220 C 685.9234449760766,217.04306220095694 744.2870813397129,213.58851674641147 833,233 C 921.7129186602871,252.41148325358853 1040.775119617225,294.68899521531097 1147,304 C 1253.224880382775,313.31100478468903 1346.6124401913876,289.6555023923445 1440,266 C 1440,266 1440,400 1440,400 Z" stroke="none" stroke-width="0" fill="#00d4f0ff" class="transition-all duration-300 ease-in-out delay-150"></path></svg>
      </footer> 
</html>  