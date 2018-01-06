<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="tijari game morocco" />
<link rel="shortcut icon" href="<?php echo $file_path; ?>/images/favicon.ico" type="image/x-icon" />
<meta name="description" content="Welcome to the Tijari game, the most popular game in Morocco" />
<LINK href="<?php echo $file_path; ?>/css/mufts.css" rel="stylesheet"	type="text/css">
<LINK href="css/tijari_stl.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $file_path; ?>/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $file_path; ?>/css/xtheme-gray.css" />
<script type="text/javascript" src="<?php echo $file_path; ?>/javascript/ext-base.js"></script>
<script type="text/javascript" src="<?php echo $file_path; ?>/javascript/ext-all.js"></script>
<script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="&copy; 2009 MUFTS WebPro. All Rights Reserved.";

function warningMessage(){
	Ext.MessageBox.show({
		           title: 'Security Warning',
		           msg: message,
		           buttons: Ext.MessageBox.OK,
		           icon: Ext.MessageBox.WARNING
		       });
}
///////////////////////////////////
function clickIE4(){
	if (event.button==2){
		warningMessage();
		return false;
	}
}

function clickNS4(e){
	if (document.layers||document.getElementById&&!document.all){
		if (e.which==2||e.which==3){
			warningMessage();
			return false;
		}
	}
}

if (document.layers){
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
	document.onmousedown=clickIE4;
}

//document.oncontextmenu=new Function("warningMessage();return false")

// -->
</script>