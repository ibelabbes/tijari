<?php
try{

    include('tijariHeader.php');
    $pageName = "index";
    include('db_query.php');

    $_SESSION['_pageName'] = $pageName;
    //Remove $y and code related to $GET admin
    $y = "y";

?>
<html>
<title>Tijari Championship - Welcome</title>
<?php
    //Includes all the common Meta Tags
    include('metaTags.php');
?>
<head>
<LINK href="css/tijari_stl.css" rel="stylesheet" type="text/css">
<link href="css/lightbox.css" rel="stylesheet" />
<style type="text/css">
	body {
	font-family: Arial, Verdana, Helvetica, sans-serif;
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a.hist:hover {color: red;}
         .thumbnailHidden{
            	padding: 4px;
                display: none;
        	}
         .thumbnail {
            	padding: 4px;
        	}
</style>
<script src="lightbox.js"></script>
<script>
        Ext.ux.Lightbox.register('a[rel^=lightbox]');
        Ext.ux.Lightbox.register('a.lb-flower', true); // true to show them as a set
</script>
<SCRIPT LANGUAGE="JavaScript">

var startTourneyButton;
var resumeTourneyButton;
var deleteTourneyButton;

Ext.onReady(function(){
<?php
    if (isset ($_GET["admin"])) {
        if (strcmp($_GET["admin"], $y) == 0) {
?>
    startTourneyButton = new Ext.Button({
            renderTo : 'startTourneyButtonDiv',
            text     : 'Start New Tourney',
            onClick  : startTourney
    });

    resumeTourneyButton = new Ext.Button({
            renderTo : 'resumeTourneyButtonDiv',
            text     : 'Resume Tourney',
            onClick  : resumeTourney
    });

    deleteTourneyButton = new Ext.Button({
            renderTo : 'deleteTourneyButtonDiv',
            text     : 'Delete Tourney',
            onClick  : deleteTourney
    });
<?php
        }
    }
?>
});


function startTourney(){
    window.location = "tourney.php";
}

function deleteTourney(){
    window.location= "resumeTourney.php";
}

function resumeTourney(){
    window.location= "resumeTourney.php";
}

function viewTourneySummary(id){
    document.myForm.tourneyid.value = id;
    document.myForm.submit();
    return false;
}
</script>
</head>

<?php
    $rs_tourneys = getTourneys();

    $tourney_info = null;
    //while($obj1 = mysql_fetch_object($rs_tourneys))
    while($obj1 = mysqli_fetch_object($rs_tourneys))
    {
        $tourney_info[] = $obj1;
    }

    $rs_players = getRankings();

    $player_info = null;
    //while($obj2 = mysql_fetch_object($rs_players))
    while($obj2 = mysqli_fetch_object($rs_players))
    {
        $player_info[] = $obj2;
    }
?>

<body bgcolor="black">
<form name="myForm" method="post" action="tourneySummary.php" onsubmit="return false;">
<input type="hidden" id="tourneyid" name="tourneyid" value="">
<input type="hidden" id="admin"     name="admin"     value="">
<table cellspacing="10" valign="middle" height="635" align="center" width="945" id="pageTable">
    <tbody>
        <tr>
            <td align="center" valign="middle"><img src="images/carta.png" border="0"></td>
            <td align="center" valign="middle"><span class="extWhiteTitle3">2019 Tijari Championship</span> </td>
            <td align="center" valign="middle"><img src="images/carta.png"></td>
        </tr>
        <tr>
            <td align="center" valign="middle">
            </td>
            <td align="center" valign="middle">
                <table cellspacing="0" align="center" valign="middle" border="1px" bordercolor="white">
<?php
    if (count($player_info) != 0)
    {
?>
                    <tr>
                        <td align="center" class="extWhiteBoldLabel22" style="width:60;">
                            <b>Rank</b>
			</td>
                        <td align="center" class="extWhiteBoldLabel22" style="width:200;">
                            <b>Player</b>
                        </td>
                        <td align="center" class="extWhiteBoldLabel22" align="left" style="width:100;">
                            <b>Wins</b>
                        </td>
                        <td align="center" class="extWhiteBoldLabel22" align="left" style="width:100;">
                            <b>Played</b>
                        </td>
                    </tr>
<?php
        for($j=0; $j<count($player_info); $j++)
        {
?>
                    <tr>
                        <td align="center" class="extWhiteLabel17" style="width:60;">
                            <?php echo $j + 1; ?>
                        </td>
                        <td align="center" class="extWhiteLabel17" style="width:200;">
                            <?php echo $player_info[$j]->First_Name; ?> <?php echo $player_info[$j]->Last_Name; ?>
                        </td>
                        <td align="center" class="extWhiteLabel17" align="left" style="width:100;">
                            <?php echo $player_info[$j]->Wins; ?>
                        </td>
                        <td align="center" class="extWhiteLabel17" align="left" style="width:100;">
                            <?php echo $player_info[$j]->Played; ?>
                        </td>
                    </tr>
<?php
        }
    }
?>
                </table>
            </td>
            <td align="center" valign="middle"></td>
        </tr>
	<tr>
            <td></td>
            <td align="center"></td>
            <td></td>
	</tr>
<?php
    for($i=0; $i<count($tourney_info); $i++)
    {
      $count = getGamesCountByTourney($tourney_info[$i]->Tourney_ID);
      if (($count == 0) && ($i == (count($tourney_info)-1))) {
?>
	<tr>
            <td></td>
            <td align="center" class="extWhiteLabel17">
                <b>Next Tourney :</b>
                <?php echo $tourney_info[$i]->Host; ?> (<?php echo $tourney_info[$i]->Location; ?>) <?php echo $tourney_info[$i]->Date; ?>, at <?php echo $tourney_info[$i]->Time; ?>
            </td>
            <td></td>
	</tr>
<?php
    } else {
?>
	<tr>
            <td></td>
            <td align="center" class="extWhiteLabel17">
                <a href="#" onclick="viewTourneySummary(<?php echo $tourney_info[$i]->Tourney_ID; ?>)" class="extWhiteLabel17">
                    <b>Tourney <?php echo $i + 1; ?> :</b>
                    <?php echo $tourney_info[$i]->Host; ?> (<?php echo $tourney_info[$i]->Location; ?>) <?php echo $tourney_info[$i]->Date; ?>, at <?php echo $tourney_info[$i]->Time; ?>
                </a>
            </td>
            <td></td>
	</tr>
<?php
    }}
?>
        <tr>
        </tr>
<?php   if (isset ($_GET["admin"])) {
            if (strcmp($_GET["admin"], $y) == 0) {
?>
	<tr>
            <td></td>
            <td>
                <table align="center" valign="middle">
                    <tr>
                        <td align="left" width="140">
                            <div id="resumeTourneyButtonDiv"/>
                        </td>
                        <td align="center" width="140">
                            <div id="startTourneyButtonDiv"/>
                        </td>
                        <td align="right" width="140">
                            <div id="deleteTourneyButtonDiv"/>
                        </td>
                    </tr>
                </table>
            </td>
            <td></td>
	</tr>
<?php
       }
  }
 ?>
        <tr>
            <td align="center" valign="middle">
                <img src="images/carta.png" border="0">
            </td>
            <td align="center" valign="middle" >
            <b class="extWhiteBoldLabel22 hist" style="text-decoration: none;">Previous Results</b><br>
               <b>
<a href="2011/2011results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;">** 2011 *</a>
<a href="2012/2012results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2012 *</a>
<a href="2013/2013results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2013 *</a>
<a href="2014/2014results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2014 *</a>
<a href="2015/2015results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2015 *</a>
<a href="2016/2016results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2016 *</a>
<a href="2017/2017results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2017 *</a>      
<a href="2018/2018results.htm" class="extWhiteBoldLabel22 hist" style="text-decoration: none;"> * 2018 **</a>
</b></td>
            <td align="center" valign="middle">
                <img src="images/carta.png" border="0">
            </td>
        </tr>
<tr>
<td></td>
<td align="center" valign="middle">
     	<div class="thumbnail" title="Meet the Tijari players">
        		<a href="images/driss.jpg" class="lb-flower" title="Driss Cherqi (2015 Champion)."><img src="images/tijari_players.jpg" alt="Meet theTijari players" width="347" height="35"></a>
        </div>
     	<div class="thumbnailHidden">
        		<a href="images/imad.jpg" class="lb-flower" title="Imad Belabbes (2011, 2013, 2017 & 2018 Champion)."><img src="images/imad.jpg" ></a>
        </div>
     	<div class="thumbnailHidden">
        		<a href="images/wadih.jpg" class="lb-flower" title="Wadih Bargach (2012 Champion)."><img src="images/wadih.jpg" ></a>
        		<a href="images/hicham.jpg" class="lb-flower" title="Hicham Benkhraba."><img src="images/hicham.jpg" ></a>
        		<a href="images/ali.jpg" class="lb-flower" title="Ali Ouahbi (2014 & 2016 Champion)."><img src="images/ali.jpg" ></a>
        		<a href="images/group_1.JPG" class="lb-flower" title="Group Picture. You can see Mounir."><img src="images/group_1.JPG" ></a>
        </div>
</td>
<td></td>
</tr>
    </tbody>
</table>


</form>
</body>
</html>
<?php
} catch(Exception $e) {
	header( "Location: index.php" );
}
?>
