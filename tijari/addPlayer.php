<?php
    include('db_query.php');
    $fname     = $_GET["fname"];
    $lname     = $_GET["lname"];
    $tourneyid = $_GET["tourneyid"];
    
    addPlayer(ucfirst(strtolower($fname)), ucfirst(strtolower($lname)));
    
    $playerid = getPlayerID($fname, $lname);
    addToTourneyWins($playerid, $tourneyid);
?>