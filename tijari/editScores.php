<?php
    include('db_query.php');
    
    $t1_a       = $_GET["t1_a"];
    $t1_b       = $_GET["t1_b"];
    $t2_a       = $_GET["t2_a"];
    $t2_b       = $_GET["t2_b"];      
    $tScore1    = $_GET["tScore1"];
    $tScore2    = $_GET["tScore2"];
    $game_id    = $_GET["game_id"];
    $tourney_id = $_GET["tourney_id"];
    $subgame_id = $_GET["subgame_id"];
    
    $t1scoreresult = str_pad($t1_a, 3, "0", STR_PAD_LEFT) . " - " . str_pad($t1_b, 3, "0", STR_PAD_LEFT);
    $t2scoreresult = str_pad($t2_a, 3, "0", STR_PAD_LEFT) . " - " . str_pad($t2_b, 3, "0", STR_PAD_LEFT);
           
    updateGameScores($game_id, $tourney_id, $tScore1, $tScore2);
    updateSubgame($subgame_id, $game_id, $tourney_id, $t1_a, $t2_a, $t1scoreresult, $t2scoreresult)
?>