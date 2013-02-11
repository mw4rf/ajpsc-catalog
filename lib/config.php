<?php

/* R�PERTOIRE RACINE
Indiquez le chemin du r�pertoire racine du syst�me de fichiers.
Tous les dossiers seront des sous-dossiers du r�pertoire racine, et tous les
fichiers se trouveront dans ce dossier ou dans l'un de ses sous-dossiers.
Le r�pertoire racine doit �tre accessible en lecture et en �criture (CHMOD 666 ou 777)
ATTENTION : il s'agit du chemin DANS LE SYST�ME DE FICHIERS du serveur.
** NE PAS INCLURE DE SLASH (/) A LA FIN**
*/
$_config['root'] = './FILES';

/* R�PERTOIRE DE T�L�CHARGEMENT
Indiquez l'adresse relative ou absolue du r�pertoire racine, qui sera utilis�e pour t�l�charger les fichiers.
ATTENTION : il s'agit d'une adresse Web relative (/mon_dossier/...) ou absolue (http://www.mondomaine.com/mon_dossier/...)
** NE PAS INCLURE DE SLASH (/) A LA FIN**
*/
$_config['dl'] = "/FILES";

/* TAILLE MAXIMALE D'UPLOAD
Indiquez la taille maximale des fichiers que les utilisateurs pourront mettre en ligne.
Cette donn�e est exprim�e en OCTETS (BYTES).
*/
$_config['maxfilesize'] = 20000000; // 20 Mo

/* ATTRIBUTION DES DROITS
Attribue les droits indiqu�s aux fichiers lors de leur upload. Certains serveurs n�cessitent des droits �tendus pour permettre le t�l�chargement des fichiers.
Les droits son � �crire sur 4 chiffres, le premier chiffre �tant obligatoirement un 0 (repr�sentation octale) ; p. ex. 0777 pour les droits les plus �tendus. Conseill� : 0664
*/
$_config['chmod'] = 0664;

/* ADMINISTRATION
Activez ou d�sactivez les fonctions d'administration. NB : seuls les administrateurs peuvent supprimer des fichiers.
- admin_activate : mettre � 1 pour activer les fonctions d'administration ou � 0 pour les d�sactiver (conseill�)
- admin_login : identifiant de l'administrateur
- admin_pass : mot de passe de l'administrateur
*/
$_config['admin_activate']	=	1;
$_config['admin_login']		=	'admin';
$_config['admin_pass']		=	'admin';

/* TYPE DES CHAMPS
D�finissez ici autant de champs que n�cessaire, en les num�rotant de 0 � X (X-1 �tant le nombre de champs d�sir�).
Assignez une lettre (ou une combinaison de lettres/chiffres) ** UNIQUE ** � chaque champ.
--
Certaines lettres sont sp�ciales :
- H : le hash md5 du fichier
- N : le nom du fichier
- S : la taille du fichier
*/
$_config['fields'][0] = 'H'; //hash md5
$_config['fields'][1] = 'N'; //nom
$_config['fields'][2] = 'S'; //taille
// Ne touchez pas aux champs ci-dessus - Changez les champs ci-dessous :
$_config['fields'][3] = 'T'; //th�me,type, etc.
$_config['fields'][4] = 'C'; //commentaires
$_config['fields'][5] = 'D'; //date de prise de note

/* INTITUL� DES CHAMPS
Reprenez ici les champs cr��s ci-dessus et d�finissez l'intitul� de chaque champ.
*/
$_config['fields-name'][0] = 'Hash'; //hash md5
$_config['fields-name'][1] = 'Nom'; //nom
$_config['fields-name'][2] = 'Taille'; //taille
$_config['fields-name'][3] = 'Type'; //th�me,type,etc.
$_config['fields-name'][4] = 'Commentaires'; //commentaires
$_config['fields-name'][5] = 'Ann�e'; //date de prise de note

/* CHAMPS AFFICH�S
D�finissez ici les champs qui doivent �tre affich�s
*/
$_config['fields-display'] = array(1,2,5,3,4);

