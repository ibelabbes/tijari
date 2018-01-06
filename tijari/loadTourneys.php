<?php
    include('db_query.php');

    $rs = getTourneysCbx();

    while($obj = mysql_fetch_object($rs))
    {
            $arr[] = $obj;
    }

    echo json_encode($arr);
?>
