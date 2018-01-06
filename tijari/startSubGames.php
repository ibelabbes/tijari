<?php
try{

    include('tijariHeader.php');
    $pageName  = "startSubGames";
    include('db_query.php');

    if (isset($_SESSION['_pageName'])) {
        $page_name_sess = $_SESSION['_pageName'];
    }
    $startGame         = "startGame";
    $resumepage        = "resumeTourney";
    $tourneys_info     = null;
    $current_tourney   = null;
    $new_subgame_count = 0;

if (isset($_POST['initialRename'])) {
    $renameValue = $_POST['initialRename'];
    if (strcmp($renameValue, 'true') == 0){
        $page_name_sess = $resumepage;
    }
}

if (isset($_SESSION['_pageName'])) {
    if (strcmp($page_name_sess, $startGame) == 0) {
        if (isset($_SESSION['_currtourneyid'])) {
            $curr_tourneyid = $_SESSION['_currtourneyid'];
            $rs_tourneys = getTourneyByInfoID($curr_tourneyid);

            //while($obj1 = mysql_fetch_object($rs_tourneys))
            while($obj1 = mysqli_fetch_object($rs_tourneys))
            {
                $tourneys_info[] = $obj1;
            }

            $current_tourney = $tourneys_info[0];
            $games_count = getGamesCountByTourney($curr_tourneyid);

            $player11 = trim($_POST['player11']);
            $player12 = trim($_POST['player12']);
            $player21 = trim($_POST['player21']);
            $player22 = trim($_POST['player22']);

            $split_player11 = explode(" ", $player11);
            $split_player12 = explode(" ", $player12);
            $split_player21 = explode(" ", $player21);
            $split_player22 = explode(" ", $player22);

            $player11_id = $_POST['player11_id'];
            $player12_id = $_POST['player12_id'];
            $player21_id = $_POST['player21_id'];
            $player22_id = $_POST['player22_id'];

            addGame($games_count + 1, $curr_tourneyid, $player11_id, $player12_id, $player21_id, $player22_id);

            $curr_gameNumber = getGamesCountByTourney($curr_tourneyid);
            $rs_gameinfo = getGameInfo($curr_tourneyid, $curr_gameNumber);

            //while($obj2 = mysql_fetch_object($rs_gameinfo))
            while($obj2 = mysqli_fetch_object($rs_gameinfo))
            {
                $game_info[] = $obj2;
            }

            $current_subgame = $game_info[0];

            $new_subgame_count = getSubGamesCountByGame($curr_tourneyid, $curr_gameNumber);

            $_SESSION['_pageName'] = $pageName;
        }

    } else if (strcmp($page_name_sess, $resumepage) == 0){

            $tourneyid  = $_POST['tourneyid'];
            $maxscore   = $_POST['maxscore'];

            $rs_tourney = getTourneyByInfoID($tourneyid);

            //while($obj1 = mysql_fetch_object($rs_tourney))
            while($obj1 = mysqli_fetch_object($rs_tourney))
            {
                $tourney_info[] = $obj1;
            }

            $current_tourney = $tourney_info[0];

            $curr_gameNumber = getGamesCountByTourney($tourneyid);

            if ($maxscore == 0) {

                $rs_gameinfo = getGameInfo($tourneyid, $curr_gameNumber);

                //while($obj2 = mysql_fetch_object($rs_gameinfo))
                while($obj2 = mysqli_fetch_object($rs_gameinfo))
                {
                    $game_info[] = $obj2;
                }

                $current_subgame   = $game_info[0];
                $new_subgame_count = getSubGamesCountByGame($tourneyid, $curr_gameNumber);

            } else {

                $rs_subgameinfo  = getTourneySummaryByGameID($tourneyid, $curr_gameNumber);

                //while($obj2 = mysql_fetch_object($rs_subgameinfo))
                while($obj2 = mysqli_fetch_object($rs_subgameinfo))
                {
                    $subgame_info[] = $obj2;
                }

                $current_subgame   = $subgame_info[count($subgame_info) - 1];
                $new_subgame_count = getSubGamesCountByGame($tourneyid, $curr_gameNumber);
            }

            $_SESSION['_currtourneyid'] = $tourneyid;
            $_SESSION['_pageName'] = $pageName;

    } else {
        if (isset($_SESSION['_currtourneyid'])) {
            $curr_tourneyid = $_SESSION['_currtourneyid'];
            $rs_tourneys = getTourneyByInfoID($curr_tourneyid);

            //while($obj1 = mysql_fetch_object($rs_tourneys))
            while($obj1 = mysqli_fetch_object($rs_tourneys))
            {
                $tourneys_info[] = $obj1;
            }

            $current_tourney = $tourneys_info[0];

            $curr_gameNumber = getGamesCountByTourney($curr_tourneyid);
            $rs_gameinfo     = getGameInfo($curr_tourneyid, $curr_gameNumber);

            //while($obj2 = mysql_fetch_object($rs_gameinfo))
            while($obj2 = mysqli_fetch_object($rs_gameinfo))
            {
                $game_info[] = $obj2;
            }

            $current_game = $game_info[0];

            $team1score = $_POST['team1Score'];
            $team2score = $_POST['team2Score'];

            $team1ScoreSofar = 0;
            $team1ScoreSofar = 0;

            if ($team1score == 0){
                $team1ScoreSofar = $current_game->Team1_Total;
            } else {
                $team1ScoreSofar = $current_game->Team1_Total + $team1score;
            }

            if ($team2score == 0){
                $team2ScoreSofar = $current_game->Team2_Total;
            } else {
                $team2ScoreSofar = $current_game->Team2_Total + $team2score;
            }

            $team1scoreresult = str_pad($team1score, 3, "0", STR_PAD_LEFT) . " - " . str_pad($team1ScoreSofar, 3, "0", STR_PAD_LEFT);
            $team2scoreresult = str_pad($team2score, 3, "0", STR_PAD_LEFT) . " - " . str_pad($team2ScoreSofar, 3, "0", STR_PAD_LEFT);

            $subgame_count    = getSubGamesCountByGame($curr_tourneyid, $curr_gameNumber);

            if (($team1score != 0) || ($team2score != 0)) {
                addSubGame($subgame_count + 1, $current_game->Game_ID, $curr_tourneyid, $team1score, $team2score, $team1scoreresult, $team2scoreresult);
                updateGameScores($current_game->Game_ID, $curr_tourneyid, $current_game->Team1_Total + $team1score, $current_game->Team2_Total + $team2score);
            }

            $rs_subgameinfo = getTourneySummaryByGameID($curr_tourneyid, $curr_gameNumber);

            //while($obj3 = mysql_fetch_object($rs_subgameinfo))
            while($obj3 = mysqli_fetch_object($rs_subgameinfo))
            {
                $subgame_info[] = $obj3;
            }

            $current_subgame   = $subgame_info[count($subgame_info) - 1];
            $new_subgame_count = getSubGamesCountByGame($curr_tourneyid, $curr_gameNumber);


            if (($current_subgame->Team1_Total > 600) || ($current_subgame->Team2_Total > 600)) {

                if ($current_subgame->Team1_Total > 600) {
                    addSubGame($current_subgame->SubGame_ID + 1, $current_game->Game_ID, $current_tourney->Tourney_ID, 0, 0, 'Winners', '');

                    updateTourneyWins($current_game->Team1_Player1_ID, $current_tourney->Tourney_ID,1);
                    updateTourneyWins($current_game->Team1_Player2_ID, $current_tourney->Tourney_ID,1);
                    updateTourneyWins($current_game->Team2_Player1_ID, $current_tourney->Tourney_ID,0);
                    updateTourneyWins($current_game->Team2_Player2_ID, $current_tourney->Tourney_ID,0);
                }
                if ($current_subgame->Team2_Total > 600) {
                    addSubGame($current_subgame->SubGame_ID + 1, $current_game->Game_ID, $current_tourney->Tourney_ID, 0, 0, '', 'Winners');

                    updateTourneyWins($current_game->Team1_Player1_ID, $current_tourney->Tourney_ID,0);
                    updateTourneyWins($current_game->Team1_Player2_ID, $current_tourney->Tourney_ID,0);
                    updateTourneyWins($current_game->Team2_Player1_ID, $current_tourney->Tourney_ID,1);
                    updateTourneyWins($current_game->Team2_Player2_ID, $current_tourney->Tourney_ID,1);
                }

                $_SESSION['_pageName'] = $pageName;
                header("Location: startGame.php");
            }
            $_SESSION['_pageName'] = $pageName;
        }
    }
}
?>
<html>
<title>Tijari Championship - Welcome</title>
<?php
	//Includes all the common Meta Tags
	include('metaTags.php');
?>
<HEAD>
<LINK href="css/tijari_stl.css" rel="stylesheet" type="text/css">
<style type="text/css">
	body {
	font-family  : Arial, Verdana, Helvetica, sans-serif;
	margin-top   : 0px;
	margin-left  : 0px;
	margin-right : 0px;
	margin-bottom: 0px;
        }

        a.edt:link {color: white;}
        a.edt:visited {color: white;}
        a.edt:hover {color: red;}
        a.edt:focus {color: red;}
        a.edt:active {color: white;}
</style>


<script type="text/javascript">
var startNewTerhButton;
var viewSummary2Button;
var team1ScoreCbx;
var team2ScoreCbx;
var mainPageButton;
var editPlayersButton;
var ds_editPlayers;
var editPlayer11Cbx;
var editPlayersWin;
var editScoreWin;
var edit_team1_scoreA;
var edit_team1_scoreB;
var edit_team2_scoreA;
var edit_team2_scoreB;
var edit_team1_TotalScore;
var edit_team2_TotalScore;

Ext.onReady(function(){
    Ext.QuickTips.init();

    startNewTerhButton = new Ext.Button({
        renderTo : 'startNewTerhButtonDiv',
        text     : 'Start Another Terh',
        onClick  : startNewTerh,
        tabIndex : 3
    });

	editPlayersButton = new Ext.Button({
        renderTo : 'editPlayersButtonDiv',
        text     : 'Change Team Players',
        onClick  : showEditPlayersWin,
        tabIndex : 4
    });

    mainPageButton = new Ext.Button({
        renderTo : 'mainPageButtonDiv',
        text     : 'Main Page',
        onClick  : mainPage,
        tabIndex : 5
    });

    viewSummary2Button = new Ext.Button({
        renderTo : 'viewSummary2ButtonDiv',
        text     : 'View Summary',
        onClick  : viewSummary2,
        tabIndex : 6
    });

    var scores = [[1,'0'],[2,'80'],[3,'90'],[4,'100'],[5,'110'],[6,'120'],[7,'130'],[8,'140'],[9,'150'],
                 [10,'160'],[11,'170'],[12,'180'],[13,'190'],[14,'200'],[15,'210'],[16,'220'],[17,'230']];

    var combo1store = new Ext.data.SimpleStore({
        fields :
        [
           {name : 'Id'},
           {name : 'Score'}
        ]
    });

    edit_team1_score_A = new Ext.form.TextField({
            name     : 'editTeam1ScoreA',
            renderTo : 'editTeam1ScoreADiv',
            id       : 'editTeam1ScoreA',
            width    : 50
    });
    edit_team1_score_A.getEl().dom.maxLength = '3';

    edit_team1_score_B = new Ext.form.TextField({
            name     : 'editTeam1ScoreB',
            renderTo : 'editTeam1ScoreBDiv',
            id       : 'editTeam1ScoreB',
            width    : 50
    });
    edit_team1_score_B.getEl().dom.maxLength = '3';

    edit_team2_score_A = new Ext.form.TextField({
            name     : 'editTeam2ScoreA',
            renderTo : 'editTeam2ScoreADiv',
            id       : 'editTeam2ScoreA',
            width    : 50
    });
    edit_team2_score_A.getEl().dom.maxLength = '3';

    edit_team2_score_B = new Ext.form.TextField({
            name     : 'editTeam2ScoreB',
            renderTo : 'editTeam2ScoreBDiv',
            id       : 'editTeam2ScoreB',
            width    : 50
    });
    edit_team2_score_B.getEl().dom.maxLength = '3';

    edit_team1_TotalScore = new Ext.form.TextField({
            name     : 'editTeam1TotalScore',
            renderTo : 'editTeam1TotalScoreDiv',
            id       : 'editTeam1TotalScore',
            width    : 50,
            value    : <?php echo $current_subgame->Team1_Total; ?>
    });
    edit_team1_TotalScore.getEl().dom.maxLength = '3';

    edit_team2_TotalScore = new Ext.form.TextField({
            name     : 'editTeam2TotalScore',
            renderTo : 'editTeam2TotalScoreDiv',
            id       : 'editTeam2TotalScore',
            width    : 50,
            value    : <?php echo $current_subgame->Team2_Total; ?>
    });
    edit_team2_TotalScore.getEl().dom.maxLength = '3';

    team1ScoreCbx = new Ext.form.ComboBox({
        renderTo      : 'team1ScoreCbxDiv',
        store         : combo1store,
        width         : 135,
        mode          : 'local',
        triggerAction : 'all',
        editable      : false,
        valueField    : 'Score',
        displayField  : 'Score',
        tabIndex      : 1,
        name          : 'team1Score',
        id            : 'team1Score'
    });

    team2ScoreCbx = new Ext.form.ComboBox({
        renderTo      : 'team2ScoreCbxDiv',
        store         : combo1store,
        width         : 135,
        mode          : 'local',
        triggerAction : 'all',
        editable      : false,
        valueField    : 'Score',
        displayField  : 'Score',
        tabIndex      : 2,
        name          : 'team2Score',
        id            : 'team2Score'
    });


    team1ScoreCbx.store.on('load', function(){
        team1ScoreCbx.setValue(team1ScoreCbx.store.collect('Score', true)[0]);
    });

    team2ScoreCbx.store.on('load', function(){
        team2ScoreCbx.setValue(team2ScoreCbx.store.collect('Score', true)[0]);
    });

    combo1store.loadData(scores);
    setFocus();

    var editPlayers_data = new Ext.data.JsonReader({}, [ 'Player_ID', 'Name']);

    ds_editPlayers = new Ext.data.Store({
         proxy      : new Ext.data.HttpProxy({
             url : 'loadPlayers.php'
	 }),
	 reader     : editPlayers_data,
         remoteSort : false,
         autoLoad   : true
    });

    editPlayer11Cbx = new Ext.form.ComboBox({
	store          : ds_editPlayers,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'editPlayer11CbxDiv',
        id             : 'editPlayer11',
        name           : 'editPlayer11',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var p11_idx = editPlayer11Cbx.getValue();
                     var p12_idx = editPlayer12Cbx.getValue();
                     var p21_idx = editPlayer21Cbx.getValue();
                     var p22_idx = editPlayer22Cbx.getValue();

                     if(p11_idx != '') {
                         if (p11_idx == p12_idx || p11_idx == p21_idx || p11_idx == p22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){editPlayer11Cbx.focus(true);});
                     }
                 }
        }
    });

    editPlayer12Cbx = new Ext.form.ComboBox({
	store          : ds_editPlayers,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'editPlayer12CbxDiv',
        id             : 'editPlayer12',
        name           : 'editPlayer12',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var p11_idx = editPlayer11Cbx.getValue();
                     var p12_idx = editPlayer12Cbx.getValue();
                     var p21_idx = editPlayer21Cbx.getValue();
                     var p22_idx = editPlayer22Cbx.getValue();

                     if(p12_idx != '') {
                         if (p12_idx == p11_idx || p12_idx == p21_idx || p12_idx == p22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){editPlayer12Cbx.focus(true);});
                     }
                 }
        }
    });

    editPlayer21Cbx = new Ext.form.ComboBox({
	store          : ds_editPlayers,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'editPlayer21CbxDiv',
        id             : 'editPlayer21',
        name           : 'editPlayer21',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var p11_idx = editPlayer11Cbx.getValue();
                     var p12_idx = editPlayer12Cbx.getValue();
                     var p21_idx = editPlayer21Cbx.getValue();
                     var p22_idx = editPlayer22Cbx.getValue();

                     if(p21_idx != '') {
                         if (p21_idx == p11_idx || p21_idx == p12_idx || p21_idx == p22_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){editPlayer21Cbx.focus(true);});
                     }
                 }
        }
    });

    editPlayer22Cbx = new Ext.form.ComboBox({
	store          : ds_editPlayers,
	valueField     : 'Player_ID',
	displayField   : 'Name',
        editable       : false,
	mode           : 'remote',
        emptyText      : 'Select a player...',
        renderTo       : 'editPlayer22CbxDiv',
        id             : 'editPlayer22',
        name           : 'editPlayer22',
        forceSelection : true,
        triggerAction  : 'all',
        listeners      : {
   		 'select': function(){
                     var p11_idx = editPlayer11Cbx.getValue();
                     var p12_idx = editPlayer12Cbx.getValue();
                     var p21_idx = editPlayer21Cbx.getValue();
                     var p22_idx = editPlayer22Cbx.getValue();

                     if(p22_idx != '') {
                         if (p22_idx == p11_idx || p22_idx == p12_idx || p22_idx == p21_idx)
                            Ext.MessageBox.alert(' ','You must select a different player',function(){editPlayer22Cbx.focus(true);});
                     }
                 }
        }
    });

    editPlayersWin = new Ext.Window({
	    el          : 'editPlayers-win',
	    layout      : 'fit',
	    width       : 400,
	    height      : 230,
	    closeAction : 'hide',
	    plain       : true,
	    buttonAlign : 'center',
	    resizable   : false,
	    closable    : true,
	    modal       : true,
	    items       : new Ext.TabPanel({
                      el             : 'editPlayers-tab',
                      autoTabs       : true,
                      activeTab      : 0,
                      deferredRender : false,
                      border         : false,
                      baseCls        : 'backImage'
	    }),
            buttons     :
	    [
                  {text    : 'Submit',
                   handler : function(){
                       editPlayersNow();
                   }
                  },
                  {text    : 'Cancel',
                   handler : function(){
                       editPlayersWin.hide();
                   }
                  }
             ],
	     listeners  :
	     {
	   	 'hide' : function(){
                     Ext.get('editPlayer11').dom.value = 'Select a player...';
                     Ext.get('editPlayer12').dom.value = 'Select a player...';
                     Ext.get('editPlayer21').dom.value = 'Select a player...';
                     Ext.get('editPlayer22').dom.value = 'Select a player...';
	   	 },
                 'show' : function(){
                     editPlayer11Cbx.setValue(<?php echo $current_game->Team1_Player1_ID ?>);
                     editPlayer12Cbx.setValue(<?php echo $current_game->Team1_Player2_ID ?>);
                     editPlayer21Cbx.setValue(<?php echo $current_game->Team2_Player1_ID ?>);
                     editPlayer22Cbx.setValue(<?php echo $current_game->Team2_Player2_ID ?>);
	   	 }
	     }
    });

    editScoreWin = new Ext.Window({
	    el          : 'editScore-win',
	    layout      : 'fit',
	    width       : 500,
	    height      : 265,
	    closeAction : 'hide',
	    plain       : true,
	    buttonAlign : 'center',
	    resizable   : false,
	    closable    : true,
	    modal       : true,
	    items       : new Ext.TabPanel({
                      el             : 'editScore-tab',
                      autoTabs       : true,
                      activeTab      : 0,
                      deferredRender : false,
                      border         : false,
                      baseCls        : 'backImage'
	    }),
            buttons     :
	    [
                  {text    : 'Submit',
                   handler : function(){
                       editScores();
                   }
                  },
                  {text    : 'Cancel',
                   handler : function(){
                       editScoreWin.hide();
                   }
                  }
             ],
	     listeners  :
	     {
	   	 'hide' : function(){
                       Ext.get('editTeam1ScoreADiv').dom.value = '';
                       Ext.get('editTeam1ScoreBDiv').dom.value = '';
                       Ext.get('editTeam2ScoreADiv').dom.value = '';
                       Ext.get('editTeam2ScoreBDiv').dom.value = '';
                       Ext.get('editTeam1TotalScoreDiv').dom.value = '';
                       Ext.get('editTeam2TotalScoreDiv').dom.value = '';

	   	  },
	   	 'show' : function(){
                       this.setTitle('Change Score for Terh# ' + document.subGameForm.terhID.value);
                       edit_team1_score_A.setValue(document.subGameForm.t1ResultA.value);
                       edit_team1_score_B.setValue(document.subGameForm.t1ResultB.value);
                       edit_team2_score_A.setValue(document.subGameForm.t2ResultA.value);
                       edit_team2_score_B.setValue(document.subGameForm.t2ResultB.value);
	   	  }
	     }
    });

});

