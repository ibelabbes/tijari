<?php
    include('db_query.php');
    $player1_1_id = $_GET["player1_1_id"];
    $player1_2_id = $_GET["player1_2_id"];
    $player2_1_id = $_GET["player2_1_id"];
    $player2_2_id = $_GET["player2_2_id"];
    $game_id      = $_GET["game_id"];
    $tourney_id   = $_GET["tourney_id"];
    
    updatePlayers($tourney_id, $game_id, $player1_1_id, $player1_2_id, $player2_1_id, $player2_2_id);
?>