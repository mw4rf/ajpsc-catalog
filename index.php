<?php
//error_reporting(0); // E_ALL
// Inclusions
include_once('lib/config.php');
include_once('lib/fonctions.php');

//


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>

	<title>AJPSC::Catalog</title>

	<!--// CSS -->
	<style type="text/css" media="screen">
		@import url("lib/styles.css");
	</style>
	<!--// Favicon -->
	<LINK REL="SHORTCUT ICON" href="favicon.ico">
	<!--// JS -->
	<script src="lib/scripts.js" language="javascript" type="text/javascript"></script>
</head>

<body>

<header>
	AJPSC::Catalog 1.3b <small>(&copy; 2007-<?php echo date('Y'); ?> Guillaume Florimond)</small>
	&nbsp;&nbsp;<a href="lib/aide/index.html">Aide</a>
</header>

<container>
<?php
// Récupération du dossier à afficher
if(isset($_GET['a']) and $_GET['a'] == 'browse' and isset($_POST['dir']))
	$root = urldecode($_POST['dir']);
else
	$root = config('root');

// Inclusions
include('lib/actions.php');
if(isset($_GET['a']) and $_GET['a'] == 'tree')
	include('lib/tree.php');
else
{
	include('lib/display.php');
	include('lib/upload.php');
}
?>
</container>

</body>
</html>