function setFocus(){
    team1ScoreCbx.focus(true);
}

function mainPage(){
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

function startNewTerh(){
    var team1score_idx = team1ScoreCbx.getValue();
    var team2score_idx = team2ScoreCbx.getValue();

    if(team1score_idx == 0 && team2score_idx == 0) {
        Ext.MessageBox.alert(' ','Both teams\' scores can\'t be 0',function(){team1ScoreCbx.focus(true);});
    } else if(team1score_idx > 0 && team2score_idx > 0) {
        Ext.MessageBox.alert(' ','Both teams can\'t score in the same Terh',function(){team1ScoreCbx.focus(true);});
    } else {
        document.subGameForm.submit();
    }
}

function viewSummary2(){
    window.open('tourneySummary.php');
}

function showEditPlayersWin(){
    editPlayersWin.show(this);
}

function editTerhScore(tid, t1res, t2res){
    var split1 = t1res.split("-");
    var split2 = t2res.split("-");

    document.subGameForm.terhID.value    = tid;
    document.subGameForm.t1ResultA.value = trim(split1[0]);
    document.subGameForm.t1ResultB.value = trim(split1[1]);
    document.subGameForm.t2ResultA.value = trim(split2[0]);
    document.subGameForm.t2ResultB.value = trim(split2[1]);

    editScoreWin.show(this);
}
function editScores() {
    var array_values = ['0','80','90','100','110','120','130','140','150','160','170','180','190','200','210','220','230'];

    var subgame_id = document.subGameForm.terhID.value;
    var game_id    = (<?php echo $curr_gameNumber; ?>);
    var tourney_id = (<?php echo $_SESSION['_currtourneyid']; ?>);

    var t1_a  = removeLeadingZeroes(trim(edit_team1_score_A.getValue()));
    var t1_b  = removeLeadingZeroes(trim(edit_team1_score_B.getValue()));
    var t2_a  = removeLeadingZeroes(trim(edit_team2_score_A.getValue()));
    var t2_b  = removeLeadingZeroes(trim(edit_team2_score_B.getValue()));
    var t1_ts = removeLeadingZeroes(trim(edit_team1_TotalScore.getValue()));
    var t2_ts = removeLeadingZeroes(trim(edit_team2_TotalScore.getValue()));

    if (isNaN(t1_a)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team1_score_A.focus(true);});
    } else if (isNaN(t1_b)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team1_score_B.focus(true);});
    } else if (isNaN(t2_a)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team2_score_A.focus(true);});
    } else if (isNaN(t2_b)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team2_score_B.focus(true);});
    } else if (isNaN(t1_ts)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team1_TotalScore.focus(true);});
    } else if (isNaN(t2_ts)) {
        Ext.MessageBox.alert(' ','You must enter a numeric value',function(){edit_team2_TotalScore.focus(true);});
    } else if (!isInArray(array_values,t1_a)) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team1_score_A.focus(true);});
    } else if (!isInArray(array_values,t2_a)) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team2_score_A.focus(true);});
    } else if (t1_a == 0 && t2_a == 0) {
        Ext.MessageBox.alert(' ','Both teams\' scores can\'t be 0',function(){edit_team1_score_A.focus(true);});
    } else if (t1_a > 0 && t2_a > 0) {
        Ext.MessageBox.alert(' ','Both teams can\'t score',function(){edit_team1_score_A.focus(true);});
} else if (t1_ts > 600 && t2_ts > 600) {
        Ext.MessageBox.alert(' ','Both teams\' total scores can\'t be over 600',function(){edit_team1_TotalScore.focus(true);});
    } else if (t1_ts == 0 && t2_ts == 0) {
        Ext.MessageBox.alert(' ','Both teams\' total scores can\'t be 0',function(){edit_team1_TotalScore.focus(true);});
    } else if ((t1_b != 0 && t1_b < 80) || (!isDivisibleBy10(t1_b))) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team1_score_B.focus(true);});
    } else if ((t2_b != 0 && t2_b < 80) || (!isDivisibleBy10(t2_b))) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team2_score_B.focus(true);});
    } else if ((t1_ts != 0 && t1_ts < 80) || (!isDivisibleBy10(t1_ts))) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team1_TotalScore.focus(true);});
    } else if ((t2_ts != 0 && t2_ts < 80) || (!isDivisibleBy10(t2_ts))) {
        Ext.MessageBox.alert(' ','You must enter a valid score',function(){edit_team2_TotalScore.focus(true);});
    } else if (t1_b > 600 && t2_b > 600) {
        Ext.MessageBox.alert(' ','Both scores can\'t be over 600',function(){edit_team1_score_A.focus(true);});
    } else {
        editScoresDB(game_id, tourney_id, subgame_id, t1_ts, t2_ts, t1_a, t1_b, t2_a, t2_b);
    }
}

