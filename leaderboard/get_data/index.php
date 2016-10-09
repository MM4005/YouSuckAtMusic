<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli("localhost", "username", "pass", "table");
$stmt = $conn->prepare("SELECT `id`, `display_name`, `score` FROM leaderboard WHERE `score` < 100 AND `score` >= 0 ORDER BY `score` DESC ");
$stmt->bind_result($id, $display_name, $score);
$stmt->execute();
$count = 1;
?>
    <tr>
        <th>Place</th>
        <th>User</th>
        <th>Score</th>
    </tr>
<?php
while ($stmt->fetch()) {
    ?>
    <tr>
        <td><?php echo $count; ?></td>
        <td><?php
            if ($display_name !== null) {
                echo $display_name;
            } else {
                echo $id;
            }
            ?></td>
        <td><?php echo number_format($score, 2); ?> / 100</td>
    </tr>
    <?php
    $count = $count + 1;
}
?>