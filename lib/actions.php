<?php
// Upload d'un fichier
if(isset($_GET['a2']) and $_GET['a2'] == 'upload' and isset($_POST) and isset($_FILES))
{
	// Valhalla Sequel : reformater le tableau des fichiers pour le rendre plus cohérent...
	foreach($_FILES['fichiers'] as $keys=>$values)
		foreach($values as $key=>$value)
			$FILE[$key][$keys] = $value;

	foreach($FILE as $F)
	{
		$props = Upload($F,$root);
		if($props) makeMeta($_POST, $props[0], $props[1]);
		else echo "Impossible d'enregistrer le fichier <b>".$F["name"]."</b>.<br />Vérifiez les droits d'écriture sur le dossier courant.<br />Vérifiez qu'un autre fichier n'existe pas déjà sous le même nom.<br /><br />";
	}
}


// Création d'un nouveau dossier
if(isset($_GET['a2']) and $_GET['a2'] == 'mkdir' and isset($_POST))
{
	$dir = $_POST['newdir'];

	// SÉCURITÉ : Enlever les accents du nom du fichier
	$dir = strtr($dir,
	    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
	    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$dir = preg_replace('/([^.a-z0-9]+)/i', '_', $dir);

	// Crée le répertoire
	// Pas besoin de chmod ici ? En principe non : on ne télécharge pas un répertoire entier...
	mkdir($_POST['parent']."/".$dir);
}

// Suppression d'un fichier
if(isset($_POST['a2']) and $_POST['a2'] == 'RM' and isset($_POST['file']) and isset($_POST['dir']) and isadmin())
{
	rmvFile( urldecode($_POST['file']) , urldecode($_POST['dir']) );
}

?>