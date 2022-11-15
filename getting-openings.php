<?php
require "config/config.php";

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->errno) {
    echo $mysqli->error;
    exit();
}

$mysqli->set_charset('utf8');

// This file will be used to get the openings from the backend and then feed them back into the front-end so that we can randomly display and process our chess openings to practice. 

$opening_sql = "
SELECT openings.name, move_id, move_num, fen_str FROM opening_moves
JOIN openings
	ON openings.id = opening_moves.openings_id;
";

$openings = $mysqli->query($opening_sql);
if (!$openings) {
    echo $mysqli->error;
    exit();
}

// Creating our openings array we're going to send to the front-end
$openings_array = [];

while ($row = $openings->fetch_assoc()) {
    array_push($openings_array, $row);
}

echo json_encode($openings_array);

$mysqli->close();