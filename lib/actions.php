<?php
// Upload d'un fichier
if(isset($_GET['a2']) and $_GET['a2'] == 'upload' and isset($_POST) and isset($_FILES))
{
	// Valhalla Sequel : reformater le tableau des fichiers pour le rendre plus coh�rent...
	foreach($_FILES['fichiers'] as $keys=>$values)
		foreach($values as $key=>$value)
			$FILE[$key][$keys] = $value;

	foreach($FILE as $F)
	{
		$props = Upload($F,$root);
		if($props) makeMeta($_POST, $props[0], $props[1]);
		else echo "Impossible d'enregistrer le fichier <b>".$F["name"]."</b>.<br />V�rifiez les droits d'�criture sur le dossier courant.<br />V�rifiez qu'un autre fichier n'existe pas d�j� sous le m�me nom.<br /><br />";
	}
}


// Cr�ation d'un nouveau dossier
if(isset($_GET['a2']) and $_GET['a2'] == 'mkdir' and isset($_POST))
{
	$dir = $_POST['newdir'];

	// S�CURIT� : Enlever les accents du nom du fichier
	$dir = strtr($dir,
	    '����������������������������������������������������',
	    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$dir = preg_replace('/([^.a-z0-9]+)/i', '_', $dir);

	// Cr�e le r�pertoire
	// Pas besoin de chmod ici ? En principe non : on ne t�l�charge pas un r�pertoire entier...
	mkdir($_POST['parent']."/".$dir);
}

// Suppression d'un fichier
if(isset($_POST['a2']) and $_POST['a2'] == 'RM' and isset($_POST['file']) and isset($_POST['dir']) and isadmin())
{
	rmvFile( urldecode($_POST['file']) , urldecode($_POST['dir']) );
}

?>