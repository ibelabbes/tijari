<?php
    include('db_query.php');

    $tourneyid = $_GET["tourneyid"];
    
    deleteTourneyInfo($tourneyid);
?>