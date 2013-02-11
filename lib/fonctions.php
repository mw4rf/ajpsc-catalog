<?php
/* Renvoie la valeur de configuration demand�e */
function config($arg,$pos=false)
{
	global $_config;

	if(!$pos and isset($_config[$arg]))
		return $_config[$arg];

	if(is_numeric($pos) and isset($_config[$arg][$pos]))
		return $_config[$arg][$pos];

	return false;
}

/*
Nom: isadmin (checkauth)
But: V�rifie si l'utilisateur est administrateur
Info: Guillaume Florimond, 9/12/2006 (Valhalla Sequel) ; 07/06/2008 (Valhalla Catalog)
*/
function isadmin()
{
	// Premier cas : les fonctions d'administration sont d�sactiv�es
	if(config('admin_activate') != 1)
	{
		return false;
	}

	// Deuxi�me cas : fonctions d'administration activ�es ; on contr�le que l'utilisateur est bien administrateur
	if(isset($_SESSION) and isset($_SESSION['ADMIN']) 		and  $_SESSION['ADMIN'] == 1
						and isset($_SESSION['ADMIN_LGN']) 	and  $_SESSION['ADMIN_LGN'] == md5( config('admin_login') )
						and isset($_SESSION['ADMIN_PWD']) 	and  $_SESSION['ADMIN_PWD'] == md5( config('admin_pass') ) )
	{
		return true;
	}

	return false;
}

/*
Nom : adminauth
But : Identifie un administrateur
Info : Guillaume Florimond, 07/06/2008
*/
function adminauth($login,$pass)
{
	if($login != md5(config('admin_login'))) return false;
	if($pass != md5(config('admin_pass'))) return false;

	$_SESSION['ADMIN'] = 1;
	$_SESSION['ADMIN_LGN'] = $login;
	$_SESSION['ADMIN_PWD'] = $pass;
	return true;
}

/*
Nom : ParentDir
But : renvoie le r�pertoire parent du r�pertoire pass� en argument
Info : Guillaume Florimond, 19/05/2008
*/
function ParentDir($dir)
{
	$dirs = explode("/", $dir);
	$num = count($dirs);
	$res = "";
	for($i = 0 ; $i < $num-1 ; $i++)
		$res .= $dirs[$i]."/";
	$res = trim($res,"/");
	return $res;
}

/*
Nom : CurrentDir
But : renvoie le r�pertoire courant � partir du chemin complet pass� en argument
Info : Guillaume Florimond, 19/05/2008
*/
function CurrentDir($dir)
{
	$dirs = explode("/", $dir);
	$num = count($dirs)-1;
	return $dirs[$num];
}

