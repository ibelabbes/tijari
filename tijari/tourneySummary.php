<?php
try{
    $pageName = "tourneySummary";
    include('tijariHeader.php');
    include('db_query.php');
    if (isset($_SESSION['_pageName'])) {
        $page_name_sess = $_SESSION['_pageName'];
    }
    $index = "index";
    $startGame = "startGame";
    $startSubGames = "startSubGames";
    $backFlag = false;
    if (isset($_SESSION['_pageName'])) {
        if (strcmp($page_name_sess, $index) == 0) {
            $backFlag = true;
            $tourneyid = $_POST['tourneyid'];

            $rs_tourneyByID = getTourneyByInfoID($tourneyid);
            while($obj1 = mysql_fetch_object($rs_tourneyByID))
            {
                    $tourney_info[] = $obj1;
            }

            $game_count = getGamesCountByTourney($tourneyid);
        } else if ((strcmp($page_name_sess, $startGame) == 0) || (strcmp($page_name_sess, $startSubGames) == 0)) {
            $tourneyid = $_SESSION['_currtourneyid'];

            $rs_tourneyByID = getTourneyByInfoID($tourneyid);
            while($obj1 = mysql_fetch_object($rs_tourneyByID))
            {
                    $tourney_info[] = $obj1;
            }

            $game_count = getGamesCountByTourney($tourneyid);
        }
    }else {
        $tourney_info = null;
        $game_count = 0;

        if (isset($_GET["tid"])) {
            $backFlag = true;
            $tourneyid = $_GET["tid"];

            $rs_tourneyByID = getTourneyByInfoID($tourneyid);

            while($obj1 = mysql_fetch_object($rs_tourneyByID))
            {
                    $tourney_info[] = $obj1;
            }

            $game_count = getGamesCountByTourney($tourneyid);
        }
    }
?>

<html>
<title>Tijari Championship - Summary</title>
<head>
<?php
    include('metaTags.php');
?>
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

</head>
<body bgcolor="black">
<form name="myForm" id="myForm" method="post" action="" onsubmit="return false;">
<table border="0" cellspacing="10" valign="middle" height="635" align="center" width="945" id="pageTable">
    <tbody>
        <tr>
            <td align="center" valign="middle"><img src="images/carta.png" border="0"></td>
            <td align="center" valign="middle"><span class="extWhiteTitle3">Tijari Championship</span> </td>
            <td align="center" valign="middle"><img src="images/carta.png" border="0"></td>
        </tr>
	<tr>
            <td></td>
            <td align="center" class="extWhiteBoldLabel2">
                <?php if (count($tourney_info) != 0) { ?>
                    <i>Tourney </i>:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tourney_info[0]->Host; ?> (<?php echo $tourney_info[0]->Location; ?>) <?php echo $tourney_info[0]->Date; ?>
                <?php } ?>
            </td>
            <?php if ($backFlag) { ?>
            <td ><a href='index.php' class="extWhiteLabel17">Back to Standings</a></td>
            <?php } else { ?>
            <td ><a href='javascript:document.location.reload();' class="extWhiteLabel17">Refresh</a></td>
            <?php } ?>
        </tr>
<?php
    $rs_wins = getWinsByTourney($tourneyid);
    $wins_info = null;
    while($obj0 = mysql_fetch_object($rs_wins))
    {
            $wins_info[] = $obj0;
    }
    if (count($wins_info) != 0)
    {
?>
            <tr>
            <td align="center" valign="middle">
            </td>
            <td align="center" valign="middle">
                <table cellspacing="0" align="center" valign="middle" border="1px" bordercolor="white">
                    <tr>
                        <td align="center" class="extWhiteBoldLabel22" style="width:200;">Player</td>
                        <td align="center" class="extWhiteBoldLabel22" align="left" style="width:100;">Won</td>
                        <td align="center" class="extWhiteBoldLabel22" style="width:150;">Played</td>
                    </tr>
<?php
        for($i=0; $i<count($wins_info); $i++)
        {
?>
                    <tr>
                        <td align="center" class="extWhiteLabel17" style="width:200;">
                            <?php echo $wins_info[$i]->First_Name; ?> <?php echo $wins_info[$i]->Last_Name; ?>
                        </td>
                        <td align="center" class="extWhiteLabel17" align="left" style="width:100;">
                            <?php echo $wins_info[$i]->Wins; ?>
                        </td>
                        <td align="center" class="extWhiteLabel17" style="width:100;">
                            <?php echo $wins_info[$i]->Games_Played; ?>
                        </td>
                    </tr>
<?php
        }
    }
?>
                </table>

<?php
    for($j=1; $j<=$game_count; $j++)
    {
        $rs_tourneySummary = getTourneySummaryByGameID($tourneyid,$j);
        $tourneySummary = null;
        while($obj2 = mysql_fetch_object($rs_tourneySummary))
        {
            $tourneySummary[] = $obj2;
        }
        if (count($tourneySummary) != 0)
    {
?>
  	<tr>
            <td align="center" valign="middle">
            </td>
            <td align="center" valign="bottom" class="extWhiteBoldLabel2">
                <b>Game <?php echo $j ?></b>
            </td>
            <td align="center" valign="middle">
            </td>
        </tr>
        <tr>
            <td align="right" valign="middle" class="extWhiteLabel">
            </td>
            <td align="left" valign="top">
            <table cellspacing="0" align="center" valign="middle" border="1px" bordercolor="white">
                <tr>
                    <td align="center" class="extWhiteBoldLabel2" style="width:150;">
                        <b><?php echo $tourneySummary[0]->P11_Name ?> & <?php echo $tourneySummary[0]->P12_Name ?></b>
                    </td>
                    <td align="center" class="extWhiteBoldLabel2" align="left" style="width:150;">
                        <b><?php echo $tourneySummary[0]->P21_Name ?> & <?php echo $tourneySummary[0]->P22_Name ?></b>
                    </td>
                </tr>
<?php
    for($k=0; $k<count($tourneySummary); $k++)
    {
        if($k != (count($tourneySummary) -1)) {
?>
                <tr>
                    <td align="center" class="extWhiteLabel17" style="width:150;">
                        <?php echo $tourneySummary[$k]->Team1ScoreResult ?>
                    </td>
                    <td align="center" class="extWhiteLabel17" style="width:150;">
                        <?php echo $tourneySummary[$k]->Team2ScoreResult ?>
                    </td>
                </tr>
<?php
        } else {
?>
                <tr>
                    <td align="center" class="extWhiteLabel17" style="width:150;;color:red;">
                        <b><?php echo $tourneySummary[$k]->Team1ScoreResult ?></b>
                    </td>
                    <td align="center" class="extWhiteLabel17" style="width:150;color:red;">
                        <b><?php echo $tourneySummary[$k]->Team2ScoreResult ?></b>
                    </td>
                </tr>
<?php
        }
    }
}
?>
            </table>
            </td>
            <td align="center" valign="middle">
            </td>
        </tr>
<?php
        unset($tourneySummary);
    }
?>
        <tr>
            <td align="center" valign="middle"><img src="images/carta.png" border="0"></td>
            <td></td>
            <td align="center" valign="middle"><img src="images/carta.png" border="0"></td>
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
