<?
include "../includes/configure.php";
?>

<html>
<head>
<title>Image Upload</title>

<style>
td {  font-family: Verdana; font-size: 10px}
.login_name {  padding-top: 5px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; font-weight: bold}
input {  border: 1px #7C7C7C solid; background-color: #cccccc; font-family: Verdana; font-size: 10px}
.hand {  cursor: hand}
.hidden {  overflow: hidden; clip:   rect(   ); z-index: -1; color: #FFFFFF; background-color: #FFFFFF; border-style: none; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
.categoriesTitle {  font-weight: bold; margin-left: 10px; padding-left: 10px}
a {  color: #666666}
a:hover {  color: #000000}
select { border: 1px #7C7C7C solid; background-color: #cccccc; font-family: Verdana; font-size: 10px }
.radio {  background-color: #FFFFFF; border-style: none; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
.actionstatus {  font-weight: bold; color: #000099}
.tableTitle {  font-size: 11px; font-weight: bold}
textarea { border: 1px #7C7C7C solid; background-color: #cccccc; font-family: Verdana; font-size: 10px }
.submit {  border: 0px #ffffff solid; background-color: #ffffff; font-family: Verdana; font-size: 10px; cursor: hand}
</style>


</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="design/01.gif">
<?

  $PATH = "images/upload/"; 
  $root = DIR_FS_CATALOG;

  if ($_POST["del"] != '') {
    @unlink($root.$PATH.$_POST["del"]);
  }
  elseif ($_FILES["img"]['tmp_name'] != '') {
    @move_uploaded_file($_FILES["img"]['tmp_name'], 
                        $root.$PATH.$_FILES["img"]['name']);

    @chmod($root.$PATH.$_FILES["img"]['name'], 0666);
  }
?>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="8" height="7"><img src="design/06.gif" width="8" height="7"></td>
    <td background="design/07.gif"><img src="design/07.gif" width="1" height="7"></td>
    <td width="12" height="7"><img src="design/08.gif" width="12" height="7"></td>
  </tr>
  <tr>
    <td background="design/09.gif"><img src="design/09.gif" width="8" height="3"></td>
    <td background="design/14.gif">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="design/16.gif" width="39" height="39"></td>
          <td class="categoriesTitle" width="100%">Images Manager</td>
        </tr>
      </table>
      <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td colspan="2">
          <p>How to use insert an image into HTML editor:
          <ol>
            <li>select an image from the list, it will appear in the right column</li>
            <li>right-click on image, select <b>Copy</b> from the context-menu</li>
            <li>close this window, move cursor to the desired position and paste image</li>
          </ol>
          </td>
        </tr>
        <tr>
          <td width="200" valign="top"><form method="post">
            <select name="del" onChange="document.preview.src='<? echo HTTP_SERVER.DIR_WS_CATALOG.$PATH ?>'+this.value" size="10" style="width:180px">
<?
  $d = opendir($root.rtrim($PATH, '/'));
  while ( ($f = readdir($d)) !== false ) {
    if ($f == '.' || $f == '..') continue;
    echo "<option value=\"$f\">".$f;
  }
  closedir($d);
?>
            </select>
            <br><input type="submit" value="delete selected" style="width: 180px"></a></form>
          </td>
          <td width="400">
            <img src="" name="preview">
          </td>
        </tr>
        <tr>
          <td colspan="2"><form method="post" enctype="multipart/form-data">
            Upload a new image:
            <input type="file" name="img" size="40">
            <input type="submit" value="Upload">
          </form>
          </td>
        </tr>
      </table>
    </td>
    <td background="design/10.gif"><img src="design/10.gif" width="12" height="2"></td>
  </tr>
  <tr>
    <td><img src="design/11.gif" width="8" height="12"></td>
    <td background="design/12.gif"><img src="design/12.gif" width="1" height="12"></td>
    <td><img src="design/13.gif" width="12" height="12"></td>
  </tr>
</table>
