<?php
#####################################################################################################################################
// 0. 	INITIALISATION
$FILES	=	BrowseFiles($root); // argument à insérer ici
$DIRS	=	BrowseDirs($root); // idem
$indice = 1; // pour alterner la couleur des lignes

// Affichage du breadcrumb
if($root != config("root"))
{
	// Construction du breadcrumb
	$curfolder = urldecode($root);
	$curpath = explode('/',$curfolder);
	$breadcrumb  = "";
	foreach($curpath as $fold)
		// Protection contre les répertoires vides/racine/système
		if($fold == ' ' or $fold == '.')
			$breadcrumb .= "";
		// Le répertoire courant
		elseif($fold == CurrentDir($root))
			$breadcrumb .= "<b>$fold</b>";
		// Les répertoires parent du répertoire courant
		else
		{
			// Construction du chemin
			$parentpath = "";
			foreach($curpath as $parentfold)
				if($parentfold == $fold)
					{ $parentpath .= "/$parentfold"; break; }
				elseif($parentfold == "." and $_config['root']{0} == '.')
					$parentpath .= $parentfold;
				elseif($parentfold != ' ')
					$parentpath .= "/$parentfold";
			// Protection selon le répertoire root
			if($_config['root']{0} != '.')
				$parentpath = trim($parentpath,'.');
			if($_config['root']{0} != '/')
				$parentpath = trim($parentpath,'/');
			// Affichage du lien et du formulaire
			$breadcrumb .= "\n\t<form name=\"breadcrumb_".md5($parentpath)."\" method=\"post\" action=\"index.php?a=browse\">\n";
			$breadcrumb .= "\t\t<input type=\"hidden\" name=\"dir\" value=\"".urlencode($parentpath)."\" />\n";
			$breadcrumb .= "\t\t<a href=\"#\" onclick=\"javascript:soumettre('breadcrumb_".md5($parentpath)."');\">$fold</a> / \n";
			$breadcrumb .= "\t</form>\n";
		}

	// Affichage de la barre de navigation
	echo "<navbar>";
	echo "<form name=\"backform\" method=\"post\" action=\"index.php?a=browse\">";
	echo "<input type=\"hidden\" name=\"dir\" value=\"".urlencode(ParentDir($root))."\" />";
	echo "<b><a href=\"#\" onclick=\"javascript:soumettre('backform')\"><< Retour niveau sup&eacute;rieur</a></b>&nbsp;|&nbsp;";
	echo "</form>";

	echo "<a href=\"index.php?a=tree\">Arborescence</a>&nbsp;|&nbsp;";
	echo "<breadcrumb>";
	echo "Dossier actuel : <i>$breadcrumb</i>";
	echo "</breadcrumb>";
	echo "</navbar>";
	echo "<hr />";
}
?>

<?php
#####################################################################################################################################
// 1.	Affichage des DOSSIERS
if($DIRS){ // la fonction BrowseDirs renvoie false s'il n'y a rien à afficher ; on contrôle qu'il y a bien quelque chose à afficher
?>
<table class="list-table" cellpadding="0" cellspacing="0">
	<tr class="list-header"><td>Sous-dossiers</td></tr>
<?php
foreach($DIRS as $key=>$subArray)
	if(!$subArray["@T"]) // Détrermine si c'est un répertoire ou un fichier
	{
		// Affichage
		echo "<tr class=\"alt$indice\">\n";

		/*
		echo 	"\t<td class=\"list-cell\">"
				."<a href=\"index.php?a=browse&dir=".urlencode($subArray['@D']."/".$subArray['@F'])."\">"
				.$subArray["@F"]."</a>&nbsp;(".$subArray['@C'].")</td>\n";
		*/

		echo "\t<td class=\"list-cell\">";
		echo "<form name=\"browse_".md5($subArray['@F'])."\" method=\"post\" action=\"index.php?a=browse\">";
		echo "<input type=\"hidden\" name=\"dir\" value=\"".urlencode($subArray['@D']."/".$subArray['@F'])."\" />";
		echo "<img src=\"lib/folder.png\" alt=\"Folder\" />&nbsp;";
		echo "<a href=\"#\" onclick=\"javascript:soumettre('browse_".md5($subArray['@F'])."')\">"
				.$subArray['@F']."</a>&nbsp;(".$subArray['@C'].")";
		echo "</form>";
		echo "</td>";


		echo "<tr>\n";

		// Indice (couleurs alternatives)
		if($indice == 1) $indice++;
		elseif($indice == 2) $indice--;
	}
?>
</table>
<hr />
<?php }//endif ?>

