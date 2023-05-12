<?php
include("connexion.php");
if(isset($_GET['suprimer'])){
   $id = intval($_GET['suprimer']);
    $stmt = $conn->query("SELECT * FROM dossiers WHERE id ='$id'");
    $fichier = $stmt->fetch(PDO::FETCH_ASSOC);
   if(unlink($fichier['path'])){
        $suprimé = $conn->query("DELETE  FROM dossiers WHERE id ='$id'");
        if($suprimé){
            echo "<p>Fichier suprimé avec succès</p>"; 
        }
   }
    
}

//je verifie l'existence du ficheir
if(isset($_FILES['fichier'])){
   $fichier = $_FILES['fichier'];
   if($fichier["error"]){
    die(" Echec du téléchargement du fichier ");
   }
   //Je limite la taille du ficheir
   if($fichier["size"] > 2097152){
    die("Taille du fichier très grande!!Max: 2MB");
   }
   $dossier =  "fichiers/";
   $nomDuFichier = $fichier["name"];
   $nouveauNomDuFichier = uniqid();
   $extension = strtolower(pathinfo($nomDuFichier, PATHINFO_EXTENSION));
   //Je verifie le format du fichier
   if($extension != "jpg" && $extension != "png"){
    die("Format du ficheir non acceptable"); 
   }
   //$parfait = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
   $path = $dossier.$nouveauNomDuFichier.".".$extension;
   $parfait = move_uploaded_file($fichier["tmp_name"], $path);
   if($parfait){
    $sql = "INSERT INTO dossiers(nom, path) VALUES('$nomDuFichier', '$path')"; 
    $resultat = $conn->exec($sql);
    echo "<p>
            Fichier téléchargé avec succès! pour y accéder,
            <a
                target = \"__blank\"  
                href= \"fichiers/$nouveauNomDuFichier.$extension\"
            >Cliquez ici
            </a>
        </p>".$resultat;
    }else{
        echo "<p> Echec du téléchargement du fichier </p>"; 
    }
   
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form action ="" method ="POST" enctype = "multipart/form-data">
            <div>
               <p> <label for="">Select the file</label>
                <input name = "fichier" type = "file"></p>
            </div>
            <button name="upload" type = "submit">Send file</button>
        </form>
        <table border = "1"  cellpadding = "10">
            <thead>
                <th>Preview</th>
               <th>Fichier</th>
               <th>Date d'envoi</th>
               <th></th>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM dossiers");
               while($fichier = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>    
                <tr>
                    <td><img height = "50px" src="<?php echo $fichier['path'];?>" alt=""></td>
                    <td><a target = "_blank" href= "<?php echo $fichier['path'];?>"><?php echo $fichier['Nom'];?></a></td>
                    <td><?php echo date(" d/m/y H:i", strtotime($fichier['date_upload'])); ?></td>
                    <td><a href="index.php?suprimer=<?php echo $fichier['id'];?>">SUPRIMER</a></td>
                </tr> 
                <?php
                }
                ?>
            </tbody> 
        </table>    
        
    </body>
</html>