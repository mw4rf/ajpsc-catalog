<?php

/* RÉPERTOIRE RACINE
Indiquez le chemin du répertoire racine du système de fichiers.
Tous les dossiers seront des sous-dossiers du répertoire racine, et tous les
fichiers se trouveront dans ce dossier ou dans l'un de ses sous-dossiers.
Le répertoire racine doit être accessible en lecture et en écriture (CHMOD 666 ou 777)
ATTENTION : il s'agit du chemin DANS LE SYSTÈME DE FICHIERS du serveur.
** NE PAS INCLURE DE SLASH (/) A LA FIN**
*/
$_config['root'] = './FILES';

/* RÉPERTOIRE DE TÉLÉCHARGEMENT
Indiquez l'adresse relative ou absolue du répertoire racine, qui sera utilisée pour télécharger les fichiers.
ATTENTION : il s'agit d'une adresse Web relative (/mon_dossier/...) ou absolue (http://www.mondomaine.com/mon_dossier/...)
** NE PAS INCLURE DE SLASH (/) A LA FIN**
*/
$_config['dl'] = "/FILES";

/* TAILLE MAXIMALE D'UPLOAD
Indiquez la taille maximale des fichiers que les utilisateurs pourront mettre en ligne.
Cette donnée est exprimée en OCTETS (BYTES).
*/
$_config['maxfilesize'] = 20000000; // 20 Mo

/* ATTRIBUTION DES DROITS
Attribue les droits indiqués aux fichiers lors de leur upload. Certains serveurs nécessitent des droits étendus pour permettre le téléchargement des fichiers.
Les droits son à écrire sur 4 chiffres, le premier chiffre étant obligatoirement un 0 (représentation octale) ; p. ex. 0777 pour les droits les plus étendus. Conseillé : 0664
*/
$_config['chmod'] = 0664;

/* ADMINISTRATION
Activez ou désactivez les fonctions d'administration. NB : seuls les administrateurs peuvent supprimer des fichiers.
- admin_activate : mettre à 1 pour activer les fonctions d'administration ou à 0 pour les désactiver (conseillé)
- admin_login : identifiant de l'administrateur
- admin_pass : mot de passe de l'administrateur
*/
$_config['admin_activate']	=	1;
$_config['admin_login']		=	'admin';
$_config['admin_pass']		=	'admin';

/* TYPE DES CHAMPS
Définissez ici autant de champs que nécessaire, en les numérotant de 0 à X (X-1 étant le nombre de champs désiré).
Assignez une lettre (ou une combinaison de lettres/chiffres) ** UNIQUE ** à chaque champ.
--
Certaines lettres sont spéciales :
- H : le hash md5 du fichier
- N : le nom du fichier
- S : la taille du fichier
*/
$_config['fields'][0] = 'H'; //hash md5
$_config['fields'][1] = 'N'; //nom
$_config['fields'][2] = 'S'; //taille
// Ne touchez pas aux champs ci-dessus - Changez les champs ci-dessous :
$_config['fields'][3] = 'T'; //thème,type, etc.
$_config['fields'][4] = 'C'; //commentaires
$_config['fields'][5] = 'D'; //date de prise de note

/* INTITULÉ DES CHAMPS
Reprenez ici les champs créés ci-dessus et définissez l'intitulé de chaque champ.
*/
$_config['fields-name'][0] = 'Hash'; //hash md5
$_config['fields-name'][1] = 'Nom'; //nom
$_config['fields-name'][2] = 'Taille'; //taille
$_config['fields-name'][3] = 'Type'; //thème,type,etc.
$_config['fields-name'][4] = 'Commentaires'; //commentaires
$_config['fields-name'][5] = 'Année'; //date de prise de note

/* CHAMPS AFFICHÉS
Définissez ici les champs qui doivent être affichés
*/
$_config['fields-display'] = array(1,2,5,3,4);

/* TAILLE DES CHAMPS LORS DE L'AFFICHAGE
Définissez la taille HORIZONTALE des champs. *** ATTENTION : Le total doit être égal à 100. ***
Le premier champ (button-size) correspond au bouton de téléchargement du fichier
*/
$_config['button-size']    = "2"; // le bouton de téléchargement
$_config['fields-display-size'][0] = "0";
$_config['fields-display-size'][1] = "38";
$_config['fields-display-size'][2] = "5";
$_config['fields-display-size'][3] = "10";
$_config['fields-display-size'][4] = "30";
$_config['fields-display-size'][5] = "15";

