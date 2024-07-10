<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];

    if ($mode == 'date' && isset($_GET['date'])) {
        $date = $_GET['date'];
        $sql = "SELECT * FROM diary_entries WHERE user_id='$user_id' AND date='$date'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "<h2>查看日記 - $date</h2>";
            echo "<p><strong>感恩練習：</strong> " . htmlspecialchars($row['gratitude']) . "</p>";
            echo "<p><strong>今日目標：</strong> " . htmlspecialchars($row['goals']) . "</p>";
            echo "<p><strong>積極肯定：</strong> " . htmlspecialchars($row['affirmation']) . "</p>";
            echo "<p><strong>今日的三件好事：</strong> " . htmlspecialchars($row['good_things']) . "</p>";
            echo "<p><strong>自我反思：</strong> " . htmlspecialchars($row['reflection']) . "</p>";
            echo "<p><strong>改進之處：</strong> " . htmlspecialchars($row['improvement']) . "</p>";
        } else {
            echo "沒有找到該日期的日記。";
        }
    } elseif ($mode == 'category' && isset($_GET['category'], $_GET['start_date'], $_GET['end_date'])) {
        $category = $_GET['category'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        $allowed_categories = ['gratitude', 'goals', 'affirmation', 'good_things', 'reflection', 'improvement'];
        if (!in_array($category, $allowed_categories)) {
            die("Invalid category.");
        }

        $sql = "SELECT date, $category FROM diary_entries WHERE user_id='$user_id' AND date BETWEEN '$start_date' AND '$end_date'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>查看日記 - $category ($start_date 至 $end_date)</h2>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p><strong>" . htmlspecialchars($row['date']) . ":</strong> " . htmlspecialchars($row[$category]) . "</p>";
            }
        } else {
            echo "沒有找到該日期範圍內的日記。";
        }
    } else {
        echo "請提供正確的參數。";
    }
} else {
    echo "請選擇查看模式。";
}

mysqli_close($conn);
?>