function removeLeadingZeroes(str) {
    var str1 = str.replace(/^(0+)/g, '');
    if (str1 == '') {
        return '0';
    } else {
        return str1;
    }
}

function isDivisibleBy10(str) {
    return (str % 10 ==0);
}

function isInArray(array, search){
    return (array.indexOf(search) >= 0) ? true : false;
}

function editPlayersNow(){

    var editPlayer11_idx = editPlayer11Cbx.getValue();
    var editPlayer12_idx = editPlayer12Cbx.getValue();
    var editPlayer21_idx = editPlayer21Cbx.getValue();
    var editPlayer22_idx = editPlayer22Cbx.getValue();

    if(editPlayer11_idx != '' && editPlayer12_idx != '' && editPlayer21_idx != '' && editPlayer22_idx != '') {

        if (editPlayer11_idx == editPlayer12_idx || editPlayer11_idx == editPlayer21_idx || editPlayer11_idx == editPlayer22_idx ||
            editPlayer12_idx == editPlayer21_idx || editPlayer12_idx == editPlayer22_idx || editPlayer21_idx == editPlayer22_idx ) {
             Ext.MessageBox.alert(' ','You must select 4 different players',function(){editPlayer11Cbx.focus(true);});
        } else {
            updatePlayersDB(editPlayer11_idx, editPlayer12_idx, editPlayer21_idx, editPlayer22_idx, '<?php echo $curr_gameNumber; ?>', '<?php echo $_SESSION['_currtourneyid']; ?>');
        }
    } else {
        Ext.MessageBox.alert(' ','You must select 4 different players',function(){editPlayer11Cbx.focus(true);});
     }
}
function updatePlayersDB(p11, p12, p21, p22, gid, tid){

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
            editPlayersWin.hide();
	 } else {
   	    Ext.MessageBox.alert(' ','There was a problem editing players' + xmlhttp.statusText,function(){});
	 }
        }
    }

    if ( (<?php echo $current_subgame->Team1_Total; ?> == 0) && (<?php echo $current_subgame->Team2_Total; ?> == 0)) {
        document.subGameForm.initialRename.value='true';
        document.subGameForm.maxscore.value=0;
        document.subGameForm.tourneyid.value='<?php echo $_SESSION['_currtourneyid']; ?>';
    }
    document.subGameForm.submit();
    xmlhttp.open("GET","updateTeamPlayers.php?player1_1_id="+p11+"&player1_2_id="+p12+"&player2_1_id="+p21+"&player2_2_id="+p22+"&game_id="+gid+"&tourney_id="+tid,true);
    xmlhttp.send();
}

