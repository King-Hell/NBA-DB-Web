<?php
echo "<?xml version = \"1.0\" encoding = \"UTF-8\"?>";
echo "<list>";
$mysqli = new mysqli("localhost", "visitor", "1234", "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
$type = $_GET['type'];
switch ($type) {
    case 'player_list':
        $index = $_GET['index'];
        $result = $mysqli->query("select * from player_list where english_name regexp '^[:alpha:]+ $index'");
        while ($row = $result->fetch_row()) {
            echo "<player>";
            echo "<id>$row[0]</id>";
            echo "<name>$row[1]</name>";
            echo "<english_name>$row[2]</english_name>";
            echo "<team_name>$row[3]</team_name>";
            echo "<team_id>$row[4]</team_id>";
            echo "</player>";
        }
        break;
    case 'game_list':
        $date = $_GET['date'];
        $result = $mysqli->query("select * from game_list where date='$date'");
        while ($row = $result->fetch_row()) {
            echo "<game>";
            echo "<id>$row[0]</id>";
            echo "<date>$row[1]</date>";
            echo "<away>$row[2]</away>";
            echo "<home>$row[3]</home>";
            echo "<away_id>$row[4]</away_id>";
            echo "<home_id>$row[5]</home_id>";
            echo "<away_score>$row[6]</away_score>";
            echo "<home_score>$row[7]</home_score>";
            echo "</game>";
        }
        break;
}
echo "</list>";


