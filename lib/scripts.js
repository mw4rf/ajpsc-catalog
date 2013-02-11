/* Navigation d'un dossier à l'autre */
function soumettre(formulaire)
{
		document.forms[formulaire].submit();
}

// Fonction spéciale de soumission pour le formulaire d'admin
function submit_auth(formulaire)
{
	document.getElementById('auth_login').value = hex_md5(document.getElementById('auth_login').value);
	document.getElementById('auth_passwd').value = hex_md5(document.getElementById('auth_passwd').value);
	document.forms[formulaire].submit();
}

/* Fonction activée lors du clic sur le bouton de soumission du formulaire d'upload */
function upload(bouton,formulaire)
{
	document.getElementById('spinner').innerHTML = "<img src=\"lib/spinner.gif\" alt=\"spinner\" />&nbsp;"
	document.getElementById('message').innerHTML = "<b><i>Veuillez patienter</i></b><br />Fichier(s) en cours d'envoi.<br />Cela peut prendre un moment.";
	bouton.disabled = true;
	bouton.value = "Envoi en cours...";
	soumettre('uploadform');
}

/* Ajouter une ligne de formulaire d'upload */
function add_line()
{
	var tbl = document.getElementById('files_tab');
	
	// Ajouter une ligne
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1; // enlever le +1 s'il y a une première ligne d'en-tête dans le tableau
	var row = tbl.insertRow(lastRow);
	
	var cell = row.insertCell(0);
	
	var sel = document.createElement('input');
	sel.type = 'file';
	sel.name = 'fichiers[]';
	sel.id = 'fichier_' + iteration;
	cell.appendChild(sel);
}

/* Retirer une ligne de formulaire d'upload */
function rmv_line()
{
	var tbl = document.getElementById('files_tab');
	var lastRow = tbl.rows.length;
	if (lastRow > 1) tbl.deleteRow(lastRow - 1);
}