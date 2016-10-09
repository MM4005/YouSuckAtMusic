<?php
session_start();
require_once 'functions.php';
if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] === false) {

    ?>
    <!-- Created by Matthew Manley (MM4005) -->
    <!-- https://github.com/MM4005 -->
    <html>
    <head>
        <title>YSAM</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <h1>You Suck at Music</h1>
    <br>
    <br>
    <a href="login"><img class="img-center" src="images/login-button-mobile.png"></a>
    <br>
    <br>
    <!--<h2><a href="about" class="button-link">About</a></h2>-->
    <!-- Click on the link below to get rick rolled. -->
    <h2><a href="no_spotify" class="button-link">But I Don't Use Spotify!</a></h2>
    </body>
    </html>
    <?php
} else {
    $userinfo = get_user_info($_SESSION['access_token']);
    $tracks = get_user_saved_tracks($_SESSION['access_token']);
    $playlists = get_user_playlists($_SESSION['access_token']);
    ?>
    <!-- Created by Matthew Manley (MM4005) -->
    <!-- https://github.com/MM4005 -->
    <html>
    <head>
        <title>YSAM</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <h1>You Suck at Music</h1>
    <br>
    <?php
    if (count($userinfo['images']) > 0) {
        ?><img src="<?php echo $userinfo['images'][0]['url']; ?>" class="img-circle img-center" width="250px"
               alt="Spotify Profile Picture">
        <?php
    }
    ?>
    <br>
    <hr>
    <br>
    <h2>Saved Songs Playlist:</h2>
    <h2 id="saved_score">Loading...</h2>
    <?php
    foreach ($playlists as $pl) {
        if ($pl['name'] !== 'Discover Weekly') {
            ?>
            <br>
            <hr><br>
            <h2>Playlist: <?php echo $pl['name']; ?></h2>
            <h2 id="<?php echo $pl['id']; ?>">Loading...</h2>
            <?php
        }
    }
    ?>
    <br><hr><br>
    <h2>Overall</h2>
    <h2 id="overall">Loading...</h2>
    <br><hr><br>
    <h2><a class="button-link" href="leaderboard">Leaderboard</a></h2>
    <h2><a class="button-link" href="logout">Logout!</a></h2>
    </body>
    <script>

        var allarr = [];

        function overall(arr){
            var average = arr.reduce(function (prev, current) {
                    return current + prev
                }, 0) / arr.length;
            average = Math.round(average * 100) / 100;
            document.getElementById("overall").innerHTML = average + " / 100";
            //I know this is vunerable. Please don't mess with it...
            $.get("submit_score?score=" + average);
        }
        var getData = function (arr, bufferSize, offset, callback) {
            var request = new XMLHttpRequest();
            request.open("GET", "/saved_tracks?offset=" + offset, true);
            request.setRequestHeader('Content-Type', 'application/json;charset=UTF-8; charset=UTF-8');
            request.send();

            request.onload = function () {
                // console.log("Status:", request.status);
                // console.log("Response:", request.responseText);
                if (request.status >= 200 && request.status < 400) {
                    /* Do stuff */
                    var results = JSON.parse(request.responseText);
                    Array.prototype.push.apply(arr, results);
                    if (results.length >= bufferSize && offset <= 1000) {
                        getData(arr, bufferSize, offset + bufferSize, callback)
                    }
                    else {
                        allarr = allarr.concat(arr)
                        overall(allarr)
                        callback(arr)
                    }
                }
                else {
                    /* Error Message */
                }

            };
            request.onerror = function () {
                // Connection error
            };
        };

        var getPlaylistData = function (arr, playlistid, ownerid, bufferSize, offset, callback) {
            //console.log(playlistid);
            var request = new XMLHttpRequest();
            request.open("GET", "/playlist_tracks?playlist_id=" + playlistid + "&owner_id=" + ownerid + "&offset=" + offset, true);
            request.setRequestHeader('Content-Type', 'application/json;charset=UTF-8; charset=UTF-8');
            request.send();

            request.onload = function () {
                // console.log("Status:", request.status);
                // console.log("Response:", request.responseText);
                if (request.status >= 200 && request.status < 400) {
                    /* Do stuff */
                    var results = JSON.parse(request.responseText);
                    Array.prototype.push.apply(arr, results);
                    if (results.length >= bufferSize && offset <= 300) {
                        getPlaylistData(arr, playlistid, ownerid, bufferSize, offset + bufferSize, callback)
                    }
                    else {
                        allarr = allarr.concat(arr)
                        overall(allarr)
                        callback(arr)
                    }
                }
                else {
                    console.log('Error!!!');
                }

            };
            request.onerror = function () {
                // Connection error
            };
        };

        //console.log('test1');
        function run() {
            var arr = [];
            var datalength = 50;
            var offset = 0;
            getData(arr, datalength, offset, function (result) {
                var average = result.reduce(function (prev, current) {
                        return current + prev
                    }, 0) / result.length;
                average = Math.round(average * 100) / 100;
                document.getElementById("saved_score").innerHTML = average + " / 100";
            });

            <?php
            foreach ($playlists as $pl) {
            if ($pl['name'] !== 'Discover Weekly'){

            ?>
            //console.log('Starting to find data for playlist <?php echo $pl['name']?>')
            arr = [];
            datalength = 100;
            offset = 0;
            getPlaylistData(arr, "<?php echo $pl['id']?>", "<?php echo $pl['owner']['id']?>", datalength, offset, function (result) {
                var average = result.reduce(function (prev, current) {
                        return current + prev
                    }, 0) / result.length;
                average = Math.round(average * 100) / 100;
                document.getElementById("<?php echo $pl['id']?>").innerHTML = average + " / 100";
            });
            <?php
            }
            }
            ?>
        }
        run();

    </script>
    </html>
    <?php
}
?>