/* CHAMPS À REMPLIR LORS DE L'UPLOAD D'UN FICHIER
Définissez les champs requis lors de l'upload d'un fichier.
*/
$_config['fields-upload'] = array(3,5,4);

/* TAILLE DES CHAMPS LORS DE L'UPLOAD
Définissez la taille des champs du formulaire d'upload
*/
$_config['fields-upload-size'][0] = "0";
$_config['fields-upload-size'][1] = "40";
$_config['fields-upload-size'][2] = "30";
$_config['fields-upload-size'][3] = "30";
$_config['fields-upload-size'][4] = "40";
$_config['fields-upload-size'][5] = "20";

/* 	TYPE DE CHAMPS
Définissez le type de chaque champ. Les types suivants sont disponibles :
- "hidden"
- "text"
- "password"
- "textarea"
- "select"
- "checkbox"
*/
$_config['fields-upload-type'][0] = "hidden";
$_config['fields-upload-type'][1] = "text";
$_config['fields-upload-type'][2] = "text";
$_config['fields-upload-type'][3] = "select";
$_config['fields-upload-type'][4] = "textarea";
$_config['fields-upload-type'][5] = "select";

/* 	VALEURS DES CHAMPS DE TYPE select
Les champs de type SELECT permettent à l'utilisateur de choisir une valeur parmi plusieurs valeurs prédéfinies.
Les valeurs préféfinies sont indiquées ici.
*/
$_config['fields-options'][3] = array("Notes de cours","Sujet d'examen","Exercice corrigé","Autres");
$_config['fields-options'][5] = array(	"Année 1 Semestre 1",
										"Année 1 Semestre 2",
										"Année 2 Semestre 1",
										"Année 2 Semestre 2",
										"Année 3 Semestre 1",
										"Année 3 Semestre 2",
										"Année 4 Semestre 1",
										"Année 4 Semestre 2");

/* EXTENSIONS AUTORISÉES
Ce tableau contient la liste BLANCHE des extensions autorisées. Seuls les fichiers portant ces extensions pourront être mis en ligne.
Tous les autres fichiers seront considérés comme dangereux et ne seront pas enregistrés sur le serveur.
*/
$_config['extensions'] = array(	'.pdf',	// Adobe Acrobat PDF (Portable Document Format)
								'.txt',	// texte brut
								'.zip',	// archive zip
								'.gz',	// archive gzip (fonctionne aussi pour tar.gz)
								'.tar',	// archive tar
								'.rar',	// archive RAR
								'.rtf',	// Fichier Rich Text Format
								'.doc',	// Fichier MS Word
								'.dot',	// Modèle MS Word
								'.docx',// Fichier MS Word OpenXML
								'.dotx',// Modèle MS Word OpenXML
								'.xls',	// Fichier MS Excel
								'.xlt',	// Modèle MS Excel
								'.xlsx',// Fichier MS Excel OpenXML
								'.xltx',// Modèle MS Excel OpenXML
								'.pps',	// Fichier MS Powerpoint (Diaporama)
								'.ppt',	// Fichier MS Powerpoint (Présentation)
								'.pot',	// Modèle MS Powerpoint
								'.ppsx',// Fichier MS Powerpoint OpenXML (Diaporama)
								'.pptx',// Fichier MS Powerpoint OpenXML (Présentation)
								'.potx',// Modèle MS Powerpoint OpenXML
								'.htm',	// Fichier HTML
								'.html',// Fichier HTML
								'.odt',	// Fichier Oasis OpenDocument
								'.sxw',	// Fichier OpenOffice 1 (Traitement de texte)
								'.sxc',	// Fichier OpenOffice 1 (Tableur)
								'.csv',	// Coma Separated Values
								'.tsv',	// Tab Separated Values
								'.xml',	// Fichier XML
								'.jpg', // Image JPEG
								'.jpeg',// Image JPEG
								'.png',	// Image PNG
								'.bmp',	// Image BMP
								'.gif',	// Image GIF
								'.psd',	// Image Photoshop
								'.tiff',// Image TIFF
								'.tga',	// Image Targa
								'.eps',	// Fichier PostScript
								'.ps',	// Fichier PostScript
								'.tex',	// Fichier LaTeX
								'.mp3',	// Musique MP3
								'.aac',	// Musique AAC (MP4)
								'.ogg',	// Musique Ogg Vorbis
								'.avi',	// Vidéo (conteneur générique)
								'.mpg',	// Vidéo MPEG
								'.mpeg',// Vidéo MPEG
								'.mp4',	// Vidéo MPEG 4 (h264)
								'.wmv',	// Vidéo Windows Media Player
								'.asf'	// Vidéo Windows Media Player
								);
?>