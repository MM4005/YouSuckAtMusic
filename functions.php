<?php
function get_user_info($access_token)
{
    $url = 'https://api.spotify.com/v1/me';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = [
        'Authorization: Bearer ' . $access_token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


//execute post
    $jsonresult = curl_exec($ch);
    $result = json_decode($jsonresult, 1);
//close connection
    curl_close($ch);
    return $result;
}

function get_user_saved_tracks($access_token, $offset = 0)
{
    $url = 'https://api.spotify.com/v1/me/tracks?limit=50&offset=' . $offset;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = [
        'Authorization: Bearer ' . $access_token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


//execute post
    $jsonresult = curl_exec($ch);
    $result = json_decode($jsonresult, 1);
//close connection
    curl_close($ch);
    return $result['items'];
}

function get_user_saved_ratings($access_token, $offset = 0)
{
    $ratings = array();
    $tracks = get_user_saved_tracks($access_token, $offset);
    foreach ($tracks as $song) {
        array_push($ratings, $song['track']['popularity']);
    }
    return $ratings;
}

function get_ratings_from_tracks($tracks, $offset = 0)
{
    $ratings = array();
    foreach ($tracks as $song) {
        array_push($ratings, $song['track']['popularity']);
    }
    return $ratings;
}

function get_user_playlists($access_token, $offset = 0)
{
    $url = 'https://api.spotify.com/v1/me/playlists?limit=50&offset=' . $offset;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = [
        'Authorization: Bearer ' . $access_token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


//execute post
    $jsonresult = curl_exec($ch);
    $result = json_decode($jsonresult, 1);
//close connection
    curl_close($ch);
    return $result['items'];
}

function get_tracks_from_playlist($access_token, $playlist_id, $owner_id, $offset = 0)
{
    $url = 'https://api.spotify.com/v1/users/' . $owner_id . '/playlists/' . $playlist_id . '/tracks?limit=100&offset=' . $offset;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = [
        'Authorization: Bearer ' . $access_token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


//execute post
    $jsonresult = curl_exec($ch);
    $result = json_decode($jsonresult, 1);
//close connection
    curl_close($ch);
    return $result['items'];
}