function editScoresDB(gid, tid, sid, tScore1, tScore2, t1_a, t1_b, t2_a, t2_b){

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
            editScoreWin.hide();
	 } else {
   	    Ext.MessageBox.alert(' ','There was a problem editing the terh score' + xmlhttp.statusText,function(){});
	 }
        }
    }
   //editScores.php?tScore1=100&tScore2=200&game_id=1&tourney_id=4&subgame_id=1&t1_a=90&t1_b=90&t2_a=0&t2_b=0
    document.subGameForm.submit();
    xmlhttp.open("GET","editScores.php?tScore1="+tScore1+"&tScore2="+tScore2+"&game_id="+gid+"&tourney_id="+tid+"&subgame_id="+sid+"&t1_a="+t1_a+"&t1_b="+t1_b+"&t2_a="+t2_a+"&t2_b="+t2_b,true);
    xmlhttp.send();
}

</script>

</HEAD>

<BODY bgcolor="black">
<br>
<?php if ($current_tourney != null) { ?>
<center>
    <h2><span class="extWhiteTitle3">
        <?php echo $current_tourney->Host; ?> - <?php echo $current_tourney->Location; ?> - <?php echo $current_tourney->Date; ?>
    </span></h2>
</center>
<br>
<form method="post" NAME="subGameForm" action="startSubGames.php" onsubmit="return false;">

    <input name="initialRename" id="initialRename" type="hidden" value="">
    <input name="maxscore"      id="maxscore"      type="hidden" value="">
    <input name="tourneyid"     id="tourneyid"     type="hidden" value="">
    <input name="terhID"        id="terhID"        type="hidden" value="">
    <input name="t1ResultA"     id="t1ResultA"     type="hidden" value="">
    <input name="t1ResultB"     id="t1ResultB"     type="hidden" value="">
    <input name="t2ResultA"     id="t2ResultA"     type="hidden" value="">
    <input name="t2ResultB"     id="t2ResultB"     type="hidden" value="">

<span class="extWhiteBoldLabel22">&nbsp;&nbsp;&nbsp;&nbsp;<u><b>Game # <?php echo $curr_gameNumber; ?></b></u>
&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
<?php
    if ($new_subgame_count == 0) {
?>
    <u><b>Terh &nbsp;# 1</b></u>
<?php
    } else {
?>
    <u><b>Terh &nbsp;# <?php echo $new_subgame_count + 1; ?></b></u>
<?php
    }
?>
</span>
<br>  <br>
<table cellspacing="1">
	<tr>
		<td>
			<table cellspacing="15">
				<tr>
					<td class="extWhiteBoldLabel22">
								<i><b><?php echo $current_subgame->P11_Name; ?>  &  <?php echo $current_subgame->P12_Name; ?> :</b></i>
					</td>
					<td>
						<div id="team1ScoreCbxDiv" name="team1ScoreCbxDiv"></div>
					</td>
					<td class="extWhiteBoldLabel22">
						<?php echo $current_subgame->Team1_Total; ?>
									<?php if ($current_subgame->Team1_Total > 499) { ?>
										<font color="red">&nbsp;&nbsp;Ding! Ding! Ding!</font>
										<embed src="sound/hornsound.wav" hidden="true" autostart="true" loop="false"/>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="extWhiteBoldLabel22">
								<i><b><?php echo $current_subgame->P21_Name; ?>  &  <?php echo $current_subgame->P22_Name; ?> :</b></i>
					</td>
					<td>
						<div id="team2ScoreCbxDiv" name="team2ScoreCbxDiv"></div>
					</td>
					<td class="extWhiteBoldLabel22">
									<?php echo $current_subgame->Team2_Total; ?>
									<?php if ($current_subgame->Team2_Total > 499) { ?>
										<font color="red">&nbsp;&nbsp;Ding! Ding! Ding!</font>
										<embed src="sound/hornsound.wav" hidden="true" autostart="true" loop="false"/>
						<?php } ?>
					</td>
				</tr>
			</table>
		</td>
		<td width="400">
		</td>

		<td width="500"align="center" valign="middle">
			<img src="images/carta.png" border="0">
		</td>
	</tr>
</table>

<table cellspacing="15">
	<tr>
            <td align="right" width="160">
                <div id="startNewTerhButtonDiv"/>
            </td>
			<td align="right" width="200">
                <div id="editPlayersButtonDiv"/>
            </td>
			<td width="300">
			</td>
            <td align="center" width="400">
                <div id="mainPageButtonDiv"/>
            </td>
            <td align="left" width="120">
                <div id="viewSummary2ButtonDiv"/>
            </td>
	</tr>
</table>
<?php } ?>

<div id="editPlayers-win" class="x-hidden">
	<div id="editPlayers-tab">
		<div id="editPlayersTab" class="x-tab" title="Edit Team Players">
                    <form>
                    <TABLE align="center">
                        <TBODY>
                            <TR>
                                <TD>
                                    <TABLE align="center" cellspacing="10">
                                        <TBODY>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>Team1/Player1:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editPlayer11CbxDiv" name="editPlayer11CbxDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>Team1/Player2:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editPlayer12CbxDiv" name="editPlayer12CbxDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>Team2/Player1:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editPlayer21CbxDiv" name="editPlayer21CbxDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2"><b>Team2/Player2:</b>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editPlayer22CbxDiv" name="editPlayer22CbxDiv" class="extLabel"></div>
                                                </TD>
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

<div id="editScore-win" class="x-hidden">
	<div id="editScore-tab">
		<div id="editScoreTab" class="x-tab" title="Change Score">
                    <form>
                    <TABLE align="center">
                        <TBODY>
                            <TR>
                                <TD>
                                    <TABLE align="center" cellspacing="10">
                                        <TBODY>
                                            <TR>
                                                <TD class="extBoldLabel2" align="left">
                                                    <SPAN id="Team1Name"><i><?php echo $current_subgame->P11_Name; ?> & <?php echo $current_subgame->P12_Name; ?></i> :</SPAN>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editTeam1ScoreADiv" name="editTeam1ScoreADiv" class="extLabel"></div>
                                                </TD>
                                                <TD>-</TD>
                                                <TD align="left">
                                                    <div id="editTeam1ScoreBDiv" name="editTeam1ScoreBDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2" align="left">
                                                    <SPAN id="Team2Name"><i><?php echo $current_subgame->P21_Name; ?> & <?php echo $current_subgame->P22_Name; ?></i> :</SPAN>
                                                </TD>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editTeam2ScoreADiv" name="editTeam2ScoreADiv" class="extLabel"></div>
                                                </TD>
                                                <TD>-</TD>
                                                <TD align="left">
                                                    <div id="editTeam2ScoreBDiv" name="editTeam2ScoreBDiv" class="extLabel"></div>
                                                </TD>
                                            </TR>
                                            <TR></TR>
                                            <TR></TR>
                                            <TR></TR>
                                            <TR>
                                                <TD class="extBoldLabel2" align="left">
                                                    <SPAN id="Team1NameBis"><i><?php echo $current_subgame->P11_Name; ?> & <?php echo $current_subgame->P12_Name; ?></i> Total Score:</SPAN>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editTeam1TotalScoreDiv" name="editTeam1TotalScoreDiv" class="extLabel"></div>
                                                </TD>
                                                <TD></TD>
                                                <TD></TD>
                                            </TR>
                                            <TR>
                                                <TD class="extBoldLabel2" align="left">
                                                    <SPAN id="Team2NameBis"><i><?php echo $current_subgame->P21_Name; ?> & <?php echo $current_subgame->P22_Name; ?></i> Total Score:</SPAN>
                                                </TD>
                                                <TD align="left">
                                                    <div id="editTeam2TotalScoreDiv" name="editTeam2TotalScoreDiv" class="extLabel"></div>
                                                </TD>
                                                <TD></TD>
                                                <TD></TD>
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

<?php
if ($new_subgame_count > 0) {
?>

<table border="1" align="center" bordercolor="white" cellspacing="0">
    <tr>
        <td width="80" align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
            <i>Terh #</i>
        </td>
        <td width="200" align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
            <i><?php echo $current_subgame->P11_Name; ?>  &  <?php echo $current_subgame->P12_Name; ?></i>
        </td>
        <td width="200" align="center" class="extWhiteBoldLabel3" bgcolor="#E0E0E0">
            <i><?php echo $current_subgame->P21_Name; ?>  &  <?php echo $current_subgame->P22_Name; ?></i>
        </td>
    </tr>
</table>

<?php
	for ($i = 0; $i < $new_subgame_count; $i++)
        {
?>

<table border="1" align="center" bordercolor="white" cellspacing="0">
    <tr>
        <td width="80" align="center" class="extWhiteBoldLabel22">
            <a class="edt" href="#" onClick="javascript:editTerhScore(<?php echo $subgame_info[$i]->SubGame_ID; ?>, '<?php echo $subgame_info[$i]->Team1ScoreResult; ?>' , '<?php echo $subgame_info[$i]->Team2ScoreResult; ?>');">
            <?php echo $subgame_info[$i]->SubGame_ID; ?>
            </a>
        </td>
        <?php if (($current_subgame->Team1_Total > 499) && ($i == $new_subgame_count - 1)) { ?>
        <td align="center" width="200" bgcolor="red" class="extWhiteBoldLabel22">
            <?php echo $subgame_info[$i]->Team1ScoreResult; ?>
        </td>
        <?php } else { ?>
        <td align="center" width="200" class="extWhiteBoldLabel22">
            <?php echo $subgame_info[$i]->Team1ScoreResult; ?>
        </td>
        <?php } ?>
        <?php if (($current_subgame->Team2_Total > 499) && ($i == $new_subgame_count - 1)) { ?>
        <td align="center" width="200" bgcolor="red" class="extWhiteBoldLabel22">
            <?php echo $subgame_info[$i]->Team2ScoreResult; ?>
        </td>
        <?php } else { ?>
        <td align="center" width="200" class="extWhiteBoldLabel22">
            <?php echo $subgame_info[$i]->Team2ScoreResult; ?>
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
