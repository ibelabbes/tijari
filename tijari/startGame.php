<?php
try{

    include('tijariHeader.php');
    $pageName = "startGame";

    include('db_query.php');
    if (isset($_SESSION['_pageName'])) {
        $page_name_sess = $_SESSION['_pageName'];
    }
    $tourneypage    = "tourney";
    $subgamepage    = "startSubGames";
    $resumepage     = "resumeTourney";
    $current_tourney = null;
    $tourney_info = null;
    $games_count = 0;
    if (isset($_SESSION['_pageName'])) {
        if (strcmp($page_name_sess, $tourneypage) == 0) {
            $host     = trim($_POST['host']);
            $location = trim($_POST['location']);
            $date     = trim($_POST['date']);
            $time     = trim($_POST['time']);

            addTourney($host, $location, $date, $time);
            $curr_tourney_num = getCurrentTourneyNumber();

            $rs_players = getPlayers();
            $players_info = null;
            while($obj0 = mysql_fetch_object($rs_players))
            {
                $players_info[] = $obj0;
            }
            if (count($players_info) != 0) {
                for ($i=0; $i<count($players_info); $i++) {
                    addToTourneyWins($players_info[$i]->Player_ID, $curr_tourney_num);
                }
            }

            $rs_tourneys = getTourneyByInfoID($curr_tourney_num);

            while($obj1 = mysql_fetch_object($rs_tourneys))
            {
                $tourneys_info[] = $obj1;
            }

            $current_tourney = $tourneys_info[0];
            $games_count = getGamesCountByTourney($current_tourney->Tourney_ID);

            $_SESSION['_currtourneyid'] = $current_tourney->Tourney_ID;

        } else if (strcmp($page_name_sess, $resumepage) == 0){
            if (isset($_POST['tourneyid'])) {
                $tourneyid  = $_POST['tourneyid'];

                $rs_tourney = getTourneyByInfoID($tourneyid);
                $tourney_info = null;
                while($obj1 = mysql_fetch_object($rs_tourney))
                {
                    $tourney_info[] = $obj1;
                }

                $current_tourney = $tourney_info[0];
                $games_count = getGamesCountByTourney($current_tourney->Tourney_ID);

                $_SESSION['_currtourneyid'] = $current_tourney->Tourney_ID;
            }

        } else {
            if (isset($_SESSION['_currtourneyid'])) {
                $curr_tourney_num = $_SESSION['_currtourneyid'];

                $rs_tourneys = getTourneyByInfoID($curr_tourney_num);

                while($obj1 = mysql_fetch_object($rs_tourneys))
                {
                    $tourneys_info[] = $obj1;
                }

                $current_tourney = $tourneys_info[0];
                $games_count = getGamesCountByTourney($current_tourney->Tourney_ID);
            }
        }
    }

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
var startTerhButton;
var addPlayerButton;
var viewSummary1Button;
var pickTeamsButton;
var mainButton;
var firstNameField;
var lastNameField;
var playerWin;
var pickTeamsWin;
var ds_players;
var player11Cbx;
var player12Cbx;
var player21Cbx;
var player22Cbx;
var playersTextArea;


Ext.onReady(function(){
    Ext.QuickTips.init();

    startTerhButton = new Ext.Button({
            renderTo : 'startTerhButtonDiv',
            text     : 'Start Terh',
            onClick  : startTerh,
            tabIndex : 5
    });

    mainButton = new Ext.Button({
            renderTo : 'mainButtonDiv',
            text     : 'Main Page',
            onClick  : backToTheMain,
            tabIndex : 7
    });

    addPlayerButton = new Ext.Button({
            renderTo : 'addPlayerButtonDiv',
            text     : 'Add New Player',
            onClick  : showAddPlayerWin,
            tabIndex : 6
	});

    pickTeamsButton = new Ext.Button({
            renderTo : 'pickTeamsButtonDiv',
            text     : 'Pick Teams',
            onClick  : showPickTeamsWin,
            tabIndex : 9
	});

    viewSummary1Button = new Ext.Button({
            renderTo : 'viewSummary1ButtonDiv',
            text     : 'View Summary',
            onClick  : viewSummary1,
            tabIndex : 8
	});

    firstNameField = new Ext.form.TextField({
            name     : 'firstname',
            renderTo : 'firstnameDiv',
            id       : 'firstname',
            width    : 150
    });

    firstNameField.getEl().dom.maxLength = '22';

    lastNameField = new Ext.form.TextField({
            name     : 'lastname',
            renderTo : 'lastnameDiv',
            id       : 'lastname',
            width    : 150
    });

    lastNameField.getEl().dom.maxLength = '22';

    playerWin = new Ext.Window({
	    el          : 'player-win',
	    layout      : 'fit',
	    width       : 400,
	    height      : 170,
	    closeAction : 'hide',
	    plain       : true,
	    buttonAlign : 'center',
	    resizable   : false,
	    closable    : true,
	    modal       : true,
	    items       : new Ext.TabPanel({
                      el             : 'player-tab',
                      autoTabs       : true,
                      activeTab      : 0,
                      deferredRender : false,
                      border         : false,
                      baseCls        : 'backImage'
	    }),
            buttons     :
	    [
                  {text    : 'Add',
                   handler : function(){
                       addPlayerNow();
                   }
                  },
                  {text    : 'Cancel',
                   handler : function(){
                       playerWin.hide();
                   }
                  }
             ],
	     listeners  :
	     {
	   	 'hide' : function(){
                     Ext.get('firstname').dom.value = '';
                     Ext.get('lastname').dom.value  = '';
	   	  }
	     }
    });

    var players_data = new Ext.data.JsonReader({}, [ 'Player_ID', 'Name']);

    ds_players = new Ext.data.Store({
         proxy      : new Ext.data.HttpProxy({
             url : 'loadPlayers.php'
	 }),
	 reader     : players_data,
         remoteSort : false
    });

    player11Cbx = new Ext.form.ComboBox({
	store          : ds_players,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'player11CbxDiv',
        id             : 'player11',
        name           : 'player11',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var player11_idx = player11Cbx.getValue();
                     var player12_idx = player12Cbx.getValue();
                     var player21_idx = player21Cbx.getValue();
                     var player22_idx = player22Cbx.getValue();

                     if(player11_idx != '') {
                         if (player11_idx == player12_idx || player11_idx == player21_idx || player11_idx == player22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){player11Cbx.focus(true);});
                     }
                 }

        }
    });

    player12Cbx = new Ext.form.ComboBox({
	store          : ds_players,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'player12CbxDiv',
        id             : 'player12',
        name           : 'player12',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var player11_idx = player11Cbx.getValue();
                     var player12_idx = player12Cbx.getValue();
                     var player21_idx = player21Cbx.getValue();
                     var player22_idx = player22Cbx.getValue();

                     if(player12_idx != '') {
                         if (player12_idx == player11_idx || player12_idx == player21_idx || player12_idx == player22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){player12Cbx.focus(true);});
                    }
                 }
        }
    });

    player21Cbx = new Ext.form.ComboBox({
	store          : ds_players,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'player21CbxDiv',
        id             : 'player21',
        name           : 'player21',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var player11_idx = player11Cbx.getValue();
                     var player12_idx = player12Cbx.getValue();
                     var player21_idx = player21Cbx.getValue();
                     var player22_idx = player22Cbx.getValue();

                     if(player21_idx != '') {
                         if (player21_idx == player11_idx || player21_idx == player12_idx || player21_idx == player22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){player21Cbx.focus(true);});
                    }
                 }
        }
    });

    player22Cbx = new Ext.form.ComboBox({
	store          : ds_players,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'player22CbxDiv',
        id             : 'player22',
        name           : 'player22',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var player11_idx = player11Cbx.getValue();
                     var player12_idx = player12Cbx.getValue();
                     var player21_idx = player21Cbx.getValue();
                     var player22_idx = player22Cbx.getValue();

                     if(player22_idx != '') {
                         if (player22_idx == player11_idx || player22_idx == player12_idx || player22_idx == player21_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){player22Cbx.focus(true);});
                     }
                 }
        }
    });

    ds_players.load();

    pickTeamsWin = new Ext.Window({
		    el          : 'pickTeams-win',
		    layout      : 'fit',
		    width       : 400,
		    height      : 310,
		    closeAction : 'hide',
		    plain       : true,
		    buttonAlign : 'center',
		    resizable   : false,
		    closable    : true,
		    modal       : true,
		    items       : new Ext.TabPanel({
	                      el             : 'pickTeams-tab',
	                      autoTabs       : true,
	                      activeTab      : 0,
	                      deferredRender : false,
	                      border         : false,
	                      baseCls        : 'backImage'

		    }),
	            buttons     :
		    [
	                  {text    : 'Submit Rey',
	                   handler : function(){
	                       pickRandomTeams();
	                   }
	                  },
	                  {text    : 'Cancel',
	                   handler : function(){
	                       pickTeamsWin.hide();
	                       document.getElementById("teamsPick").innerHTML ='';
	                   }
	                  }
	             ],
		     listeners  :
		     {
		   	 'hide' : function(){
	                     Ext.get('playersList').dom.value = '';
		   	  }
		     }
    });

    playersTextArea = new Ext.form.TextArea({
            name     : 'playersList',
            renderTo : 'playersListDiv',
            id       : 'playersList',
            width    : 180,
            height   : 110
    });

});

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