/*
Nom: Upload
But: Enregistre le fichier sp�cifi� � l'emplacement sp�cifi� du disque
Info: Guillaume Florimond, 07/09/2007 (Valhalla Contacts) ; modifi� le 19/05/2008 (Valhalla Catalog)
*/
function Upload($F,$destination=false)
{
	if(!$destination) $destination = config("root");
	//$F = $F["filename"];
	$fichier = basename($F["name"]);

	// S�CURIT� : Contr�ler la taille du fichier soumis
	if(filesize( $F['tmp_name']) > config('maxfilesize') )
		die("ERREUR : <b>Fichier trop volumineux</b>. R�duisez sa taille ou scindez-le en plusieurs fichiers.");

	// S�CURIT� : Contr�le des extensions
	$extension = strrchr($fichier, '.');
	if(!in_array($extension, config('extensions')))
	{
		echo "ERREUR : <b>Extension non autoris�e</b>. "
			."Seuls peuvent �tre mis en ligne les fichiers portant les extensions suivantes :<br />";
		foreach(config('extensions') as $ext)
			echo $ext.' ';
		die();
	}

	// S�CURIT� : Enlever les accents du nom du fichier
	$fichier = strtr($fichier,
	    '����������������������������������������������������',
	    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$fichier = preg_replace('/([^.a-z0-9]+)/i', '_', $fichier);

	// R�cup�rer les donn�es relatives au fichier
	$meta[0] = $fichier; // nom
	$meta[1] = filesize($F['tmp_name']); // taille
	$meta[2] = $destination; // dossier

	// v. 1.3b -- Chmod des fichiers pour permettre leur t�l�chargement
	chmod($F['tmp_name'],config('chmod'));

	// Ecrire le fichier sur le disque
	$fichier = $destination ."/". $fichier;
	if(is_readable($fichier)) return false; // le fichier existe d�j� !!!!
	$res = move_uploaded_file($F['tmp_name'], $fichier);

	return $meta;
}

/*
Nom: BrowseRecursive
But: Renvoie le contenu COMPLET d'un dossier du FTP sous forme de tableau, ou FALSE en cas d'erreur
Cette fonction r�cursive renvoie �galement le contenu des sous-dossiers.
Attention : renvoie aussi . (racine), .. (niveau sup.) et les fichiers cach�s !
Info: Guillaume Florimond, 25/08/2007 (Valhalla Sequel) ; modifi� le 21/05/2008 (Valhalla Catalog)
*/
function BrowseRecursive($chemin = "")
{
	// Appel sans param�tre
	if($chemin == "") $chemin = config('root');
	$contenu = "";
	// Traitement
	if ($handle = opendir($chemin))
	{
		/* Indice pour remplir le tableau */
		$i = 0;
		/* Ceci est la bonne mani�re pour parcourir un dossier. */
	    while (false !== ($fichier = readdir($handle)))
	    {
			// Le fichier est un r�pertoire : appel r�cursif de cette fonction
			// ATTENTION S�CURIT� !! CECI PEUT TUER LE SERVEUR !! (boucle perp�tuelle...)
			// Il faut TOUJOURS contr�ler qu'on n'essaie pas de partcourir ".." (niv. sup.) ou "." (racine)
			// NB : is_dir() ne fonctionne qu'avec des chemins COMPLETS (d'o� $chemin."/".$fichier)
			if(is_dir($chemin."/".$fichier) and $fichier != "." and $fichier != ".."
											and $fichier{0} != '.' and $fichier != 'dir.cfg')
			{
				$contenu[$i]['@T'] = false; // true = fichier ; false = dossier
				$contenu[$i]['@D'] = $chemin; // le chemin du dossier (chemin complet : chemin.'/'.fichier)
				$contenu[$i]['@F'] = $fichier;
				$contenu[$i]['@S'] = BrowseRecursive($chemin."/".$fichier);
			}
			// Le fichier n'est pas un r�pertoire
			elseif($fichier != '.' and $fichier != '..' and $fichier{0} != '.' and $fichier != 'dir.cfg')
			{
				$contenu[$i]['@T'] = true; // true = fichier ; false = dossier
				$contenu[$i]['@F'] = $fichier; // le nom du dossier
				$contenu[$i]['@D'] = $chemin; // le chemin du dossier ; pour le chemin complet : nom.'/'.chemin
			}

			// Incr�mentation
			$i++;
	    }
	   closedir($handle);
	   return $contenu;
	}
	return false;
}

/*
Nom: PrintTree
But: Imprime le contenu du dossier � l'�cran (le dossier doit �tre un tableau de fichiers)
Info : Utiliser la fonction BrowseRecursive pour g�n�rer l'argument : PrintTree(BrowseRecursive($PATH))
Info: Guillaume Florimond, 25/08/2007 (Valhalla Sequel) ; modifi� le 21/05/2008 (Valhalla Catalog)
*/
function PrintTree($tab)
{
	// Protection apr�s de l'appel imbriqu� de Browse (qui renvoie False)
	if(!$tab) return;
	$indice = 0; // alternance de la couleur des lignes

	echo "<ol class=\"tree\">\n";
	foreach($tab as $key=>$subArray)
	{
		// Si on a un dossier
		if(!$subArray['@T'])
		{
			echo "\t<li class=\"tree-folder\">";
			echo "<form name=\"tree_".md5($subArray['@F'])."\" method=\"post\" action=\"index.php?a=browse\">";
			echo "<input type=\"hidden\" name=\"dir\" value=\"".urlencode($subArray['@D']."/".$subArray['@F'])."\" />";
			echo 	"<a href=\"#\" onclick=\"javascript:soumettre('tree_".md5($subArray['@F'])."')\">"
					."<img src=\"lib/folder-small.png\" alt=\"Folder\" />&nbsp;"
					.$subArray['@F']."</a>";
			echo "</form>";
			echo "</li>\n";
			PrintTree($subArray['@S']);
		}
		// Si on a un fichier
		else
		{
			echo "\t<li class=\"tree-file\"><img src=\"lib/file-small.png\" alt=\"File\" />&nbsp;".$subArray['@F']."</li>\n";
		}
	}
	echo "</ol>\n";
}

/*
Nom: BrowseDirs
But: Renvoie les DOSSIERS contenus dans un dossier du FTP sous forme de tableau, ou FALSE en cas d'erreur
Cette fonction ne renvoie PAS le contenu des sous-dossiers, elle ne revoie QUE le nom des dossiers !
Retour : Tableau $tab tel que $tab[indice num�rique][cl�] ; o� cl� peut �tre :
- @T : true s'il s'agit d'un fichier, false s'il s'agit d'un dossier
- @F : le nom du dossier
- @D : le chemin du dossier (le chemin complet sera : nom.'/'.chemin)
- @C : le nombre de sous-dossiers et sous-fichiers contenus dans le dossier
Info: Guillaume Florimond, 19/05/2008 (Valhalla Catalog)
*/
function BrowseDirs($chemin = "")
{
	// Appel sans param�tre
	if($chemin == "") $chemin = config('root');

	// Traitement
	if ($handle = opendir($chemin))
	{
		/* Indice pour remplir le tableau */
		$i = 0;
		$contenu = "";
		/* Ceci est la bonne mani�re pour parcourir un dossier. */
	    while (false !== ($fichier = readdir($handle)))
	    {
			// Le fichier est un r�pertoire : appel r�cursif de cette fonction
			// ATTENTION S�CURIT� !! CECI PEUT TUER LE SERVEUR !! (boucle perp�tuelle...)
			// Il faut TOUJOURS contr�ler qu'on n'essaie pas de partcourir ".." (niv. sup.) ou "." (racine)
			// NB : is_dir() ne fonctionne qu'avec des chemins COMPLETS (d'o� $chemin."/".$fichier)
			if($fichier != "." and $fichier != ".." and is_dir($chemin."/".$fichier) and $fichier{0} != '.')
			{
				// Donn�es relatives au dossier
				$contenu[$i]['@T'] = false; // true = fichier ; false = dossier
				$contenu[$i]['@F'] = $fichier; // le nom du dossier
				$contenu[$i]['@D'] = $chemin; // le chemin du dossier ; pour le chemin complet : nom.'/'.chemin
				// Nombre d'�l�ments (fichiers+dossiers) contenus dans ce dossier
				$compteur = 0;
				if($subhandle = opendir($chemin.'/'.$fichier))
					while(false !== ($sf = readdir($subhandle)))
						if($sf != "." and $sf != ".." and $sf{0} != '.' and $sf != "dir.cfg")
							$compteur++;
				$contenu[$i]['@C'] = $compteur;
				// Incr�mentation
				$i++;
			}
	    }
	   closedir($handle);
	   return $contenu;
	}
	return false;
}

/*
Nom: BrowseFiles
But: Renvoie le contenu d'un dossier du FTP sous forme de tableau, ou FALSE en cas d'erreur
Cette fonction ne renvoie PAS le contenu des sous-dossiers, elle ne revoie PAS les dossiers !
Retour : Tableau $tab tel que $tab[indice num�rique][cl�] ; o� cl� peut �tre :
- @T : true s'il s'agit d'un fichier, false s'il s'agit d'un dossier
- @F : le nom du dossier
- @D : le chemin du dossier (le chemin complet sera : nom.'/'.chemin)
Info: Guillaume Florimond, 27/08/2007 (Valhalla Sequel) ; modifi�e : 18/05/2008 (Valhalla Catalog)
*/
function BrowseFiles($chemin = "",$showdirs=false,$nometa=false)
{
	// Appel sans param�tre
	if($chemin == "") $chemin = config('root');

	// Mise � jour �ventuelle des m�ta-donn�es
	refreshMeta($chemin);

	// Lecture des m�ta-donn�es
	$metas = readMeta($chemin);

	// Traitement
	if ($handle = opendir($chemin))
	{
		/* Indice pour remplir le tableau */
		$i = 0;
		$contenu = "";
		/* Ceci est la bonne mani�re pour parcourir un dossier. */
	    while (false !== ($fichier = readdir($handle)))
	    {
			// Le fichier est un r�pertoire : appel r�cursif de cette fonction
			// ATTENTION S�CURIT� !! CECI PEUT TUER LE SERVEUR !! (boucle perp�tuelle...)
			// Il faut TOUJOURS contr�ler qu'on n'essaie pas de partcourir ".." (niv. sup.) ou "." (racine)
			// NB : is_dir() ne fonctionne qu'avec des chemins COMPLETS (d'o� $chemin."/".$fichier)
			// NB2 : le fichier dir.cfg stocke les m�ta-donn�es des autres fichiers ; il ne doit pas �tre affich�
			if($fichier != "." and $fichier != ".." and $fichier != "dir.cfg"
				and $fichier{0} != '.' and !is_dir($chemin."/".$fichier))
			{
				if(!is_dir($chemin."/".$fichier))
				{
					$contenu[$i]['@T'] = true;
					$contenu[$i]['@F'] = $fichier;
					$contenu[$i]['@D'] = $chemin;

					if(!$nometa)
					{
						// D�termination de l'indice (quelle ligne correspond � ce fichier ?) -- Correction de bug 07/06/2008
							$ligne = 0; $compteur = 0;
							// R�cup�rer la ligne qui correspond � ce hash
							$idx = fopen($chemin.'/dir.cfg',"r"); // ouvrir le fichier
							while (!feof ($idx))
							{
								// Est-ce la bonne ligne ?
								$data = fgets($idx, 4096);
								if(strpos($data,$fichier) !== false)
                                    { $ligne = $compteur; break; }
								// Incr�mentation du compteur
								$compteur++;
							}
							fclose($idx);

						// Assignation des m�ta-donn�es du fichier (enregistr�es dans dir.cfg)
						global $_config;
						foreach($_config['fields'] as $field)
							if(isset($metas[$ligne][$field]))
								$contenu[$i][$field] = $metas[$ligne][$field];
							else
								$contenu[$i][$field] = "";
					}
				}

			// Incr�mentation
			$i++;
			}
	    }

		// DEBUG
		//for($z = 0 ; $z < count($contenu) ; $z++)
		//	echo $contenu[$z]['@F']." => ".$metas[$z]['N']."<br />";

	   	closedir($handle);
	   	if($contenu == "" or count($contenu) < 1) return false; // dossier vide, on retourne false
		return $contenu; // dossier plein ; on retourne son contenu
	}
	return false; // echec : on retourne false
}

/*
Nom: makeMeta
But : cr�e les m�tadonn�es du fichiers qui vient d'�tre mis en ligne par UPLOAD
Info : Guillaume Florimond, 19/05/2008
Arguments : $meta est le r�sultat du formulaire POST ; $nom est renvoy� par la fonction Upload
*/
function makeMeta($metas,$nom,$taille)
{
	global $_config, $root;

	// Cr�ation de la ligne
	if(is_readable($root.'/dir.cfg') and file_get_contents($root.'/dir.cfg') !== false and file_get_contents($root.'/dir.cfg') != '')
		$ln = "\n";
	else
		$ln = "";
	foreach($_config['fields'] as $field)
	{
		// Premi�re ligne ?
		if(array_search($field,$_config['fields']) == 0)
			$sep = "";
		else
			$sep = "\t";
		// Remplissage
		if($field == 'H') // hash
			$ln .= $sep.md5($nom);
		elseif($field == 'N') // nom
			$ln .= $sep.$nom;
		elseif($field == 'S') // taille
			$ln .= $sep.$taille;
		elseif(isset($metas[$field])) // autres champs
			$ln .= "\t".urldecode($metas[$field]);
	}

	// Enregistrement de la ligne dans le fichier
	//if(!is_writable("$root/dir.cfg")) die ("Fichier dir.cfg non accessible en �criture (chmod 666 ou 777) !");

	$fichier = fopen("$root/dir.cfg","a+");
	fwrite($fichier,$ln);    // �criture fichier
	fclose($fichier);			// fermeture fichier

}

/*
Nom: readMeta
But : initialise la liste des fichiers du r�pertoire courant et renvoie leurs m�ta donn�es
Info : Guillaume Florimond, 8/05/2008 (Valhalla Sequel) ; modifi� le 8/05/2008 (Valhalla Catalog)
--
Renvoie false en cas d'�chec.
*/
function readMeta($dir)
{
	$meta = false;

	// V�rifie que le fichier est accessible en lecture
	if(!is_readable($dir.'/dir.cfg')) remakeMeta($dir); //die ("M�ta-donn�es illisibles");
	// S'il ne l'est toujours pas, on sort
	if(!is_readable($dir.'/dir.cfg')) return;

	// Lecture du fichier
	$fichier = $dir.'/dir.cfg'; // ouverture du fichier
	$lignes = explode("\n", file_get_contents($fichier));

	// Traitement de chaque ligne
	$i = 0;
	if(count($lignes) < 1) return; // protection en cas de dossier vide
	foreach($lignes as $ligne)
	{
		// Si la ligne est vide, on sort
		if($ligne == "") break;
		// Sinon, on continue
		$ar = explode("\t" , $ligne);  // s�parateur : tabulation
		global $_config; $y = 0; // les champs sont d�finis dans config.php
		foreach($_config['fields'] as $field)
		{	$meta[$i][$field] = stripslashes($ar[$y]);	$y++;	}
		$i++;
	}
	return $meta;
}

/*
Nom: remakeMeta
But : cr�e un nouveau fichier de m�tadonn�es � partir des fichiers pr�sents dans le dossier.
Cette fonction est � appeler sur un dossier contenant des fichiers mais pas le fichier d'index dir.cfg
ATTENTION : cette fonction ne PEUT PAS conna�tre toutes les m�ta-donn�es du fichier ; elle ne peut r�g�n�rer que les m�ta-donn�es
contenues dans le fichier lui m�me : nom, type, taille...
Info : Guillaume Florimond, 8/05/2008 (Valhalla Sequel) ; modifi� le 8/05/2008 (Valhalla Catalog)
*/
function remakeMeta($dir)
{
	// Si le fichier meta existe d�j�, on sort
	if(is_readable($dir.'/dir.cfg')) die ("Le fichier dir.cfg existe d�j� dans ce r�pertoire ! Veuillez le supprimer avant de tenter de le r�g�n�rer.");

	// Initialisation
	global $_config;
	$lignes = array(); // les lignes � ins�rer (un fichier repr�sente une ligne)
	$num = count($_config['fields']); // nombre de sections � ins�rer � chaqye ligne
	$FILES = ""; // tableau qui stockera les fichiers du dossier
	$i = 0; // indice

	// Parcourt le contenu du dossier
	if($handle = opendir($dir))
		while (false !== ($fichier = readdir($handle)))
			if($fichier != "." and $fichier != ".." and $fichier != "dir.cfg" and $fichier{0} != '.' and !is_dir($dir.'/'.$fichier))
			{	$FILES[$i] = $fichier; $i++;	}

	// Cr�er les lignes � ins�rer dans le fichier
	if(count($FILES) < 1 or $FILES == "") return;// protection en cas de dossier vide
	foreach($FILES as $nom) // ex�cut�e autant de fois qu'il y a de fichier ; 1 passage = 1 ligne
	{
		// Cr�er la ligne
		$ln = "";
		foreach($_config['fields'] as $field)
		{
			// Premi�re ligne ?
			if(array_search($field,$_config['fields']) == 0)
				$sep = "";
			else
				$sep = "\t";
			// Contenu des champs
			if($field == 'H') // hash
				$ln .= $sep.md5($nom);
			elseif($field == 'N') // nom
				$ln .= $sep.$nom;
			elseif($field == 'S') // taille
				$ln .= $sep.filesize($dir.'/'.$nom);
			else
				$ln .= $sep."?"; // tous les autres champs
		}
		// Ins�rer la ligne
		array_push( $lignes , $ln );
	}

	// Ajouter des sauts de ligne � la fin de toutes les lignes, sauf la derni�re
	for($i = 0 ; $i < count($lignes)-1 ; $i++)
		$lignes[$i] .= "\n";

	/* NB : le mode a+ est le mode "append" alors que le mode w+ efface et remplace le contenu du fichier */
	$fichier = fopen($dir."/dir.cfg","a+");
	if(count($lignes) < 1) return; // protection en cas de dossier vide
	foreach($lignes as $ligne)
		fwrite($fichier,$ligne);    // �criture fichier
	fclose($fichier);			// fermeture fichier

	return true;
}

/*
Nom: refreshMeta
But : met � jour le fichier de m�ta-donn�es � partir des fichiers pr�sents dans le dossier
Fonction appel�e � chaque chargement d'un dossier (v. la fonction BrowseFiles)
ATTENTION : cette fonction ne PEUT PAS conna�tre toutes les m�ta-donn�es du fichier ; elle ne peut r�g�n�rer que les m�ta-donn�es
contenues dans le fichier lui m�me : nom, type, taille...
Info : Guillaume Florimond, 20/05/2008 (Valhalla Catalog)
*/
function refreshMeta($dir)
{
	// Si le fichier config.cfg n'existe pas, c'est la fonction remakeMeta qu'il faut appeler
	if(!is_readable($dir.'/dir.cfg')) remakeMeta($dir);
	// S'il n'existe toujours pas, on sort
	if(!is_readable($dir.'/dir.cfg')) return;

	// Initialisation
	global $_config;
	$FILES = "";
	$hash = "";
	$i = 0;

	// Ouvre le fichier d'index du dossier, dir.cfg
	$fichier = $dir."/dir.cfg";
	$lignes = explode("\n", file_get_contents($fichier));

	// R�cup�re les hash des fichiers dans l'index
	$y = 0;
	if(count($lignes) < 1) return; // protection en cas de dossier vide
	foreach($lignes as $ligne)
	{
		// Si la ligne est vide, on sort
		if($ligne == "") break;
		// R�cup�re le hash md5 du fichier
		$data = explode("\t", $ligne);
		$hash[$y] = $data[0];
		$y++;
	}

	// Parcourt le dossier et rajoute les fichiers absents de l'index dir.cfg
	if($handle = opendir($dir))
		while (false !== ($fichier = readdir($handle)))
			if($fichier != "." and $fichier != ".." and $fichier != "dir.cfg" and $fichier{0} != '.' and !is_dir($dir.'/'.$fichier))
			{
				// Initialisation des donn�es du fichier
				$FILES['F'][$i] = $fichier; // le fichier
				$FILES['H'][$i] = md5($fichier); // le hash md5 du fichier

				// Le fichier est-il pr�sent dans l'index ?
				// NB : depuis php 4.2.0, le premier argument peut �tre un tableau
				if(is_array($hash))
					if(!in_array($FILES['H'][$i],$hash)) // R�ponse : NON
						addMeta($fichier,$dir); // on ajoute une ligne dans l'index correspondant � ce fichier
				// Incr�mentation
				$i++;
			}

	// Parcourt les donn�es du fichier dir.cfg, efface les entr�es des fichiers absents
	if(count($lignes) < 1) return; // protection en cas de dossier vide
	foreach($lignes as $ligne)
	{
		// Si la ligne est vide, on sort
		if($ligne == "") break;
		// R�cup�re le hash md5 du fichier
		$data = explode("\t", $ligne);
		$hash = $data[0];
		$nom = $data[array_search('N',$_config['fields'])];
		// Recherche ce hash parmi les fichiers r�ellement pr�sents dans le dossier [array_search($needle,$haystack)]
		$key = array_search($hash,$FILES['H']); // renvoie un indice en cas de succ�s et FALSE en cas d'�chec
		if($key === FALSE) // si $key est FALSE c'est que le hash n'a pas �t� trouv� ; le fichier n'est plus pr�sent sur le disque
			if(!is_readable($dir.'/'.$nom))
				rmvMeta($ligne,$dir); // param : la ligne � effacer
	}
}

/*
Nom: addMeta
But : ajoute une ligne dans le fichier de m�tadonn�es
Param : le fichier � ajouter (voir fonction refreshMeta) / le r�pertoire dans lequel se trouve le fichier
Info : Guillaume Florimond, 20/05/2008 (Valhalla Catalog)
*/
function addMeta($file,$dir)
{
	// Si le fichier config.cfg n'existe pas, c'est la fonction remakeMeta qu'il faut appeler
	if(!is_readable($dir.'/dir.cfg')) remakeMeta($dir);

	// Initialisation
	global $_config;

	// Cr�er la ligne
	$ln = "\n";
	foreach($_config['fields'] as $field)
	{
		// Premi�re ligne ?
		if(array_search($field,$_config['fields']) == 0)
			$sep = "";
		else
			$sep = "\t";
		// Contenu des champs
		if($field == 'H') // hash
			$ln .= $sep.md5($file);
		elseif($field == 'N') // nom
			$ln .= $sep.$file;
		elseif($field == 'S') // taille
			$ln .= $sep.filesize($dir.'/'.$file);
		else
			$ln .= $sep."?"; // tous les autres champs
	}

	/* NB : le mode a+ est le mode "append" alors que le mode w+ efface et remplace le contenu du fichier */
	$fichier = fopen($dir."/dir.cfg","a+");
	fwrite($fichier,$ln);    // �criture fichier
	fclose($fichier);
}

/*
Nom: rmvMeta
But : efface une ligne dans le fichier de m�tadonn�es / le r�pertoire dans lequel se trouve le fichier
Param : la ligne � effacer (voir fonction refreshMeta)
Info : Guillaume Florimond, 20/05/2008 (Valhalla Catalog)
*/
function rmvMeta($line,$dir)
{
	// Si le fichier config.cfg n'existe pas, c'est la fonction remakeMeta qu'il faut appeler
	if(!is_readable($dir.'/dir.cfg')) remakeMeta($dir);

	// Le fichier d'index
	$fichier = fopen($dir.'/dir.cfg',"r"); // ouvrir le fichier
	$result = "";

	while (!feof ($fichier))          // parcourir le fichier
	{
		$data = fgets($fichier, 4096);
		if(strpos($data,$line) !== false)
            { $data = ""; } // si la ligne est pr�sente, on la remplace par du vide
		if($data != "")				$result .= $data; // on ajoute une ligne pleine ou une ligne vide qui r�sulte du if pr�c�dent
	}

	fclose($fichier);		// fermeture du flux de lecture

	// Ecriture du contenu dans le fichier
	$result = trim(rtrim($result));
	$fichier = fopen($dir.'/dir.cfg',"w+");
	fwrite($fichier,$result);
	fclose($fichier);
}

/*
Nom : rmvFile
But : Supprime un fichier et les m�ta-donn�es qui lui sont associ�es
Info : Guillaume Florimond, 07/06/2008
*/
function rmvFile($filename,$dir)
{
	// 0. Contr�ler qu'on est bien administrateur ; sinon, sortir

	//echo "unlinker : $dir/$filename";

	// 1. Effacer le fichier
	unlink($dir.'/'.$filename);

	// 2. Effacer les m�ta-donn�es associ�es au fichier
	rmvMeta($filename,$dir);
}


/*
Nom : formater_taille
But : formate une taille exprim�e en Octets en GO, MO, KO ou Octets.
Info : Guillaume Florimond, 13/03/2008 (Valhalla Sequel)
*/
function formater_taille($taille, $round=false)
{
	if(!is_numeric($taille)) return "";
	if(!$round) $round = "1";

	// Formater les tailles
	// Go
	if($taille > 1073741824)
	{
		$taille = round($taille / 1073741824 , $round);
		$taille .= " Gb";
	}
	// Mo
	elseif($taille > 1048576)
	{
		$taille = round($taille / 1048576 , $round);
		$taille .= " Mb";
	}
	// Ko
	elseif($taille > 1024)
	{
		$taille = round($taille / 1024 , $round);
		$taille .= " Kb";
	}
	// Octets
	else
		$taille .= " B";

	return $taille;
}


?>