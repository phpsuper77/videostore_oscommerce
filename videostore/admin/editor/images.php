<?php
// predefined constants
define(IMG_PATH, "..\\img\\");
define(IMG_URL, "../img/");
?>

<html>
<head>
<LINK rel="stylesheet" type="text/css" href="dialog.css">
<script language="JavaScript">
function insertImage(img) {
  html = showModalDialog("my_dlg_ins_image.html", "<?php echo IMG_URL ?>"+img, "status:no;dialogWidth:400px;dialogHeight:200px;help:no;resizable:yes");

  if(html) {
	window.opener.document.all.doc.focus();
	var sel = window.opener.document.selection.createRange();
	sel.pasteHTML(html);
  }

  //window.close();
}

function previewImage(img) {
  document.all.image.innerHTML = "<table width='100%'><tr><td nowrap>Просмотр изображения (<font onmouseover='style.cursor=\"hand\"' onClick='document.all.image.innerHTML=\"\"'>закрыть</font>)&nbsp;</td><td valign='middle' width='100%'><hr width='100%'></td></tr></table><img src='<?php echo IMG_URL ?>"+img+"' border='0'>";
}

function confirmDelete(img) {
  if(confirm("Удалить файл "+img+"?"))
	window.location = "<?php echo basename($_SERVER['PHP_SELF']) ?>?event=del&file="+img;
    //return true;
  else
	return false;
}

//window.opener.wnd_images=false;
</script>

<title>Работа с изображениями</title>
</head>

<body topmargin="0" leftmargin="0" style="border: 0; margin: 0;" onbeforeunload="window.opener.wnd_images=false;">
<table class="dlg" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td>
	  <table width="100%"><tr><td nowrap>Список изображений&nbsp;</td><td valign="middle" width="100%"><hr width="100%"></td></tr></table>
	</td>
  </tr>  
  <tr>
    <td vAlign="top">

<?php
if($_SERVER['REQUEST_METHOD'] == "POST") extract($_POST);
else extract($_GET);

switch($event) {
  case "upload":
	fnImagesUpload();
    break;
  case "del":
	fnImagesDelete();
}

fnImagesList();

function fnImagesList() {
  global $result;

  if($handle = opendir(IMG_PATH)) {
	if($result != "") echo $result."<hr>";
	echo "<table border='0' cellpadding='2' cellspacing='2'>";
    while (($file = readdir($handle)) !== false) { 
      if($file != "." && $file != ".." && !is_dir(IMG_URL.$file)) { 
        $bg = (++$i%2) ? "#C0C0C0" : "#B0B0B0";
		echo "<tr bgcolor='".$bg."'><td width='100%'>".$file."</td><td onClick=\"insertImage('".$file."')\" onmouseover=\"style.cursor='hand'\">Вставить</td><td onmouseover=\"style.cursor='hand'\" onclick=\"previewImage('".$file."')\">Просмотр</td><td onmouseover=\"style.cursor='hand'\" onclick=\"return confirmDelete('".$file."')\">Удалить</td></tr>"; 
      }
	}
    closedir($handle);
	echo "</table>";
  }
?>
	</td>
  </tr>
  <tr>
	<td><div id="image"></div></td>
  </tr>
  <tr>
    <td colspan="5">
	  <table width="100%"><tr><td nowrap>Загрузка изображений&nbsp;</td><td valign="middle" width="100%"><hr width="100%"></td></tr></table>
	</td>
  </tr>
  <tr>
    <td>
	  <table border="0">
	    <FORM METHOD="POST" ACTION="<?php  echo basename($_SERVER['PHP_SELF']) ?>" name="upload_form" enctype="multipart/form-data">
		<input type="hidden" name="event" value="upload">
		<tr>
		  <td>Изображение:</td>
		  <td><input type="file" name="file" size="30" class="dlg"></td>
		  <td><input type="checkbox" name="over" value="1"> Перезаписать</td>
		</tr>
		<tr>
		  <td colspan="3" align="right"><input type="submit" value="Upload" class="button"> <input type="button" value="Закрыть" onClick="javascript: window.close();" class="button"></td>
		</tr>
	    </FORM>
	  </table>
    </td>
  </tr>
</table>
</body>
</html>

<?php
}

function fnImagesUpload() {
  global $result;

  if(is_uploaded_file($_FILES['file']['tmp_name'])) {
    if(preg_match("/^image\//", $_FILES['file']['type'])) {
	  if(@file_exists(IMG_PATH.$_FILES['file']['name']) && !$_POST['over'])
		$result = "Файл ".$_FILES['file']['name']." существует.";
	  else if(@move_uploaded_file($_FILES['file']['tmp_name'], IMG_URL.$_FILES['file']['name']))
	    $result = "Файл ".$_FILES['file']['name']." успешно закачан.";
	  else
		$result = "Ошибка загрузки файла на сервер.";
	}
	else
	  $result = "Неверный формат файла. Допускаются только файлы изображений.";
  }
  else
	$result = "Указан неверный файл.";

  //fnImagesList();
}

function fnImagesDelete() {
  global $result;

  if(@file_exists(IMG_PATH.$_GET['file'])) {
	if(@unlink(IMG_PATH.$_GET['file']))
	  $result = "Файл ".$_GET['file']." удален.";
	else
	  $result = "Ошибка удаления файла.";
  }
  else
	$result = "Файл ".$_GET['file']." не найден.";
}
?>