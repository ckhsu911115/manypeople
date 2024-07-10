<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $good_things = $_POST['good_things'];
    $reflection = $_POST['reflection'];
    $improvement = $_POST['improvement'];
    $date = date('Y-m-d');

    $sql = "UPDATE diary_entries SET good_things='$good_things', reflection='$reflection', improvement='$improvement' WHERE user_id='$user_id' AND date='$date'";
    if (mysqli_query($conn, $sql)) {
        // 獲取日記條目ID
        $sql = "SELECT id FROM diary_entries WHERE user_id='$user_id' AND date='$date'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $entry_id = $row['id'];

        // 保存自訂問題的答案
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'custom_') === 0) {
                $question_id = str_replace('custom_', '', $key);
                $answer = $value;
                $sql = "INSERT INTO custom_answers (entry_id, question_id, answer) VALUES ('$entry_id', '$question_id', '$answer')";
                mysqli_query($conn, $sql);
            }
        }

        header("Location: index.html");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
