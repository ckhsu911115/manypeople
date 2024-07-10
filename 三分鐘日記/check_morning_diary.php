<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');

$sql = "SELECT * FROM diary_entries WHERE user_id='$user_id' AND date='$date'";
$result = mysqli_query($conn, $sql);
$morning_written = false;

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (!empty($row['gratitude']) && !empty($row['goals']) && !empty($row['affirmation'])) {
        $morning_written = true;
    }
}

mysqli_close($conn);
echo json_encode(['morning_written' => $morning_written]);
?>
