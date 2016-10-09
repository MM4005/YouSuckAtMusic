<?php
session_start();
//extract data from the post
//set POST variables
$url = 'https://accounts.spotify.com/api/token';
$fields = array(
    'grant_type' => urlencode('authorization_code'),
    'code' => urlencode($_GET['code']),
    'redirect_uri' => urlencode('https://yousuckatmusic.com/confirm_login')
);
$fields_string = '';
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$headers = [
    'Authorization: Basic CLIENTID:CLIENTSECRET'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


//execute post
$jsonresult = curl_exec($ch);
$result = json_decode($jsonresult, 1);
//close connection
curl_close($ch);
$_SESSION['logged_in'] = true;
$_SESSION['access_token'] = $result['access_token'];
header('Location: https://yousuckatmusic.com/');