<?php

$error = array();
// cette condition verifie les inputs vides :
if(empty($_POST['adresse']) || empty($_POST['destinataire'])) {
    echo "Tous les champs ne sont pas remplis !";
    return false;
} else {
		// cette condition verifie les adresse email :
	    if(filter_var($_POST['adresse'], FILTER_VALIDATE_EMAIL)){
	       	$error['adresse'] = false;
	    } else {
	        echo "Votre adresse est fausse !";
	    }
	    if(filter_var($_POST['destinataire'], FILTER_VALIDATE_EMAIL)){
	        $error['destinataire'] = false;
	    } else {
	        echo "L'adresse du destinataire est fausse !";
	    }
}

//variables pour chaques input :
$adresse = htmlspecialchars($_POST['adresse']);
$destinataire = htmlspecialchars($_POST['destinataire']);
$message = htmlspecialchars($_POST['message']);

//variable pour dossier et archive avec nom unique :
$newarchive = time().'-Archive.zip';
$newdossier = time();
//creation dossier pour contenir fichiers et archives :
mkdir('fichierEnvoi/'.$newdossier);

//	condition pour stocker le fichier dans le bon dossier :
// if ($_FILES['fichier']['error'] == 0){
//   	move_uploaded_file($_FILES['fichier']['tmp_name'], './fichierEnvoi/'.$newdossier.'/'.$_FILES['fichier']['name']);
// }

// Boucle pour stocker tous les fichiers
for($i=0; $i<count($_FILES["fichier"]["tmp_name"]);$i++){
    $tmp_name = $_FILES["fichier"]["tmp_name"][$i];
    $name = $_FILES["fichier"]["name"][$i];
    move_uploaded_file($tmp_name, './fichierEnvoi/'.$newdossier.'/'.$name);
}

//création d'une archive :
$zip = new ZipArchive();

// On teste si le dossier existe :
if(is_dir('fichierEnvoi/')){
	// Ouverture et création de l’archive :
  	if($zip->open('fichierEnvoi/'.$newdossier.'/Archive.zip', ZipArchive::CREATE) === true) {

      	echo'fichierEnvoi/Archive.zip ouvert<br/>';

      	// Récupération des fichiers :
      	$fichiers = scandir('fichierEnvoi/'.$newdossier);
      	// On enlève . et .. qui représentent le dossier courant et le dossier parent :
      	unset($fichiers[0], $fichiers[1]);
      	// On ajoute chaque fichier à l’archive :
      	foreach($fichiers as $f) {
          	if(!$zip->addFile('fichierEnvoi/'.$newdossier.'/'.$f, $f)) {
            	echo 'Impossible d"ajouter '.$f.'.<br/>';
          	}
      	}
      	// On ferme l’archive :
      	$zip->close();
      	echo 'fichierEnvoi/Archive.zip fermé<br/>';

      	//On renomme bien l'archive avec un nom unique :
      /*	rename("fichierEnvoi/".$newdossier."/Archive.zip", 'fichierEnvoi/'.$newdossier.'/'.$newarchive);*/

  	} else {
      	echo "Impossible d'ouvrir fichierEnvoi.zip<br/>";
  	}
}
//On supprime les fichiers pas archivés :
/*unlink('./fichierEnvoi/'.$newdossier.'/'.$_FILES['fichier']['name']);*/


$liens = "http://vesoul.codeur.online/front/lgwendal/newwedili.MGC/fichierEnvoi/".$newdossier."/Archive.zip";

//On envoie l'email et les fichiers :
if(!in_array(true, $error)) {
    mail($_POST['destinataire'], 'Vous avez des fichiers à télécharger', $_POST['message'] . "<a href='".$liens."'>mon super lien</a>", 'From: "'.$_POST['adresse']);    
    $error['sendEmail'] = false;
}
else{
    $error['sendEmail'] = true;
}

echo json_encode($_FILES);        
?>