<?php
#####################################################################################################################################
// 2.	Affichage des FICHIERS
if($FILES){ // la fonction BrowseFiles renvoie false s'il n'y a rien à afficher ; on contrôle qu'il y a bien quelque chose à afficher
?>
<table class="list-table" cellpadding="0" cellspacing="0">
	<tr class="list-header">
		<td><!--// download -->&nbsp;</td>
		<?php foreach($_config['fields-display'] as $key) echo "\t<td>".$_config['fields-name'][$key]."</td>\n"; ?>
		<?php if(isadmin()) { ?><td><!--// ADMIN ONLY -->Suppr.</td><?php } ?>
	</tr>
<?php
// Initialisation
$compteur = 0; // nombre de fichiers
$somme = 0; // taille totale des fichiers
// Parcours
foreach($FILES as $key=>$subArray)
{
	if($subArray["@T"])
	{
		// Construction de l'URL de téléchargement du fichier
		if($subArray['@D']{0} == '.') $path = substr($subArray['@D'],1); else $path = $subArray['@D'];
		$dlpath = $_config['dl'].'/'.$path."/".$subArray['@F'];

		// Affichage
		echo "<tr class=\"alt$indice\">\n";
		echo "\t<td class=\"list-cell\" width=\"".$_config["button-size"]."\">"
			."<a href=\"$dlpath\"><img src=\"lib/download.png\" alt=\"Download\"/></a></td>";
		foreach($_config['fields-display'] as $key)
		{
			// Champ spécial TAILLE
			if($_config['fields'][$key] == "S")
			{
				echo	"\t<td class=\"list-cell\" width=\"".$_config['fields-display-size'][$key]."%\">"
						.formater_taille($subArray[$_config['fields'][$key]])."</td>\n";
				// Calcul de la taille totale des fichiers
				if(is_numeric($subArray[$_config['fields'][$key]]))
					$somme += $subArray[$_config['fields'][$key]];
			}
			// Autres champs
			else
			echo 	"\t<td class=\"list-cell\" width=\"".$_config['fields-display-size'][$key]."%\">"
					.$subArray[$_config['fields'][$key]]."</td>\n";
		}

		// Supprimer le fichier -- ADMIN ONLY
		if(isadmin())
		{
			echo 	"\t<td>\n"
					."\t\t<form name=\"rm_".md5($subArray['@F'])."\" method=\"post\" action=\"index.php?a=browse\">\n"
					."\t\t\t<input type=\"hidden\" name=\"a2\" value=\"RM\" />\n"
					."\t\t\t<input type=\"hidden\" name=\"dir\" value=\"".urlencode($subArray['@D'])."\" />\n"
					."\t\t\t<input type=\"hidden\" name=\"file\" value=\"".urlencode($subArray['@F'])."\" />\n"
					."\t\t\t<a href=\"#\" onclick=\"javascript:soumettre('rm_".md5($subArray['@F'])."')\">Suppr.</a>\n"
					."\t\t</form>\n"
					."\t</td>\n";
			echo "<tr>\n";
		}

		// Indice (couleurs alternatives)
		if($indice == 1) $indice++;
		elseif($indice == 2) $indice--;

		// Incrémentation du compteur
		$compteur++;
	}
}
// Statistiques
echo "<tr>";
echo "<td class=\"stats\">&nbsp;</td>";
foreach($_config['fields-display'] as $key)
{
	// Total des tailles
	if($_config['fields'][$key] == "S")
		echo "<td class=\"stats\">".formater_taille($somme)."</td>";
	// Total du nombre de fichiers
	elseif($_config['fields'][$key] == "N")
		echo "<td class=\"stats\"><b>$compteur</b> fichiers dans ce dossier</td>";
	// Autres champs
	else
		echo "<td class=\"stats\">&nbsp;</td>";
}
echo "</tr>";
?>
</table>
<hr />
<?php
} // endif
?>
