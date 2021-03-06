<?php
    function escapeSingleQuote($str){
        return str_replace("'", "\'",$str);
    }

    //Connect To Database
    function getdbConnection()
    {
        //local
        //$hostname="localhost";
        //$username="imad";
        //$password="Lass2010";
        //$dbname="mufts";

        //production
        //$hostname="10.130.10.212";
	$hostname=getenv("MYSQL_SERVICE_HOST");   
        $username="adminrTnXRyu";
        $password="NZYW89EZgNdl";
        $dbname="mufts";

        //$conn = mysql_connect($hostname,$username, $password) OR DIE ("Unable to connect to database!! Please try again later.");
        $mysqli = new mysqli($hostname, $username, $password, $dbname);
		if ($mysqli->connect_errno) {
				echo "Unable to connect to database!! Please try again later.";
			    exit;
		}
        //mysql_select_db($dbname, $conn);
        return $mysqli;
    }

    function addTourney($host, $location, $date, $time){
        $sql_str = "INSERT INTO mufts.tourney (
                                               Tourney_ID,
                                               Host,
                                               Location,
                                               Date,
                                               Time
                                               )
                                               VALUES
                                               (
                                               NULL,
                                               '$host',
                                               '$location',
                                               '$date',
                                               '$time')";

        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function addPlayer($firstname, $lastname){
        $sql_str = "INSERT INTO mufts.player (
                                             Player_ID,
                                             First_Name,
                                             Last_Name
                                             )
                                             VALUES
                                             (
                                             NULL,
                                             '$firstname',
                                             '$lastname')";

        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function addGame($gameNumber, $tourneyId, $player11_id, $player12_id, $player21_id, $player22_id){
        $sql_str = "INSERT INTO mufts.game (
                                            Game_ID,
                                            Tourney_ID,
                                            Team1_Player1_ID,
                                            Team1_Player2_ID,
                                            Team2_Player1_ID,
                                            Team2_Player2_ID,
                                            Team1_Total,
                                            Team2_Total
                                            )
                                            VALUES
                                            (
                                            '$gameNumber',
                                            '$tourneyId',
                                            '$player11_id',
                                            '$player12_id',
                                            '$player21_id',
                                            '$player22_id',
                                            0,
                                            0)";

        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function addSubGame($subGameID, $gameID, $tourneyID, $team1score, $team2score, $team1result, $team2result){
        $sql_str = "INSERT INTO mufts.subgame (
                                               SubGame_ID,
                                               Game_ID,
                                               Tourney_ID,
                                               Team1Score,
                                               Team2Score,
                                               Team1ScoreResult,
                                               Team2ScoreResult
                                               )
                                               VALUES
                                              (
                                              '$subGameID',
                                              '$gameID',
                                              '$tourneyID',
                                              '$team1score',
                                              '$team2score',
                                              '$team1result',
                                              '$team2result'
                                              )";

        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function addToTourneyWins($playerID, $tourneyID){
        $sql_str = "INSERT INTO mufts.tourney_wins (
                                                    Player_ID,
                                                    Tourney_ID,
                                                    Wins,
                                                    Games_Played
                                                    )
                                                    VALUES
                                                    (
                                                    '$playerID',
                                                    '$tourneyID',
                                                    0,
                                                    0
                                                    )";
        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function updateTourneyWins($playerID, $tourneyID, $i){
        $sql_str = "Update mufts.tourney_wins
                    SET    Wins = Wins + '$i',
                           Games_Played = Games_Played + 1
                    WHERE  Player_ID  = '$playerID'
                    AND    Tourney_ID = '$tourneyID'";
        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function getAllPlayers(){
        $sql_str = "SELECT Player_ID, CONCAT(First_Name, ' ' , Last_Name) as Name
                    FROM mufts.player
                    ORDER BY Player_ID ASC";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function updateGameScores($gamenumber, $tourneynumber, $team1totalscore, $team2totalscore){
        $sql_str = "Update mufts.game
                    SET    Team1_Total = '$team1totalscore',
                           Team2_Total = '$team2totalscore'
                    WHERE  Game_ID     = '$gamenumber'
                    AND    Tourney_ID  = '$tourneynumber'";
        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function getCurrentTourneyNumber(){
        $sql_str = "SELECT MAX(Tourney_ID) AS ID
                    FROM mufts.tourney";

        $conn = getdbConnection();
        //$result = mysql_query($sql_str , $conn);
        $result = $conn->query($sql_str);

        if ($result != null)
        {
                //$row = mysql_fetch_array($result);
                $row = mysqli_fetch_array($result);
                if ( $row )
                {
                        $number = $row['ID'];
                }
        }
        return $number;
    }

    function getPlayerID($fname, $lname){
        $sql_str = "SELECT player_ID
                    FROM   mufts.player
                    WHERE  First_Name = '$fname'
                    AND    Last_Name  = '$lname'";
        $conn = getdbConnection();
        //$result = mysql_query($sql_str , $conn);
        $result = $conn->query($sql_str);

        if ($result != null)
        {
                //$row = mysql_fetch_array($result);
                $row = mysqli_fetch_array($result);
                if ( $row )
                {
                        $number = $row['Player_ID'];
                }
        }
        return $number;
    }

    function getTourneys(){
        $sql_str = "SELECT *
                    FROM mufts.tourney
                    ORDER BY Tourney_ID ASC";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getTourneysCbx(){
        $sql_str = "SELECT Tourney_ID, CONCAT(Host, ' - ', Location, ' - ', Date) AS Tourney_Desc
                    FROM mufts.tourney
                    ORDER BY Tourney_ID ASC";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getTourneyByInfoID($tourneyID){
        $sql_str = "SELECT *
                    FROM  mufts.tourney
                    WHERE Tourney_ID = '$tourneyID'";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getTourneySummaryByGameID($tourneyID, $gameID){
        $sql_str = "SELECT G.Game_ID, S.SubGame_ID, G.Team1_Total, G.Team2_Total, S.Team1ScoreResult, S.Team2ScoreResult,
                           P1.First_Name AS P11_Name, P2.First_Name AS P12_Name, P3.First_Name AS P21_Name, P4.First_Name AS P22_Name
                    FROM  mufts.game G, mufts.subgame S, mufts.player P1, mufts.player P2, mufts.player P3, mufts.player P4
                    WHERE G.Tourney_ID = '$tourneyID'
                    AND   S.Game_ID    = '$gameID'
                    AND   G.Game_ID    = S.Game_ID
                    AND   S.Tourney_ID = G.Tourney_ID
                    AND   G.Team1_Player1_ID = P1.Player_ID
                    AND   G.Team1_Player2_ID = P2.Player_ID
                    AND   G.Team2_Player1_ID = P3.Player_ID
                    AND   G.Team2_Player2_ID = P4.Player_ID
                    ORDER BY S.SubGame_ID";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getGameWinnersByTourney($tourneyID){
        $sql_str = "SELECT G.Game_ID, G.Team1_Total, G.Team2_Total, P1.First_Name AS P11_Name, P2.First_Name AS P12_Name, P3.First_Name AS P21_Name, P4.First_Name AS P22_Name
                    FROM  mufts.game G, mufts.player P1, mufts.player P2, mufts.player P3, mufts.player P4
                    WHERE G.Tourney_ID = '$tourneyID'
                    AND   G.Team1_Player1_ID = P1.Player_ID
                    AND   G.Team1_Player2_ID = P2.Player_ID
                    AND   G.Team2_Player1_ID = P3.Player_ID
                    AND   G.Team2_Player2_ID = P4.Player_ID
                    ORDER BY G.Game_ID";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }


    function getGameInfo($tourneyID, $gameID){
        $sql_str = "SELECT G.Game_ID, G.Team1_Player1_ID, G.Team1_Player2_ID, G.Team2_Player1_ID, G.Team2_Player2_ID, G.Team1_Total, G.Team2_Total,
                    P1.First_Name AS P11_Name, P2.First_Name AS P12_Name, P3.First_Name AS P21_Name, P4.First_Name AS P22_Name
                    FROM  mufts.game G, mufts.player P1, mufts.player P2, mufts.player P3, mufts.player P4
                    WHERE G.Tourney_ID = '$tourneyID'
                    AND   G.Game_ID    = '$gameID'
                    AND   G.Team1_Player1_ID = P1.Player_ID
                    AND   G.Team1_Player2_ID = P2.Player_ID
                    AND   G.Team2_Player1_ID = P3.Player_ID
                    AND   G.Team2_Player2_ID = P4.Player_ID";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getGamesCountByTourney($tourneyID){
        $sql_str = "SELECT COUNT(*) AS COUNT
                    FROM  mufts.game
                    WHERE Tourney_ID = '$tourneyID'";
        $conn = getdbConnection();
        //$result = mysql_query($sql_str , $conn);
        $result = $conn->query($sql_str);

        if ($result != null)
        {
                //$row = mysql_fetch_array($result);
                $row = mysqli_fetch_array($result);
                if ( $row )
                {
                        $count = $row['COUNT'];
                }
        }
        return $count;
    }

    function getSubGamesCountByGame($tourneyID, $gameID){
        $sql_str = "SELECT COUNT(*) AS COUNT
                    FROM  mufts.subgame
                    WHERE Tourney_ID = '$tourneyID'
                    AND   Game_ID    = '$gameID'";
        $conn = getdbConnection();
        //$result = mysql_query($sql_str , $conn);
        $result = $conn->query($sql_str);

        if ($result != null)
        {
                //$row = mysql_fetch_array($result);
                $row = mysqli_fetch_array($result);
                if ( $row )
                {
                        $count = $row['COUNT'];
                }
        }
        return $count;
    }

    function getPlayers(){
        $sql_str = "SELECT *
                    FROM mufts.player";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getRankings(){
        $sql_str = "SELECT T.Player_ID, P.First_Name,P.Last_Name, SUM(T.Wins) AS Wins, SUM(T.Games_Played) AS Played
                    FROM  mufts.tourney_wins T, mufts.player P
                    WHERE P.Player_ID = T.Player_ID
                    Group by T.Player_ID
                    ORDER BY Wins DESC";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function deleteTourneyInfo($tourneyID){
        $conn = getdbConnection();

        $sql_str = "DELETE
                    FROM  mufts.tourney_wins
                    WHERE Tourney_ID = '$tourneyID'";
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);

        $sql_str = "DELETE
                    FROM  mufts.subgame
                    WHERE Tourney_ID = '$tourneyID'";
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);

        $sql_str = "DELETE
                    FROM  mufts.game
                    WHERE Tourney_ID = '$tourneyID'";
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);

        $sql_str = "DELETE
                    FROM  mufts.tourney
                    WHERE Tourney_ID = '$tourneyID'";
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function getWinsByTourney($tourneyID){
        $sql_str = "SELECT T.Wins, T.Games_Played, P.First_Name, P.Last_Name
                    FROM  mufts.tourney_wins T, mufts.player P
                    WHERE T.Tourney_ID = '$tourneyID'
                    AND   T.Player_ID  = P.Player_ID
                    ORDER BY T.Wins DESC";
        $conn = getdbConnection();
        //return $result = mysql_query($sql_str , $conn);
        return $conn->query($sql_str);
    }

    function getMaxGameScores($tourneyID){
        $sql_str = "SELECT CASE
                               WHEN Team1_Total >= Team2_Total THEN Team1_Total
                               ELSE Team2_Total
                           END AS MAXSCORE
                    FROM   mufts.game
                    WHERE  Tourney_ID = '$tourneyID'
                    AND       Game_ID = (SELECT MAX(Game_ID)
                                         FROM   mufts.game
                                         WHERE  Tourney_ID = '$tourneyID')";
        $conn = getdbConnection();
        //$result = mysql_query($sql_str , $conn);
        $result = $conn->query($sql_str);
        if ($result != null)
        {
            //$row = mysql_fetch_array($result);
            $row = mysqli_fetch_array($result);
            if ( $row )
            {
                return $row['MAXSCORE'];
            } else {
                return 1000; //No games
            }
        }
    }

    function updatePlayers($tourney_id, $game_id, $player1_1_id, $player1_2_id, $player2_1_id, $player2_2_id) {
        $sql_str = "Update mufts.game
                    SET    Team1_Player1_ID = $player1_1_id,
                           Team1_Player2_ID = $player1_2_id,
                           Team2_Player1_ID = $player2_1_id,
                           Team2_Player2_ID = $player2_2_id
                    WHERE  Game_ID  = '$game_id'
                    AND    Tourney_ID = '$tourney_id'";
        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }

    function updateSubgame($subgame_id, $game_id, $tourney_id, $team1_score, $team2_score, $team1_score_result, $team2_score_result) {
        $sql_str = "Update mufts.subgame
                    SET    Team1Score       = $team1_score,
                           Team2Score       = $team2_score,
                           Team1ScoreResult = '$team1_score_result',
                           Team2ScoreResult = '$team2_score_result'
                    WHERE  SubGame_ID = '$subgame_id'
                    AND    Tourney_ID = '$tourney_id'
                    AND    Game_ID    = '$game_id'";

        $conn = getdbConnection();
        //mysql_query($sql_str , $conn);
        $conn->query($sql_str);
    }
?>
