<?php
try{
    $pageName = "resumeTourney";
    include('tijariHeader.php');
    include('db_query.php');

    $_SESSION['_pageName'] = $pageName;
?>

<html>
<title>Tijari Championship - Welcome</title>
<?php
    include('metaTags.php');
?>
<HEAD>
<LINK href="css/tijari_stl.css" rel="stylesheet" type="text/css">
<style type="text/css">
    body {
	font-family   : Arial, Verdana, Helvetica, sans-serif;
	margin-top    : 0px;
	margin-left   : 0px;
	margin-right  : 0px;
	margin-bottom : 0px;
    }
</style>

<HEAD>

<script type="text/javascript">
var resumeButton;
var tourneysCbx;
var backButton;
var deleteButton;
var ds_tourneys;
var tourneys_data;

Ext.onReady(function(){

    resumeButton = new Ext.Button({
            renderTo : 'resumeButtonDiv',
            text     : 'Resume Playing',
            onClick  : resumePlaying,
            tabIndex : 2
    });

    backButton = new Ext.Button({
            renderTo : 'backButtonDiv',
            text     : 'Main Page',
            onClick  : toMainPage,
            tabIndex : 5
    });

    deleteButton = new Ext.Button({
            renderTo : 'deleteButtonDiv',
            text     : 'Delete Tourney',
            onClick  : deleteTourney,
            tabIndex : 4
    });

    tourneys_data = new Ext.data.JsonReader({}, [ 'Tourney_ID', 'Tourney_Desc']);

    ds_tourneys = new Ext.data.Store({
         proxy      : new Ext.data.HttpProxy({
             url : 'loadTourneys.php'
	 }),
	 reader     : tourneys_data,
         remoteSort : false
    });

    tourneysCbx = new Ext.form.ComboBox({
	store          : ds_tourneys,
	valueField     : 'Tourney_ID',
	displayField   : 'Tourney_Desc',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a tourney...',
        renderTo       : 'tourneysCbxDiv',
        id             : 'tourneys_id',
        name           : 'tourneys_id',
        tabIndex       : 1,
        forceSelection : true,
        triggerAction  : 'all',
        width          : 300,
        listeners      : {
   		 'select': function(){
                    var tourney_idx = tourneysCbx.getValue();

                    if(tourney_idx != '') {
                        getMaxScore(tourney_idx);
                    }
                 }
        }
    });

    ds_tourneys.load();

});

function getMaxScore(tourneyID)
{
   var strURL="getMaxScore.php?tourneyid="+tourneyID;

    if (window.XMLHttpRequest)
    {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else{
      // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

   if (xmlhttp)
   {
     xmlhttp.onreadystatechange = function()
     {
      if (xmlhttp.readyState == 4)
      {
	 if (xmlhttp.status == 200)
         {
            document.resumeForm.maxscore.value = xmlhttp.responseText;
	 } else {
            Ext.MessageBox.alert(' ','There was a problem getting Max Score' + xmlhttp.statusText,function(){});
	 }
       }
      }
   xmlhttp.open("GET", strURL, true);
   xmlhttp.send();
   }
}

function toMainPage(){
    window.location = "index.php";
}

function resumePlaying(){

    var tourney_idx = tourneysCbx.getValue();

    if(tourney_idx != '') {
        score = document.resumeForm.maxscore.value;

        if (score == 0) {
            document.resumeForm.action= "startSubGames.php";
        }
        if (score == 1000) {
            document.resumeForm.action= "startGame.php";
        }
        if (score > 600 && score != 1000) {
            document.resumeForm.action= "startGame.php";
        }
        if (score < 601 && score != 0) {
            document.resumeForm.action= "startSubGames.php";
        }
        document.resumeForm.tourneyid.value= tourney_idx;
        document.resumeForm.submit();

    } else {
        Ext.MessageBox.alert(' ','You must make a selection',function(){tourneysCbx.focus(true);});
    }
}

function deleteTourney(){

    var tourney_idx = tourneysCbx.getValue();

    if(tourney_idx != '') {
        Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete tourney?', function(getButtonBar){
            if (getButtonBar == 'yes'){
                deleteTourneyInfo(tourney_idx);
                tourneysCbx.store.remove(tourneysCbx.store.getAt(tourneysCbx.selectedIndex));
                tourneysCbx.clearValue();
            }
        });
    } else {
        Ext.MessageBox.alert(' ','You must make a selection',function(){tourneysCbx.focus(true);});
    }
}

function deleteTourneyInfo(tourneyid){

    if (window.XMLHttpRequest)
    {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else{
      // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState == 4)
        {
	 if (xmlhttp.status == 200)
         {
            Ext.MessageBox.alert(' ','Successfuly deleted tourney',function(){});
	 } else {
   	    Ext.MessageBox.alert(' ','There was a problem deleting tourney'  + xmlhttp.statusText,function(){});
	 }
        }
    }

    xmlhttp.open("GET","deleteTourney.php?tourneyid="+tourneyid,true);
    xmlhttp.send();
}

</script>
</HEAD>
<BODY bgcolor="black">
<br><br><br><br>
<center><span class="extWhiteTitle3">Welcome to Tijari Championship</span></center>
<br><br><br><br>

<form method="post" name="resumeForm" action="" onsubmit="return false;">

    <input name="maxscore"   id="maxscore"  type="hidden" value="">
    <input name="tourneyid"  id="tourneyid" type="hidden" value="">

    <table align="center" cellspacing="15">
        <tr>
            <td class="extWhiteBoldLabel2">Tourney:</td>
            <td>
                <div id="tourneysCbxDiv" name="tourneysCbxDiv"></div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div id="resumeButtonDiv"/>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div id="deleteButtonDiv"/>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td></td>
            <td>
                <div id="backButtonDiv"/>
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
