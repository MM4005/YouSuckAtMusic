<?php
session_start();
require_once '../functions.php';
if(isset($_SESSION['logged_in']) and $_SESSION['logged_in'] === true){
    $offset = 0;
    if (isset($_GET['offset'])){
        $offset = (int)$_GET['offset'];
    }
    echo json_encode(get_user_saved_ratings($_SESSION['access_token'], $offset));
}
else
{
    http_response_code(401);
}