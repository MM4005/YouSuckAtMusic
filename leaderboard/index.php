<html>
<head>
    <title>YSAM Leaderboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 50%;
            margin: 0px auto;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #F1F1F1;
        }
    </style>
</head>
<body>
<h1>You Suck at Music</h1>
<table id="board">
</table>
<br>
<h2><a class="button-link" href="/">Main Page</a></h2>
</body>
<script>
    function getdata() {
        $.get("get_data/", function (data) {
            document.getElementById("board").innerHTML = data;
        });
    }
    getdata();
    setInterval(getdata, 1000);
</script>
</html>