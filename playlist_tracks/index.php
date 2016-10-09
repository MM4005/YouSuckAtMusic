<?php
session_start();
require_once '../functions.php';
if(isset($_SESSION['logged_in']) and $_SESSION['logged_in'] === true){
    $offset = 0;
    if (isset($_GET['offset'])){
        $offset = (int)$_GET['offset'];
    }
    echo json_encode(get_ratings_from_tracks(get_tracks_from_playlist($_SESSION['access_token'], $_GET['playlist_id'], $_GET['owner_id'])));
}
else
{
    http_response_code(401);
}