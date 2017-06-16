<?php

$error = array();

if(empty($_POST['adresse']) || empty($_POST['destinataire']) || empty($_POST['message'])) {
    echo "Tous les champs ne sont pas remplis !";
    return false;
} else {
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

$adresse = htmlspecialchars($_POST['adresse']);
$destinataire = htmlspecialchars($_POST['destinataire']);
$message = htmlspecialchars($_POST['message']);

$newarchive = time().'-Archive.zip';
$newdossier = time();
mkdir('fichierEnvoi/'.$newdossier);

if ($_FILES['fichier']['error'] == 0){
  	move_uploaded_file($_FILES['fichier']['tmp_name'], './fichierEnvoi/'.$newdossier.'/'.$_FILES['fichier']['name']);
}

$zip = new ZipArchive();
if(is_dir('fichierEnvoi/')){
  if($zip->open('fichierEnvoi/'.$newdossier.'/Archive.zip', ZipArchive::CREATE) === true) {


     /* $infosadresse = pathinfo($adresse);
      $nameadresse = $infosadresse['filename'];
      rename ("/Archive.zip", '/'.$nameadresse.'Archive.zip');*/

      echo'fichierEnvoi/Archive.zip ouvert<br/>';

      $fichiers = scandir('fichierEnvoi/'.$newdossier);
      unset($fichiers[0], $fichiers[1]);
      foreach($fichiers as $f) {
          if(!$zip->addFile('fichierEnvoi/'.$newdossier.'/'.$f, $f)) {
            echo 'Impossible d"ajouter '.$f.'.<br/>';
          }
      }     
      $zip->close();
      echo 'fichierEnvoi/Archive.zip fermé<br/>';

      rename("fichierEnvoi/".$newdossier."/Archive.zip", 'fichierEnvoi/'.$newdossier.'/'.$newarchive);

  } else {
      echo "Impossible d'ouvrir fichierEnvoi.zip<br/>";
  }
}
unlink('./fichierEnvoi/'.$newdossier.'/'.$_FILES['fichier']['name']);

$liens = "http://vesoul.codeur.online/front/lgwendal/newwedili.MGC/fichierEnvoi/".$newarchive."Archive.zip";

if(!in_array(true, $error)) {
    mail($_POST['destinataire'], 'Vous avez des fichiers à télécharger', $_POST['message'], 'From: "'.$_POST['adresse']);    
    $error['sendEmail'] = false;
}
else{
    $error['sendEmail'] = true;
}

echo json_encode($_FILES);        
?>