function backToTheMain(){
    window.location = "index.php";
}

function isValidName(alphane){
  var iChars = "!@#$%^&*()+=-[]\';,./{}|\":<>?~`0123456789";
  for (var i = 0; i < alphane.length; i++) {
  	if (iChars.indexOf(alphane.charAt(i)) != -1)
  	{
	  	return false;
  	}
  }
  return true;
}

function startTerh(){
    var player11_idx = player11Cbx.getValue();
    var player12_idx = player12Cbx.getValue();
    var player21_idx = player21Cbx.getValue();
    var player22_idx = player22Cbx.getValue();

     if(player11_idx != '' && player12_idx != '' && player21_idx != '' && player22_idx != '') {

        if (player11_idx == player12_idx || player11_idx == player21_idx || player11_idx == player22_idx ||
            player12_idx == player21_idx || player12_idx == player22_idx || player21_idx == player22_idx ) {
             Ext.MessageBox.alert(' ','You must select 4 different players',function(){player11Cbx.focus(true);});
        } else {
            gameForm.player11_id.value = player11_idx;
            gameForm.player12_id.value = player12_idx;
            gameForm.player21_id.value = player21_idx;
            gameForm.player22_id.value = player22_idx;
            document.gameForm.submit();
        }
     } else {
        Ext.MessageBox.alert(' ','You must select 4 different players',function(){player11Cbx.focus(true);});
     }
}

