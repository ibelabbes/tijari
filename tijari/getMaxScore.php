<?php
    include('db_query.php');
    $tourneyid=$_GET["tourneyid"];

    echo getMaxGameScores($tourneyid);
?>
