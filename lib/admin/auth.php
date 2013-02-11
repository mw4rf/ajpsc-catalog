<?php
session_start();
//error_reporting(0); // E_ALL
// Inclusions
include_once('../config.php');
include_once('../fonctions.php');

//


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>

	<title>Valhalla::Catalog</title>

	<!--// CSS -->
	<style type="text/css" media="screen">
		@import url("../styles.css");
	</style>
	<!--// JS -->
	<script src="../md5.js" language="javascript" type="text/javascript"></script>
	<script src="../scripts.js" language="javascript" type="text/javascript"></script>
</head>

<body>

<header>
	Valhalla::Catalog 1.2b <small>(&copy; 2007-<?php echo date('Y'); ?> Guillaume Florimond)</small>
	&nbsp;&nbsp;<a href="../aide/index.html">Aide</a>
</header>

<container>
<br />


<?php
// CAS 1 : AFFICHAGE DU FORMULAIRE D'IDENTIFICATION
if(!isset($_GET['next'])) { ?>
Veuillez vous identifier pour être reconnu comme administrateur du catalogue :
<br />

<form name="authform" method="post" action="auth.php?next=auth">
<table align="center" cellspacing=10 cellpadding=5>
	<tr><td style="text-align:left;">Login</td></tr>
	<tr><td><input type="text" size="30" id="auth_login" name="auth_login" /></td></tr>
	<tr><td style="text-align:left;">Mot de passe</td></tr>
	<tr><td><input type="password" size="30" id="auth_passwd" name="auth_passwd" /></td></tr>
	<tr><td><input type="button" onclick="javascript:submit_auth('authform');" value="S'identifier" /></td></tr>
	</tr>
</table>
</form>
<?php } elseif(isset($_POST['auth_login']) and isset($_POST['auth_passwd']) and $_GET['next'] == 'auth') {
// CAS 2 : TRAITEMENT DU FORMULAIRE
$login = $_POST['auth_login'];
$pass = $_POST['auth_passwd'];

//echo "$login => ".md5(config('admin_login'))."<br />";
//echo "$pass => ".md5(config('admin_pass'))."<br /><br />";

$res = adminauth($login,$pass);

if($res) echo "Identification réussie.";
else echo "Échec d'identification";

echo "<br /><br /><a href=\"../../index.php\">&lt;&lt; Retour à l'index</a>";

}
else
{echo "Erreur...!";}
?>


</container>

</body>
</html>
