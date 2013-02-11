<?php
// ------------- FORMULAIRES D'UPLOAD ----------------------
if(!isset($_GET['a2']) or $_GET['a2'] != "upload" or $_GET['a2'] != "mkdir") {
?>
<!--// Upload -->
<div style="float:left;width:50%;">
<center><b>Ajout de fichiers</b></center><hr />
<form 	id="uploadform" name="uploadform" method="post" action="index.php?a=browse&a2=upload"
		enctype="multipart/form-data">
  <input type="hidden" name="dir" value="<?php echo urlencode($root) ?>" /> <!--//  -->
  <table width="90%" border="0">
	<?php
	foreach($_config['fields-upload'] as $key)
	{
		$field	=	$_config['fields-name'][$key];
		$name	=	$_config['fields'][$key];
		$size	=	$_config['fields-upload-size'][$key];
		$type	=	$_config['fields-upload-type'][$key];

		switch($type)
		{
			case 'text': echo "<tr><td>$field</td><td><input type=\"text\" name=\"$name\" size=\"$size\" /></td></tr>\n\t"; break;
			case 'hidden': echo "<tr><td>$field</td><td><input type=\"hidden\" name=\"$name\"/></td></tr>\n\t"; break;
			case 'textarea': echo "<tr><td>$field</td><td><textarea name=\"$name\" cols=\"$size\"></textarea></td></tr>\n\t"; break;
			case 'select':
				echo "<tr><td>$field</td><td><select name=\"$name\">";
				foreach($_config['fields-options'][$key] as $option)
					echo "<option value='".urlencode($option)."'>$option</option>";
				echo "</select></td></tr>\n\t";
			break;
		}
	}
	?>

    <tr>
      <td>Fichier(s)<br />
			<input type="button" onclick="javascript:add_line()" value="+" />&nbsp;
			<input type="button" onclick="javascript:rmv_line()" value="-" />
	  </td>
      <td>
		<table id="files_tab" style="margin:0 auto; width:100%">
			<tr>
				<td>
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo config('maxfilesize'); ?>" />
					<input type="file" name="fichiers[]" style="width:100%;"/>
				</td>
			</tr>
		</table>
	  </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
		<input type="submit" name="button" id="bouton_envoi" value="Envoyer" onclick="javascript:upload(this);"/>
		<p id="spinner" style="text-align:left;">&nbsp;</p>
		<p id="message">&nbsp;</p>
	  </td>
    </tr>
  </table>
</form>
</div>

<!--// Mkdir -->
<div style="float:right;width:40%;">
<form id="form2" name="form2" method="post" action="index.php?a=browse&a2=mkdir">
  <input type="hidden" name="dir" value="<?php echo urlencode($root) ?>" />
  <input type="hidden" name="parent" value="<?php echo $root; ?>" />
  <table width="90%" border="0">
    <tr>
      <td>Nouveau dossier :</td>
      <td><input type="text" value="" name="newdir" /></td>
      <td><input type="submit" name="button" id="button" value="Envoyer" /></td>
    </tr>
	<tr>
		<td colspan="3" id="message2"></td>
	</tr>
  </table>
</form>
</div>
<?php } //endif ?>