function showAddPlayerWin(){
    playerWin.show(this);
    firstNameField.focus(false,50);
}

function showPickTeamsWin(){
    pickTeamsWin.show(this);
    var names = '';

	ds_players.each(function(rec) {
		if (names.length > 0) {
			names = names + '\n' + rec.get('Name');
		} else {
			names = rec.get('Name');
		}
	});

	Ext.get('playersList').dom.value = names;
	document.getElementById("teamsPick").innerHTML ='';
}

function pickRandomTeams(){

	var names = Ext.get('playersList').dom.value;

	var names_array = names.split('\n');

	if (names_array.length < 4) {
		Ext.MessageBox.alert(' ','There must be at least 4 players in the list',function(){document.getElementById("teamsPick").innerHTML ='';});
	}

	var index1 = Math.floor(Math.random() * names_array.length)
	var team1_player1 = names_array[index1];
	names_array.splice(index1, 1);

	var index2 = Math.floor(Math.random() * names_array.length)
	var team1_player2 = names_array[index2];
	names_array.splice(index2, 1);

	var index3 = Math.floor(Math.random() * names_array.length)
	var team2_player1 = names_array[index3];
	names_array.splice(index3, 1);

	var index4 = Math.floor(Math.random() * names_array.length)
	var team2_player2 = names_array[index4];
	names_array.splice(index4, 1);

	document.getElementById("teamsPick").innerHTML = team1_player1 + '  &  ' + team1_player2 + '<br>VS<br>' + team2_player1 + '  &  ' + team2_player2;

}

