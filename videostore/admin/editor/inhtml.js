function edit(field) {
  var edit = new Object;
  edit.src = field.value;



  var win = window.showModalDialog("editor/popup_editor.html", edit, 						"dialogWidth:750px;dialogHeight:400px;help:no;status:no;scroll:no;resizable:yes;");

  if(win!=null) {
  	field.value = win;
  }
}