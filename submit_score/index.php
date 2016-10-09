<?php
session_start();
require_once '../functions.php';
ini_set('display_errors', 1);
echo $_SESSION['logged_in'];
if (isset($_SESSION['logged_in']) and
    $_SESSION['logged_in'] === true and
    isset($_SESSION['access_token']) and
    isset($_GET['score'])
) {
    $user_info = get_user_info($_SESSION['access_token']);
    $disp_name = $user_info['display_name'];
    if (strlen($disp_name) <= 0){
        $disp_name = null;
    }
    $link = $user_info['external_urls']['spotify'];
    $score = (double)$_GET['score'];
    $conn = new mysqli("localhost", "user", "pass", "table");
    $stmt = $conn->prepare("INSERT into leaderboard (`id`, `display_name`, `link`, `score`) values (?,?,?,?) ON DUPLICATE KEY UPDATE score=?");
    $stmt->bind_param("sssdd", $user_info['id'], $disp_name, $link, $score, $score);
    $stmt->execute();
    $conn->commit();
}