function addPlayerNow(){

    var first_name = trim(firstNameField.getValue());
    var last_name  = trim(lastNameField.getValue());

    if (first_name == null   || first_name.length < 1 || !isValidName(first_name))
    {
        Ext.MessageBox.alert(' ','Please enter a valid first name',function(){firstNameField.focus(true);});
    } else if (last_name == null   || last_name.length < 1 || !isValidName(last_name))
    {
        Ext.MessageBox.alert(' ','Please enter a valid last name',function(){lastNameField.focus(true);});
    } else {
        addPlayerToDB(first_name,last_name, '<?php echo $_SESSION['_currtourneyid']; ?>');
    }
}
function addPlayerToDB(str1, str2, tourneyid){

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
            playerWin.hide();
            Ext.MessageBox.alert(' ','Successfuly added new player',function(){});
	 } else {
   	    Ext.MessageBox.alert(' ','There was a problem adding new player'  + xmlhttp.statusText,function(){});
	 }
        }
    }

    xmlhttp.open("GET","addPlayer.php?fname="+str1+"&lname="+str2+"&tourneyid="+tourneyid,true);
    xmlhttp.send();
}

function viewSummary1(){
    window.open('tourneySummary.php');
}


</script>
</HEAD>
<BODY bgcolor="black">
<?php if ($current_tourney != null) { ?>
<center>
    <h2><span class="extWhiteTitle3">
        <?php echo $current_tourney->Host; ?> - <?php echo $current_tourney->Location; ?> - <?php echo $current_tourney->Date; ?>
    </span></h2>
</center>

<span class="extWhiteBoldLabel22">&nbsp;&nbsp;&nbsp;&nbsp;<u><b>Game # <?php echo $games_count + 1; ?></b></u></span>

<form method="post" name="gameForm" action="startSubGames.php" onsubmit="return false;">
<input name="player11_id"  id="player11_id" type="hidden" value="">
<input name="player12_id"  id="player12_id" type="hidden" value="">
<input name="player21_id"  id="player21_id" type="hidden" value="">
<input name="player22_id"  id="player22_id" type="hidden" value="">


<table cellspacing="10">
	<tr>
		<td>
			<table cellspacing="10">
				<tr>
					<td width="100"></td>
					<td align="left" class="extWhiteBoldLabel22"><i><b><u>First Team</u></b></i></td>
				</tr>
				<tr>
					<td class="extWhiteBoldLabel22" width="100"><b>Player 1:</b></td>
					<td>
						<div id="player11CbxDiv" name="player11CbxDiv"></div>
					</td>
				</tr>
				<tr>
					<td class="extWhiteBoldLabel22" width="100"><b>Player 2:</b></td>
					<td>
						<div id="player12CbxDiv" name="player12CbxDiv"></div>
					</td>
				</tr>
			</table>
			<table cellspacing="10">
				<tr>
					<td width="100"></td>
					<td align="left" class="extWhiteBoldLabel22"><i><b><u>Second Team</u></b></i></td>
				</tr>
				<tr>
					<td class="extWhiteBoldLabel22" width="100"><b>Player 1:</b></td>
					<td>
						<div id="player21CbxDiv" name="player22CbxDiv"></div>
					</td>
				</tr>
				<tr>
					<td class="extWhiteBoldLabel22" width="100"><b>Player 2:</b></td>
					<td>
						<div id="player22CbxDiv" name="player22CbxDiv"></div>
					</td>
				</tr>
			</table>
		</td>

		<td width="600">
		</td>

		<td width="400"align="center" valign="middle">
			<img src="images/carta.png" border="0">
		</td>
	</tr>
</table>

<table cellspacing="10">
    <tr>
        <td align="right" width="160">
            <div id="startTerhButtonDiv"/>
        </td>
        <td align="right" width="200">
            <div id="addPlayerButtonDiv"/>
        </td>
        <td align="right" width="300">
			<div id="pickTeamsButtonDiv"/>
		</td>
        <td align="center" width="400">
            <div id="mainButtonDiv"/>
        </td>
        <td align="left" width="120">
            <div id="viewSummary1ButtonDiv"/>
        </td>
    </tr>
</table>
        <?php } ?>