/* TAILLE DES CHAMPS LORS DE L'AFFICHAGE
D�finissez la taille HORIZONTALE des champs. *** ATTENTION : Le total doit �tre �gal � 100. ***
Le premier champ (button-size) correspond au bouton de t�l�chargement du fichier
*/
$_config['button-size']    = "2"; // le bouton de t�l�chargement
$_config['fields-display-size'][0] = "0";
$_config['fields-display-size'][1] = "38";
$_config['fields-display-size'][2] = "5";
$_config['fields-display-size'][3] = "10";
$_config['fields-display-size'][4] = "30";
$_config['fields-display-size'][5] = "15";

/* CHAMPS � REMPLIR LORS DE L'UPLOAD D'UN FICHIER
D�finissez les champs requis lors de l'upload d'un fichier.
*/
$_config['fields-upload'] = array(3,5,4);

/* TAILLE DES CHAMPS LORS DE L'UPLOAD
D�finissez la taille des champs du formulaire d'upload
*/
$_config['fields-upload-size'][0] = "0";
$_config['fields-upload-size'][1] = "40";
$_config['fields-upload-size'][2] = "30";
$_config['fields-upload-size'][3] = "30";
$_config['fields-upload-size'][4] = "40";
$_config['fields-upload-size'][5] = "20";

/* 	TYPE DE CHAMPS
D�finissez le type de chaque champ. Les types suivants sont disponibles :
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
Les champs de type SELECT permettent � l'utilisateur de choisir une valeur parmi plusieurs valeurs pr�d�finies.
Les valeurs pr�f�finies sont indiqu�es ici.
*/
$_config['fields-options'][3] = array("Notes de cours","Sujet d'examen","Exercice corrig�","Autres");
$_config['fields-options'][5] = array(	"Ann�e 1 Semestre 1",
										"Ann�e 1 Semestre 2",
										"Ann�e 2 Semestre 1",
										"Ann�e 2 Semestre 2",
										"Ann�e 3 Semestre 1",
										"Ann�e 3 Semestre 2",
										"Ann�e 4 Semestre 1",
										"Ann�e 4 Semestre 2");

/* EXTENSIONS AUTORIS�ES
Ce tableau contient la liste BLANCHE des extensions autoris�es. Seuls les fichiers portant ces extensions pourront �tre mis en ligne.
Tous les autres fichiers seront consid�r�s comme dangereux et ne seront pas enregistr�s sur le serveur.
*/
$_config['extensions'] = array(	'.pdf',	// Adobe Acrobat PDF (Portable Document Format)
								'.txt',	// texte brut
								'.zip',	// archive zip
								'.gz',	// archive gzip (fonctionne aussi pour tar.gz)
								'.tar',	// archive tar
								'.rar',	// archive RAR
								'.rtf',	// Fichier Rich Text Format
								'.doc',	// Fichier MS Word
								'.dot',	// Mod�le MS Word
								'.docx',// Fichier MS Word OpenXML
								'.dotx',// Mod�le MS Word OpenXML
								'.xls',	// Fichier MS Excel
								'.xlt',	// Mod�le MS Excel
								'.xlsx',// Fichier MS Excel OpenXML
								'.xltx',// Mod�le MS Excel OpenXML
								'.pps',	// Fichier MS Powerpoint (Diaporama)
								'.ppt',	// Fichier MS Powerpoint (Pr�sentation)
								'.pot',	// Mod�le MS Powerpoint
								'.ppsx',// Fichier MS Powerpoint OpenXML (Diaporama)
								'.pptx',// Fichier MS Powerpoint OpenXML (Pr�sentation)
								'.potx',// Mod�le MS Powerpoint OpenXML
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
								'.avi',	// Vid�o (conteneur g�n�rique)
								'.mpg',	// Vid�o MPEG
								'.mpeg',// Vid�o MPEG
								'.mp4',	// Vid�o MPEG 4 (h264)
								'.wmv',	// Vid�o Windows Media Player
								'.asf'	// Vid�o Windows Media Player
								);
?>