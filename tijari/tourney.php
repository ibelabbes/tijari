<?php
try{
    include ('tijariHeader.php');
    $pageName = "tourney";

    include ('db_query.php');

    $_SESSION['_pageName'] = $pageName;
?>
<html>
<head>
<title>Tijari Championship - Welcome</title>
<?php
    //Includes all the common Meta Tags
    include('metaTags.php');
?>
<HEAD>
<style type="text/css">
    body {
	font-family: Arial, Verdana, Helvetica, sans-serif;
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
    }
</style>

<SCRIPT LANGUAGE="JavaScript">

var startButton;
var hostField;
var locationField;
var dateField;
var timeField;
var backToMainButton;

Ext.onReady(function(){
    Ext.QuickTips.init();

    startButton = new Ext.Button({
            renderTo : 'startButtonDiv',
            text     : 'Start Playing',
            onClick  : startPlay,
            tabIndex : 5
    });

    backToMainButton = new Ext.Button({
            renderTo : 'backToMainButtonDiv',
            text     : 'Main Page',
            onClick  : back,
            tabIndex : 6
    });

    hostField = new Ext.form.TextField({
            name     : 'host',
            renderTo : 'hostDiv',
            id       : 'host',
            width    : 150,
            tabIndex : 1
    });

    hostField.getEl().dom.maxLength = '25';

    locationField = new Ext.form.TextField({
            name     : 'location',
            renderTo : 'locationDiv',
            id       : 'location',
            width    : 150,
            tabIndex : 2
    });

    locationField.getEl().dom.maxLength = '25';

    dateField = new Ext.form.TextField({
            name     : 'date',
            renderTo : 'dateDiv',
            id       : 'date',
            width    : 150,
            tabIndex : 3
    });

    dateField.getEl().dom.maxLength = '25';

    timeField = new Ext.form.TextField({
            name     : 'time',
            renderTo : 'timeDiv',
            id       : 'time',
            width    : 150,
            tabIndex : 4
    });

    timeField.getEl().dom.maxLength = '10';
	setFocus();
});

function setFocus(){
    hostField.focus(true);
}

function back(){
    window.location = "index.php";
}

function trim(s) {
  var r = "";

  while ( s.length > 0 && s.substr(0, 1) == " ")  {
    s = s.substring(1);
  }
  while ( s.length > 0 && s.substr(s.length - 1, 1) == " ")  {
    s = s.substr(0, s.length - 1);
  }
  r = s;

  return r;
}

function startPlay(){
    var thost     = document.getElementById('host').value;
    var tlocation = document.getElementById('location').value;
    var tdate     = document.getElementById('date').value;
    var ttime     = document.getElementById('time').value;

    if (trim(thost) == null   || trim(thost).length < 1)
      {
        Ext.MessageBox.alert(' ','Please enter a host name',function(){hostField.focus(true);});
      } else if (trim(tlocation) == null || trim(tlocation).length < 1)
      {
        Ext.MessageBox.alert(' ','Please enter a location',function(){locationField.focus(true);});
      } else if (trim(tdate) == null || trim(tdate).length < 1)
      {
        Ext.MessageBox.alert(' ','Please enter a date',function(){dateField.focus(true);});
      } else if (trim(ttime) == null || trim(ttime).length < 1)
      {
        Ext.MessageBox.alert(' ','Please enter a time',function(){timeField.focus(true);});
      } else
      {
        document.mainForm.submit();
      }
}
</script>

</HEAD>
<BODY bgcolor="black">
<br><br>
<center><span class="extWhiteTitle3">Welcome to Tijari Championship</span></center>
<br>
<form name="mainForm" action="startGame.php" method="post" onsubmit="return false;">

<table align="center" cellspacing="15">
    <tr>
        <td class="extWhiteBoldLabel2">Host:</td>
        <td>
            <div id="hostDiv" name="hostDiv" class="extLabel"></div>
        </td>
    </tr>
    <tr>
        <td class="extWhiteBoldLabel2">Location:</td>
        <td>
            <div id="locationDiv" name="locationDiv" class="extLabel"></div>
        </td>
    </tr>
    <tr>
        <td class="extWhiteBoldLabel2">Date:</td>
        <td>
            <div id="dateDiv" name="dateDiv" class="extLabel"></div>
        </td>
    </tr>
    <tr>
        <td class="extWhiteBoldLabel2">Time:
        </td>
        <td>
            <div id="timeDiv" name="timeDiv" class="extLabel"></div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <div id="startButtonDiv"/>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td></td>
        <td>
            <div id="backToMainButtonDiv"/>
        </td>
    </tr>
</table>
</form>
</BODY>
</HTML>
<?php
} catch(Exception $e) {
	header( "Location: index.php" );
} ?>