<div id="player-win" class="x-hidden">
	<div id="player-tab">
		<div id="playerTab" class="x-tab" title="Add new player">
                    <form>
                    <TABLE align="center">
                        <TBODY>
                            <TR>
                                <TD>
                                    <TABLE align="center" cellspacing="10">
                                        <TBODY>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>First Name:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="firstnameDiv" name="firstnameDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>Last Name:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="lastnameDiv" name="lastnameDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                            </TR>
                                            <TR>
                                            </TR>
                                            <TR>
                                            </TR>
                                        </TBODY>
                                    </TABLE>
                                </TD>
                            </TR>
                        </TBODY>
                    </TABLE>
                    </form>
		</div>
	</div>
</div>


<div id="pickTeams-win" class="x-hidden">
	<div id="pickTeams-tab">
		<div id="pickTeamsTab" class="x-tab" title="Pick Teams">
                    <form>
                    <TABLE align="center">
                        <TBODY>
                            <TR>
                                <TD>
                                    <TABLE cellspacing="10">
                                        <TBODY>
                                            <TR>
                                            </TR>
                                            <TR>
                                                <TD align="left" class="extBoldLabel2"><b>Players List:</b>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD align="left">
                                                    <div id="playersListDiv" name="playersListDiv" class="extBoldLabel1"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD align="center" class="extBoldLabel2">
                                                    <div  class="extBoldLabel2">
                                                    	<label id="teamsPick"/>
                                                    </div>
                                                </TD>
                                            </TR>
                                        </TBODY>
                                    </TABLE>
                                </TD>
                            </TR>
                        </TBODY>
                    </TABLE>
                    </form>
		</div>
	</div>
</div>

<?php if ($games_count > 0) { ?>

    <table border="1px" align="center" bordercolor="white" cellspacing="0">
        <tr>
            <td width="80"  align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
                <i>Game #</i>
            </td>
            <td width="200" align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
                <i><b>Winners</b></i>
            </td>
            <td width="200" align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
                <i><b>Losers</b></i>
            </td>
        </tr>
    </table>

<?php
        $rs_games = getGameWinnersByTourney($current_tourney->Tourney_ID);
        while($obj2 = mysql_fetch_object($rs_games))
        {
            $gamewinners[] = $obj2;
        }

        for($k=0; $k<count($gamewinners); $k++)
        {
?>

    <table border="1px" align="center" bordercolor="white" cellspacing="0">
        <tr>
            <td width="80"  align="center" class="extWhiteBoldLabel22">
                <?php echo $gamewinners[$k]->Game_ID; ?>
            </td>
            <?php if ($gamewinners[$k]->Team1_Total > $gamewinners[$k]->Team2_Total) { ?>
            <td width="200" align="center" class="extWhiteBoldLabel22">
                <?php echo $gamewinners[$k]->P11_Name; ?>  &  <?php  echo $gamewinners[$k]->P12_Name; ?>
            </td>
            <?php } else { ?>
            <td width="200" align="center" class="extWhiteBoldLabel22">
                <?php echo $gamewinners[$k]->P21_Name; ?>  &  <?php  echo $gamewinners[$k]->P22_Name; ?>
            </td>
            <?php } ?>

            <?php if ($gamewinners[$k]->Team1_Total > $gamewinners[$k]->Team2_Total) { ?>
            <td width="200" align="center" class="extWhiteBoldLabel22">
                <?php echo $gamewinners[$k]->P21_Name; ?>  &  <?php  echo $gamewinners[$k]->P22_Name; ?>
            </td>
            <?php } else { ?>
            <td width="200" align="center" class="extWhiteBoldLabel22">
                <?php echo $gamewinners[$k]->P11_Name; ?>  &  <?php  echo $gamewinners[$k]->P12_Name; ?>
            </td>
            <?php } ?>

        </tr>
    </table>
<?php
    }
}
?>



</form>
</BODY>
</HTML>
<?php
} catch(Exception $e) {
	header( "Location: index.php" );
} ?>
