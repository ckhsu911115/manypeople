<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $gratitude = $_POST['gratitude'];
    $goals = $_POST['goals'];
    $affirmation = $_POST['affirmation'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO diary_entries (user_id, date, gratitude, goals, affirmation) VALUES ('$user_id', '$date', '$gratitude', '$goals', '$affirmation')";
    if (mysqli_query($conn, $sql)) {
        $entry_id = mysqli_insert_id($conn);
        
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
